<?php

namespace App\Livewire\FiscalYears;

use Livewire\Component;
use App\Models\FiscalYear;
use Illuminate\Support\Carbon;

class FiscalYearForm extends Component
{
    public $name = '';
    public $year = null;
    public $start_date = '';
    public $end_date = '';

    // Edit disabled; no listeners needed

    protected function rules()
    {
        $currentYear = (int) Carbon::now()->year;
        return [
            'name' => ['required', 'string', 'max:255'],
            'year' => ['required', 'integer', 'min:'.$currentYear, 'max:3000'],
            'start_date' => ['required', 'date'],
            // النهاية يجب أن تكون بعد البداية (ليس مساويًا)
            'end_date' => ['nullable', 'date', 'after:start_date'],
        ];
    }

    public function save()
    {
        $data = $this->validate();

        // التحقق بأن تاريخ البداية ليس قبل 1 يناير للسنة المختارة
        $startOfSelectedYear = Carbon::create((int) $data['year'], 1, 1)->startOfDay();
        $startDate = Carbon::parse($data['start_date'])->startOfDay();
        if ($startDate->lt($startOfSelectedYear)) {
            $this->addError('start_date', __('fiscal_years.start_date_year_min'));
            return;
        }

        // ضبط تاريخ النهاية تلقائيًا إذا لم يُحدّد
        if (empty($data['end_date']) && !empty($data['start_date'])) {
            $data['end_date'] = Carbon::parse($data['start_date'])->addYear()->subDay()->toDateString();
        }

        // التحقق بأن تاريخ النهاية ليس أقل من السنة المختارة وبأنه بعد البداية
        if (!empty($data['end_date'])) {
            $endDate = Carbon::parse($data['end_date'])->startOfDay();
            if ($endDate->year < (int) $data['year'] || !$endDate->gt($startDate)) {
                $this->addError('end_date', __('fiscal_years.end_date_after'));
                return;
            }
        }
        try {
            if (!auth()->user()?->can('create-fiscal-years')) {
                $this->dispatch('notify', type: 'danger', message: __('fiscal_years.unauthorized'));
                return;
            }
            $fy = new FiscalYear();
            $fy->year = $data['year'];
            $fy->start_date = $data['start_date'];
            $fy->end_date = $data['end_date'];
            // Ensure new fiscal years start with status 'open'
            $fy->status = 'open';
            $fy->translateOrNew(app()->getLocale())->name = $data['name'];
            $fy->save();
            $this->dispatch('notify', type: 'success', message: __('fiscal_years.created'));
            $this->dispatch('fiscalYearSaved');
            $this->resetForm();
            $this->resetErrorBag();
            $this->resetValidation();
        } catch (\Throwable $e) {
            $this->dispatch('notify', type: 'danger', message: __('fiscal_years.save_failed'));
        }
    }

    public function resetForm()
    {
        $this->name = '';
        $this->year = null;
        $this->start_date = '';
        $this->end_date = '';
        $this->resetValidation();
        $this->resetErrorBag();
    }

    public function cancel()
    {
        $this->resetForm();
        $this->resetErrorBag();
        $this->resetValidation();
        $this->dispatch('notify', type: 'info', message: __('fiscal_years.cancelled'));
    }

    // ضبط نهاية السنة تلقائيًا عند تحديد البداية
    public function updatedStartDate($value)
    {
        if (!empty($value)) {
            try {
                $this->end_date = Carbon::parse($value)->addYear()->subDay()->toDateString();
            } catch (\Throwable $e) {
                // تجاهل في حال كان الإدخال غير صالح
            }
        }
    }

    public function render()
    {
        return view('livewire.fiscal-years.fiscal-year-form');
    }
}
