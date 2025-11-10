<?php

namespace App\Livewire\Suppliers;

use Livewire\Component;
use App\Models\Supplier;
use App\Models\SupplierCard;
use App\Models\City;
use App\Models\Village;
use App\Models\Currency;

class SupplierCardForm extends Component
{
    public int $supplierId;
    public bool $canEdit = true;

    public ?int $city_id = null;
    public ?int $village_id = null;
    public ?int $default_currency_id = null;
    public ?int $bank_account_currency_id = null;

    public string $name = '';
    public string $trade_name = '';
    public string $phone = '';
    public string $fax = '';
    public string $tax_number = '';
    public string $registration_number = '';
    public string $supplier_type = 'local';
    public string $status = 'active';
    public string $iban = '';
    public string $bank_name = '';
    public string $bank_account_number = '';
    public string $beneficiary_name = '';
    public string $notes = '';
    public $credit_limit = null; // numeric|string

    // Read-only meta info for display
    public ?string $created_at = null;
    public ?string $updated_at = null;
    public ?string $created_by_name = null;
    public ?int $attachmentsCount = null;

    protected function rules(): array
    {
        return [
            'name' => ['nullable','string','max:255'],
            'trade_name' => ['nullable','string','max:255'],
            'phone' => ['nullable','string','max:50'],
            'fax' => ['nullable','string','max:50'],
            'tax_number' => ['nullable','string','max:100'],
            'registration_number' => ['nullable','string','max:100'],
            'supplier_type' => ['required','in:local,foreign'],
            'status' => ['required','in:active,suspended'],
            'default_currency_id' => ['nullable','integer','exists:currencies,id'],
            'credit_limit' => ['nullable','numeric','min:0'],
            'city_id' => ['nullable','integer','exists:cities,id'],
            'village_id' => ['nullable','integer','exists:villages,id'],
            'bank_name' => ['nullable','string','max:255'],
            'bank_account_number' => ['nullable','string','max:100'],
            'iban' => ['nullable','string','max:100'],
            'beneficiary_name' => ['nullable','string','max:255'],
            'bank_account_currency_id' => ['nullable','integer','exists:currencies,id'],
            'notes' => ['nullable','string','max:2000'],
        ];
    }

    public function mount(int $supplierId): void
    {
        $this->supplierId = $supplierId;
        $supplier = Supplier::with(['card','card.translations'])->find($supplierId);
        $card = $supplier?->card;
        if ($card) {
            $locale = app()->getLocale();
            $this->name = optional($card->translate($locale))->name ?? '';
            $this->trade_name = optional($card->translate($locale))->trade_name ?? '';
            $this->phone = (string)($card->phone ?? '');
            $this->fax = (string)($card->fax ?? '');
            $this->tax_number = (string)($card->tax_number ?? '');
            $this->registration_number = (string)($card->registration_number ?? '');
            $this->supplier_type = $card->supplier_type ?? 'local';
            $this->status = $card->status ?? 'active';
            $this->default_currency_id = $card->default_currency_id;
            $this->credit_limit = $card->credit_limit;
            $this->city_id = $card->city_id;
            $this->village_id = $card->village_id;
            $this->bank_name = (string)($card->bank_name ?? '');
            $this->bank_account_number = (string)($card->bank_account_number ?? '');
            $this->iban = (string)($card->iban ?? '');
            $this->beneficiary_name = (string)($card->beneficiary_name ?? '');
            $this->bank_account_currency_id = $card->bank_account_currency_id;
            $this->notes = optional($card->translate($locale))->notes ?? '';

            $this->created_at = optional($card->created_at)->format('Y-m-d H:i');
            $this->updated_at = optional($card->updated_at)->format('Y-m-d H:i');
            $this->created_by_name = optional($card->creator)->name;
            $attachments = json_decode($card->attachments ?? '[]', true);
            $this->attachmentsCount = is_array($attachments) ? count($attachments) : 0;
        }
    }

    public function save(): void
    {
        if (!auth()->user()?->can('edit-suppliers')) {
            $this->dispatch('notify', type: 'danger', message: __('suppliers.unauthorized'));
            return;
        }
        $data = $this->validate();

        $supplier = Supplier::findOrFail($this->supplierId);
        $card = $supplier->card ?: new SupplierCard(['supplier_id' => $supplier->id]);

        $card->phone = $data['phone'] ?? null;
        $card->fax = $data['fax'] ?? null;
        $card->tax_number = $data['tax_number'] ?? null;
        $card->registration_number = $data['registration_number'] ?? null;
        $card->supplier_type = $data['supplier_type'];
        $card->status = $data['status'];
        $card->default_currency_id = $data['default_currency_id'] ?? null;
        $card->credit_limit = $data['credit_limit'] ?? null;
        $card->city_id = $data['city_id'] ?? null;
        $card->village_id = $data['village_id'] ?? null;
        $card->bank_name = $data['bank_name'] ?? null;
        $card->bank_account_number = $data['bank_account_number'] ?? null;
        $card->iban = $data['iban'] ?? null;
        $card->beneficiary_name = $data['beneficiary_name'] ?? null;
        $card->bank_account_currency_id = $data['bank_account_currency_id'] ?? null;
        $card->save();

        $locale = app()->getLocale();
        $card->translateOrNew($locale)->name = $data['name'] ?? '';
        $card->translateOrNew($locale)->trade_name = $data['trade_name'] ?? '';
        $card->translateOrNew($locale)->notes = $data['notes'] ?? '';
        $card->save();

        $this->dispatch('notify', type: 'success', message: __('suppliers.updated_success'));
        $this->dispatch('supplierSaved');
    }

    public function render()
    {
        return view('livewire.suppliers.supplier-card-form', [
            'cities' => City::query()->with('translations')->orderBy('id')->get(),
            'villages' => Village::query()->with('translations')->orderBy('id')->get(),
            'currencies' => Currency::query()->with('translations')->orderBy('id')->get(),
        ]);
    }
}
