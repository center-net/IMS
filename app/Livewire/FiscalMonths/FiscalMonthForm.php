<?php

namespace App\Livewire\FiscalMonths;

use App\Models\FiscalMonth;
use App\Models\FiscalYear;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;

class FiscalMonthForm extends Component
{
    public $fiscal_year_id;
    public $name; // deprecated from UI, still used to store localized label
    public $start_date;
    public $end_date;
    public $status = 'open';
    public $selected_month = null; // 1..12
    public $manual_dates = false; // يسمح بتعديل التواريخ يدوياً

    public function mount(): void
    {
        $fy = request()->query('fiscal_year_id');
        if ($fy) {
            $this->fiscal_year_id = (int) $fy;
        }
    }

    protected function rules()
    {
        return [
            'fiscal_year_id' => ['required', Rule::exists('fiscal_years', 'id')],
            'selected_month' => ['required', 'integer', 'between:1,12'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'status' => ['required', Rule::in(['open', 'closed'])],
        ];
    }

    public function updatedStartDate($value)
    {
        if ($value) {
            $this->end_date = Carbon::parse($value)->addMonth()->subDay()->toDateString();
        }
    }

    public function save()
    {
        $this->validate();

        $fy = FiscalYear::find($this->fiscal_year_id);
        if (!$fy) {
            $this->addError('fiscal_year_id', __('fiscal_months.fiscal_year_required'));
            return;
        }

        // إذا تم اختيار الشهر، اضبط تواريخ البداية والنهاية على أساس السنة المالية
        if ($this->selected_month) {
            $start = Carbon::create((int) $fy->year, (int) $this->selected_month, 1)->toDateString();
            $end = Carbon::create((int) $fy->year, (int) $this->selected_month, 1)->addMonth()->subDay()->toDateString();
            $this->start_date = $start;
            $this->end_date = $end;
        }

        $startYear = Carbon::parse($this->start_date)->year;
        $endYear = Carbon::parse($this->end_date)->year;
        if ($startYear !== (int) $fy->year) {
            $this->addError('start_date', __('fiscal_months.start_date_year_eq', ['year' => $fy->year]));
            return;
        }
        if ($endYear !== (int) $fy->year) {
            $this->addError('end_date', __('fiscal_months.end_date_year_eq', ['year' => $fy->year]));
            return;
        }

        // منع تكرار نفس الشهر داخل نفس السنة المالية
        if ($this->selected_month) {
            $exists = FiscalMonth::query()
                ->where('fiscal_year_id', $fy->id)
                ->whereMonth('start_date', (int) $this->selected_month)
                ->exists();
            if ($exists) {
                $this->addError('selected_month', __('fiscal_months.month_exists'));
                return;
            }
        }

        $fm = new FiscalMonth();
        $fm->fiscal_year_id = $fy->id;
        $fm->start_date = $this->start_date;
        $fm->end_date = $this->end_date;
        // Enforce default status as open on creation
        $fm->status = 'open';
        // set translation for current locale
        $locale = app()->getLocale();
        $monthName = $this->selected_month
            ? Carbon::createFromDate((int) $fy->year, (int) $this->selected_month, 1)->locale(app()->getLocale())->translatedFormat('F')
            : ($this->name ?? '');
        $fm->translateOrNew($locale)->name = $monthName;
        $fm->save();

        $this->dispatch('fiscalMonthSaved');
        session()->flash('message', __('fiscal_months.created'));
        $this->reset(['name', 'selected_month', 'start_date', 'end_date', 'status', 'manual_dates']);
    }

    public function render()
    {
        $availableMonths = [];
        $fyYear = null;
        if ($this->fiscal_year_id) {
            $fy = FiscalYear::find($this->fiscal_year_id);
            $fyYear = $fy?->year;
            $taken = $fy
                ? FiscalMonth::where('fiscal_year_id', $fy->id)
                    ->selectRaw('MONTH(start_date) as m')
                    ->pluck('m')
                    ->map(fn($m) => (int) $m)
                    ->all()
                : [];
            for ($m = 1; $m <= 12; $m++) {
                if (!in_array($m, $taken, true)) {
                    $availableMonths[$m] = Carbon::create((int) $fy->year, $m, 1)->locale(app()->getLocale())->translatedFormat('F');
                }
            }
        }

        return view('livewire.fiscal-months.fiscal-month-form', [
            'fiscalYears' => FiscalYear::orderByDesc('year')->get(),
            'minStart' => $fyYear ? Carbon::create($fyYear, 1, 1)->toDateString() : null,
            'maxStart' => $fyYear ? Carbon::create($fyYear, 12, 31)->toDateString() : null,
            'availableMonths' => $availableMonths,
        ]);
    }

    public function updatedSelectedMonth($value)
    {
        if ($this->fiscal_year_id && $value) {
            $fy = FiscalYear::find($this->fiscal_year_id);
            if ($fy) {
                $this->start_date = Carbon::create((int) $fy->year, (int) $value, 1)->toDateString();
                $this->end_date = Carbon::create((int) $fy->year, (int) $value, 1)->addMonth()->subDay()->toDateString();
            }
        }
    }

    public function updatedFiscalYearId($value)
    {
        // عند تغيير السنة، أعد ضبط الشهر المختار والتواريخ، وسيُعاد حساب الأشهر المتاحة تلقائياً
        $this->fiscal_year_id = $value ? (int) $value : null;
        $this->selected_month = null;
        $this->start_date = null;
        $this->end_date = null;
    }
}
