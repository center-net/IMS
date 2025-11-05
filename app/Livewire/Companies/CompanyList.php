<?php

namespace App\Livewire\Companies;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Company;

class CompanyList extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $pendingDeleteId = null;
    protected $paginationTheme = 'bootstrap';

    protected $listeners = ['companySaved' => '$refresh'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function edit($id)
    {
        $this->dispatch('editCompany', id: $id);
    }

    public function confirmDelete($id)
    {
        if (!auth()->user()?->can('delete-companies')) {
            $this->dispatch('notify', type: 'danger', message: __('companies.unauthorized'));
            return;
        }
        $this->pendingDeleteId = $id;
        $this->dispatch('showConfirmModal');
    }

    public function delete()
    {
        if (!$this->pendingDeleteId) return;
        try {
            $company = Company::findOrFail($this->pendingDeleteId);
            $company->delete();
            $this->dispatch('notify', type: 'success', message: __('companies.deleted_success'));
            $this->pendingDeleteId = null;
            $this->resetPage();
        } catch (\Throwable $e) {
            $this->dispatch('notify', type: 'danger', message: __('companies.delete_failed'));
        }
    }

    public function render()
    {
        $query = Company::query()
            ->when($this->search, function ($q) {
                $term = $this->search;
                $q->whereTranslationLike('name', "%{$term}%");
            })
            ->orderByDesc('id');

        $companies = $query->paginate($this->perPage);

        return view('livewire.companies.company-list', [
            'companies' => $companies,
        ]);
    }
}

