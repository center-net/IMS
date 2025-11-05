<?php

namespace App\Livewire\Companies;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Company;

class CompanyList extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $perPage = 10;
    public $pendingDeleteId = null;
    public $editingId = null;
    public $name = '';
    public $address = '';
    public $phone = '';
    public $tax_percentage = 0;
    public $email = '';
    public $logo = ''; // existing logo path for preview fallback
    public $logoFile;  // uploaded file during edit
    protected $paginationTheme = 'bootstrap';

    protected $listeners = ['companySaved' => '$refresh'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:1000'],
            'phone' => ['nullable', 'string', 'max:30'],
            'tax_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
            'email' => ['nullable', 'email', 'max:255'],
            'logoFile' => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function edit($id)
    {
        if (!auth()->user()?->can('edit-companies')) {
            $this->dispatch('notify', type: 'danger', message: __('companies.unauthorized'));
            return;
        }
        $company = Company::findOrFail($id);
        $this->editingId = $company->id;
        $this->phone = $company->phone;
        $this->tax_percentage = $company->tax_percentage;
        $this->logo = $company->logo;
        $this->logoFile = null;
        $this->email = $company->email;
        $this->name = optional($company->translate(app()->getLocale()))->name ?? $company->name;
        $this->address = optional($company->translate(app()->getLocale()))->address ?? '';
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function save()
    {
        if (!$this->editingId) return;
        if (!auth()->user()?->can('edit-companies')) {
            $this->dispatch('notify', type: 'danger', message: __('companies.unauthorized'));
            return;
        }
        $data = $this->validate();
        try {
            $company = Company::findOrFail($this->editingId);
            $company->phone = $data['phone'] ?? null;
            $company->tax_percentage = $data['tax_percentage'];
            $company->email = $data['email'] ?? null;
            $company->translateOrNew(app()->getLocale())->name = $data['name'];
            $company->translateOrNew(app()->getLocale())->address = $data['address'] ?? null;
            if (!empty($data['logoFile'])) {
                $path = $this->logoFile->store('logos', 'public');
                $company->logo = 'storage/' . $path;
            }
            $company->save();
            $this->dispatch('notify', type: 'success', message: __('companies.updated_success'));
            $this->editingId = null;
            $this->logoFile = null;
            $this->dispatch('companySaved');
            $this->resetErrorBag();
            $this->resetValidation();
        } catch (\Throwable $e) {
            $this->dispatch('notify', type: 'danger', message: __('companies.save_failed'));
        }
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
