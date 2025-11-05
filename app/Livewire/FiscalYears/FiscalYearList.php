<?php

namespace App\Livewire\FiscalYears;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\FiscalYear;

class FiscalYearList extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    protected $paginationTheme = 'bootstrap';

    protected $listeners = ['fiscalYearSaved' => '$refresh'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function details($id)
    {
        // Navigate to fiscal months index pre-filtered by selected fiscal year
        return redirect()->route('fiscal-months.index', ['fiscal_year_id' => $id]);
    }

    public function closeYear($id)
    {
        try {
            if (!auth()->user()?->can('close-fiscal-years')) {
                $this->dispatch('notify', type: 'danger', message: __('fiscal_years.unauthorized'));
                return;
            }
            $fy = FiscalYear::findOrFail($id);
            if ($fy->status !== 'closed') {
                $fy->status = 'closed';
                $fy->save();
                session()->flash('message', __('fiscal_years.closed_success'));
            }
        } catch (\Throwable $e) {
            $this->dispatch('notify', type: 'danger', message: __('fiscal_years.save_failed'));
        }
    }

    // Edit and delete actions disabled per requirements

    public function render()
    {
        $query = FiscalYear::query()
            ->when($this->search, function ($q) {
                $term = $this->search;
                $q->where(function ($qq) use ($term) {
                    $qq->whereTranslationLike('name', "%{$term}%")
                       ->orWhere('code', 'like', "%{$term}%")
                       ->orWhere('year', 'like', "%{$term}%");
                });
            })
            ->orderByDesc('year');

        $fiscalYears = $query->paginate($this->perPage);
        return view('livewire.fiscal-years.fiscal-year-list', [
            'fiscalYears' => $fiscalYears,
        ]);
    }
}
