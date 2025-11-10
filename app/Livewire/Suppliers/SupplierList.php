<?php

namespace App\Livewire\Suppliers;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Supplier;

class SupplierList extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $pendingDeleteId = null;
    public $selectedSupplierId = null;
    protected $paginationTheme = 'bootstrap';

    protected $listeners = ['supplierSaved' => '$refresh'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function edit(int $id): void
    {
        $this->dispatch('editSupplier', id: $id);
    }

    public function showDetails(int $id): void
    {
        $this->selectedSupplierId = $id;
    }

    public function confirmDelete(int $id): void
    {
        $this->pendingDeleteId = $id;
    }

    public function toggleStatus(int $id): void
    {
        // Require edit permission
        if (!auth()->user()?->can('edit-suppliers')) {
            // Optional: surface a notification event
            $this->dispatch('notify', type: 'danger', message: __('suppliers.unauthorized'));
            return;
        }

        $supplier = Supplier::with('card')->find($id);
        if (!$supplier) {
            return;
        }

        $current = $supplier->card?->status ?? 'active';
        $new = $current === 'active' ? 'suspended' : 'active';

        $card = $supplier->card ?: new \App\Models\SupplierCard();
        $card->supplier_id = $supplier->id;
        $card->status = $new;
        $card->save();

        // Refresh list
        $this->dispatch('supplierSaved');
    }

    public function delete(): void
    {
        if (!$this->pendingDeleteId) return;
        $supplier = Supplier::find($this->pendingDeleteId);
        if ($supplier) {
            $supplier->delete();
            session()->flash('message', __('suppliers.deleted_success'));
        }
        $this->pendingDeleteId = null;
        $this->dispatch('supplierSaved');
    }

    public function render()
    {
        $query = Supplier::query()->with(['translations', 'card']);
        if ($this->search) {
            $term = $this->search;
            $query->where(function ($q) use ($term) {
                $q->whereHas('translations', function ($t) use ($term) {
                    $t->where('name', 'like', "%{$term}%");
                })->orWhere('code', 'like', "%{$term}%");
            });
        }

        return view('livewire.suppliers.supplier-list', [
            'suppliers' => $query->orderByDesc('id')->paginate($this->perPage),
        ]);
    }
}
