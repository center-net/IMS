<?php

namespace App\Livewire\Suppliers;

use Livewire\Component;
use App\Models\Supplier;
use App\Models\SupplierCard;

class SupplierCardDetails extends Component
{
    public $supplierId;

    public function mount(int $supplierId): void
    {
        $this->supplierId = $supplierId;
    }

    public function render()
    {
        $supplier = Supplier::with(['translations', 'card', 'card.translations', 'card.city.translations', 'card.village.translations', 'card.defaultCurrency.translations', 'card.bankAccountCurrency.translations'])->find($this->supplierId);
        $card = $supplier?->card;
        return view('livewire.suppliers.supplier-card-details', [
            'supplier' => $supplier,
            'card' => $card,
        ]);
    }
}

