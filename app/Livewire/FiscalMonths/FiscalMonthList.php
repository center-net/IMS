<?php

namespace App\Livewire\FiscalMonths;

use App\Models\FiscalMonth;
use App\Models\FiscalYear;
use Livewire\Component;
use Livewire\WithPagination;

class FiscalMonthList extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $fiscal_year_id;

    protected $queryString = [
        'search' => ['except' => ''],
        'fiscal_year_id' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFiscalYearId()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function closeMonth(int $id): void
    {
        if (!auth()->user()?->can('close-fiscal-months')) {
            $this->dispatch('notify', type: 'danger', message: __('fiscal_months.unauthorized'));
            return;
        }
        $month = FiscalMonth::find($id);
        if (!$month) {
            return;
        }
        if ($month->status === 'closed') {
            return;
        }
        $month->status = 'closed';
        $month->save();
        session()->flash('message', __('fiscal_months.closed_success'));
    }

    public function render()
    {
        $query = FiscalMonth::query()->with('translations');
        if ($this->fiscal_year_id) {
            $query->where('fiscal_year_id', $this->fiscal_year_id);
        }
        if ($this->search) {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('translations', function ($t) use ($search) {
                    $t->where('name', 'like', '%' . $search . '%');
                })
                ->orWhere('code', 'like', '%' . $search . '%');
            });
        }

        return view('livewire.fiscal-months.fiscal-month-list', [
            'months' => $query->orderBy('start_date')->paginate($this->perPage),
            'fiscalYears' => FiscalYear::orderByDesc('year')->get(),
        ]);
    }
}
