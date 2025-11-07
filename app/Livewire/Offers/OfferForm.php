<?php

namespace App\Livewire\Offers;

use Livewire\Component;
use Illuminate\Validation\Rule;
use App\Models\Offer;

class OfferForm extends Component
{
    public $offer_id = null;
    public $editing = false;

    public $name = '';
    public $code = '';
    public $price = 0;
    public $original_price = 0;
    public $start_date = '';
    public $end_date = '';

    protected $listeners = [
        'editOffer' => 'loadOffer',
        'offerSaved' => '$refresh',
    ];

    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', Rule::unique('offers', 'code')->ignore($this->offer_id)],
            'price' => ['required', 'numeric', 'min:0'],
            'original_price' => ['required', 'numeric', 'min:0'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
        ];
    }

    public function submit()
    {
        // Ensure code is auto-generated if missing (on create or when cleared)
        if (empty($this->code)) {
            $this->code = Offer::generateUniqueCode();
        }
        $data = $this->validate();
        // Ensure logical pricing
        if ($this->original_price < $this->price) {
            $this->addError('original_price', __('offers.original_ge_price'));
            return;
        }

        if ($this->editing) {
            try {
                $offer = Offer::find($this->offer_id);
                if (!$offer) return;
                $offer->price = (float) $this->price;
                $offer->original_price = (float) $this->original_price;
                $offer->start_date = $this->start_date;
                $offer->end_date = $this->end_date;
                // Keep code required; if cleared, regenerate was already handled above
                $offer->code = $this->code;
                $offer->translateOrNew(app()->getLocale())->name = $this->name;
                $offer->save();
                $this->dispatch('notify', type: 'success', message: __('offers.updated'));
                $this->dispatch('offerSaved');
                $this->resetForm();
            } catch (\Throwable $e) {
                $this->dispatch('notify', type: 'danger', message: __('offers.save_failed'));
            }
        } else {
            try {
                $offer = new Offer();
                $offer->price = (float) $this->price;
                $offer->original_price = (float) $this->original_price;
                $offer->start_date = $this->start_date;
                $offer->end_date = $this->end_date;
                // Code is required and auto-generated before validation
                $offer->code = $this->code;
                $offer->translateOrNew(app()->getLocale())->name = $this->name;
                $offer->save();
                $this->dispatch('notify', type: 'success', message: __('offers.created'));
                $this->dispatch('offerSaved');
                $this->resetForm();
            } catch (\Throwable $e) {
                $this->dispatch('notify', type: 'danger', message: __('offers.save_failed'));
            }
        }
    }

    public function render()
    {
        return view('livewire.offers.offer-form');
    }

    public function loadOffer(int $id): void
    {
        $offer = Offer::find($id);
        if (!$offer) return;
        $this->offer_id = $offer->id;
        $this->name = optional($offer->translate(app()->getLocale()))->name ?? '';
        $this->code = $offer->code;
        $this->price = $offer->price;
        $this->original_price = $offer->original_price;
        $this->start_date = $offer->start_date;
        $this->end_date = $offer->end_date;
        $this->editing = true;
        $this->resetErrorBag();
        $this->resetValidation();
    }

    private function resetForm(): void
    {
        $this->reset(['offer_id', 'editing', 'name', 'code', 'price', 'original_price', 'start_date', 'end_date']);
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function cancel(): void
    {
        $this->resetForm();
        $this->dispatch('notify', type: 'info', message: __('offers.cancel'));
    }
}
