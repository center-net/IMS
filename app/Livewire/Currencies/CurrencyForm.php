<?php

namespace App\Livewire\Currencies;

use Livewire\Component;
use Illuminate\Validation\Rule;
use App\Models\Currency;
use App\Services\CurrencyRateService;

class CurrencyForm extends Component
{
    public $currencyId = null;
    public $name = '';
    public $code = '';
    public $symbol = '';

    protected $listeners = ['editCurrency' => 'loadCurrency'];

    protected function rules(): array
    {
        $rules = [
            'code' => [
                'required', 'string', 'max:10',
                Rule::unique('currencies', 'code')->ignore($this->currencyId),
            ],
            'symbol' => ['nullable', 'string', 'max:10'],
            'name' => ['required', 'string', 'max:255'],
        ];

        return $rules;
    }

    public function save(): void
    {
        $this->validate();

        $isNew = $this->currencyId === null;

        $data = [
            'code' => strtoupper(trim($this->code)),
            'symbol' => $this->symbol,
        ];

        if ($this->currencyId) {
            $currency = Currency::findOrFail($this->currencyId);
            $currency->update($data);
        } else {
            $currency = Currency::create($data);
            $this->currencyId = $currency->id;
        }

        $locale = app()->getLocale();
        $currency->translateOrNew($locale)->name = $this->name;
        $currency->save();

        // تحديث سعر الصرف تلقائياً إن لم تكن العملة USD
        session()->flash('message', $isNew ? __('currencies.created_success') : __('currencies.updated_success'));
        $this->dispatch('currencySaved');
        $this->resetForm();
    }

    public function loadCurrency(int $id): void
    {
        $currency = Currency::with('translations')->findOrFail($id);
        $this->currencyId = $currency->id;
        $this->code = $currency->code;
        $this->symbol = $currency->symbol;
        $this->name = optional($currency->translate(app()->getLocale()))->name ?? '';
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->currencyId = null;
        $this->code = '';
        $this->symbol = '';
        $this->name = '';
    }

    public function render()
    {
        return view('livewire.currencies.currency-form');
    }
}
