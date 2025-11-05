<?php

namespace App\Livewire\Companies;

use Livewire\Component;
use App\Models\Company;

class CompanyForm extends Component
{
    public $companyId = null;
    public $name = '';
    public $address = '';
    public $phone = '';
    public $tax_percentage = 0;
    public $email = '';
    public $logo = '';

    protected $listeners = ['editCompany' => 'loadCompany'];

    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:1000'],
            'phone' => ['nullable', 'string', 'max:30'],
            'tax_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
            'email' => ['nullable', 'email', 'max:255'],
            'logo' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function save()
    {
        $data = $this->validate();
        try {
            if ($this->companyId) {
                if (!auth()->user()?->can('edit-companies')) {
                    $this->dispatch('notify', type: 'danger', message: __('companies.unauthorized'));
                    return;
                }
                $company = Company::findOrFail($this->companyId);
                $company->phone = $data['phone'] ?? null;
                $company->tax_percentage = $data['tax_percentage'];
                $company->logo = $data['logo'] ?? null;
                $company->email = $data['email'] ?? null;
                $company->translateOrNew(app()->getLocale())->name = $data['name'];
                $company->translateOrNew(app()->getLocale())->address = $data['address'] ?? null;
                $company->save();
                $this->dispatch('notify', type: 'success', message: __('companies.updated_success'));
            } else {
                if (!auth()->user()?->can('create-companies')) {
                    $this->dispatch('notify', type: 'danger', message: __('companies.unauthorized'));
                    return;
                }
                $company = new Company();
                $company->phone = $data['phone'] ?? null;
                $company->tax_percentage = $data['tax_percentage'];
                $company->logo = $data['logo'] ?? null;
                $company->email = $data['email'] ?? null;
                $company->translateOrNew(app()->getLocale())->name = $data['name'];
                $company->translateOrNew(app()->getLocale())->address = $data['address'] ?? null;
                $company->save();
                $this->dispatch('notify', type: 'success', message: __('companies.created'));
            }
            $this->dispatch('companySaved');
            $this->resetForm();
            $this->resetErrorBag();
            $this->resetValidation();
        } catch (\Throwable $e) {
            $this->dispatch('notify', type: 'danger', message: __('companies.save_failed'));
        }
    }

    public function loadCompany($id)
    {
        $this->resetErrorBag();
        $this->resetValidation();
        if (!auth()->user()?->can('edit-companies')) {
            $this->dispatch('notify', type: 'danger', message: __('companies.unauthorized'));
            return;
        }
        $company = Company::findOrFail($id);
        $this->companyId = $company->id;
        $this->phone = $company->phone;
        $this->tax_percentage = $company->tax_percentage;
        $this->logo = $company->logo;
        $this->email = $company->email;
        $this->name = optional($company->translate(app()->getLocale()))->name ?? $company->name;
        $this->address = optional($company->translate(app()->getLocale()))->address ?? '';
    }

    public function resetForm()
    {
        $this->companyId = null;
        $this->name = '';
        $this->address = '';
        $this->phone = '';
        $this->tax_percentage = 0;
        $this->email = '';
        $this->logo = '';
        $this->resetValidation();
        $this->resetErrorBag();
    }

    public function cancel()
    {
        $this->resetForm();
        $this->resetErrorBag();
        $this->resetValidation();
        $this->dispatch('notify', type: 'info', message: __('companies.cancelled'));
    }

    public function render()
    {
        return view('livewire.companies.company-form');
    }
}

