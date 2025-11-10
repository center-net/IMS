<?php

namespace App\Livewire\Suppliers;

use Livewire\Component;
use App\Models\Supplier;
use App\Models\SupplierCard;

class SupplierForm extends Component
{
    public $supplierId = null;
    public $name = '';

    protected $listeners = ['editSupplier' => 'loadSupplier'];

    protected function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
        ];

        return $rules;
    }

    public function save(): void
    {
        $data = $this->validate();
        try {
            if ($this->supplierId) {
                if (!auth()->user()?->can('edit-suppliers')) {
                    $this->dispatch('notify', type: 'danger', message: __('suppliers.unauthorized'));
                    return;
                }
                $supplier = Supplier::findOrFail($this->supplierId);
                $supplier->translateOrNew(app()->getLocale())->name = $data['name'];
                $supplier->save();
                // Ensure supplier card exists; rely on migration default status
                $card = $supplier->card ?: new SupplierCard();
                $card->supplier_id = $supplier->id;
                $card->save();
                $this->dispatch('notify', type: 'success', message: __('suppliers.updated_success'));
            } else {
                if (!auth()->user()?->can('create-suppliers')) {
                    $this->dispatch('notify', type: 'danger', message: __('suppliers.unauthorized'));
                    return;
                }
                $supplier = new Supplier();
                $supplier->translateOrNew(app()->getLocale())->name = $data['name'];
                $supplier->save();
                // Create supplier card; status defaults via migration
                $card = new SupplierCard();
                $card->supplier_id = $supplier->id;
                $card->save();
                $this->dispatch('notify', type: 'success', message: __('suppliers.created'));
            }
            $this->dispatch('supplierSaved');
            $this->resetForm();
        } catch (\Throwable $e) {
            $this->dispatch('notify', type: 'danger', message: __('suppliers.save_failed'));
        }
    }

    public function loadSupplier(int $id): void
    {
        $this->resetErrorBag();
        $this->resetValidation();
        if (!auth()->user()?->can('edit-suppliers')) {
            $this->dispatch('notify', type: 'danger', message: __('suppliers.unauthorized'));
            return;
        }
        $supplier = Supplier::findOrFail($id);
        $this->supplierId = $supplier->id;
        $this->name = optional($supplier->translate(app()->getLocale()))->name ?? '';
        // Status is managed via SupplierCard migration/defaults; not in form
    }

    public function resetForm(): void
    {
        $this->supplierId = null;
        $this->name = '';
        $this->status = 'active';
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function cancel(): void
    {
        $this->resetForm();
        $this->dispatch('notify', type: 'info', message: __('suppliers.cancelled'));
    }

    public function render()
    {
        return view('livewire.suppliers.supplier-form');
    }
}
