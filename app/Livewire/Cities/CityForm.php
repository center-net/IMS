<?php

namespace App\Livewire\Cities;

use Livewire\Component;
use App\Models\City;
use App\Models\Country;

class CityForm extends Component
{
    public $cityId = null;
    public $name = '';
    public $country_id = null;
    public $delivery_price = 0;

    protected $listeners = ['editCity' => 'loadCity'];

    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'country_id' => ['required', 'integer', 'exists:countries,id'],
            'delivery_price' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function save()
    {
        $data = $this->validate();
        try {
            if ($this->cityId) {
                if (!auth()->user()?->can('edit-cities')) {
                    $this->dispatch('notify', type: 'danger', message: __('cities.unauthorized'));
                    return;
                }
                $city = City::findOrFail($this->cityId);
                $city->country_id = $data['country_id'];
                $city->delivery_price = $data['delivery_price'];
                $city->translateOrNew(app()->getLocale())->name = $data['name'];
                $city->save();
                $this->dispatch('notify', type: 'success', message: __('cities.updated_success'));
            } else {
                if (!auth()->user()?->can('create-cities')) {
                    $this->dispatch('notify', type: 'danger', message: __('cities.unauthorized'));
                    return;
                }
                $city = new City();
                $city->country_id = $data['country_id'];
                $city->delivery_price = $data['delivery_price'];
                $city->translateOrNew(app()->getLocale())->name = $data['name'];
                $city->save();
                $this->dispatch('notify', type: 'success', message: __('cities.created'));
            }
            $this->dispatch('citySaved');
            $this->resetForm();
            $this->resetErrorBag();
            $this->resetValidation();
        } catch (\Throwable $e) {
            $this->dispatch('notify', type: 'danger', message: __('cities.save_failed'));
        }
    }

    public function loadCity($id)
    {
        $this->resetErrorBag();
        $this->resetValidation();
        if (!auth()->user()?->can('edit-cities')) {
            $this->dispatch('notify', type: 'danger', message: __('cities.unauthorized'));
            return;
        }
        $city = City::findOrFail($id);
        $this->cityId = $city->id;
        $this->country_id = $city->country_id;
        $this->delivery_price = $city->delivery_price;
        $this->name = optional($city->translate(app()->getLocale()))->name ?? $city->name;
    }

    public function resetForm()
    {
        $this->cityId = null;
        $this->name = '';
        $this->country_id = null;
        $this->delivery_price = 0;
        $this->resetValidation();
        $this->resetErrorBag();
    }

    public function cancel()
    {
        $this->resetForm();
        $this->resetErrorBag();
        $this->resetValidation();
        $this->dispatch('notify', type: 'info', message: __('cities.cancelled'));
    }

    public function render()
    {
        $countries = Country::orderBy('id', 'desc')->get();
        return view('livewire.cities.city-form', [
            'countries' => $countries,
        ]);
    }
}

