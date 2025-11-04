<?php

namespace App\Livewire\Countries;

use Livewire\Component;
use App\Models\Country;

class CountryForm extends Component
{
    public $countryId = null;
    public $name = '';
    public $iso_code = '';
    public $national_number = '';

    protected $listeners = ['editCountry' => 'loadCountry'];

    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'iso_code' => ['required', 'string', 'max:3'],
            'national_number' => ['required', 'string', 'max:20'],
        ];
    }

    public function save()
    {
        // نفّذ التحقق أولاً لعرض أخطاء الحقول مباشرة
        $data = $this->validate();
        try {

            if ($this->countryId) {
                if (!auth()->user()?->can('edit-countries')) {
                    $this->dispatch('notify', type: 'danger', message: __('countries.unauthorized'));
                    return;
                }
                $country = Country::findOrFail($this->countryId);
                $country->iso_code = $data['iso_code'];
                $country->national_number = $data['national_number'] ?? null;
                $country->translateOrNew(app()->getLocale())->name = $data['name'];
                $country->save();
                $this->dispatch('notify', type: 'success', message: __('countries.updated_success'));
            } else {
                if (!auth()->user()?->can('create-countries')) {
                    $this->dispatch('notify', type: 'danger', message: __('countries.unauthorized'));
                    return;
                }
                $country = new Country();
                $country->iso_code = $data['iso_code'];
                $country->national_number = $data['national_number'] ?? null;
                $country->translateOrNew(app()->getLocale())->name = $data['name'];
                $country->save();
                $this->dispatch('notify', type: 'success', message: __('countries.created'));
            }

            $this->dispatch('countrySaved');
            $this->resetForm();
            // مسح أخطاء التحقق بعد الحفظ الناجح
            $this->resetErrorBag();
            $this->resetValidation();
        } catch (\Throwable $e) {
            // أخطاء تشغيلية غير مرتبطة بالتحقق
            $this->dispatch('notify', type: 'danger', message: __('countries.save_failed'));
        }
    }

    public function loadCountry($id)
    {
        // تنظيف أخطاء التحقق السابقة عند فتح إجراء جديد
        $this->resetErrorBag();
        $this->resetValidation();
        if (!auth()->user()?->can('edit-countries')) {
            $this->dispatch('notify', type: 'danger', message: __('countries.unauthorized'));
            return;
        }
        $country = Country::findOrFail($id);
        $this->countryId = $country->id;
        $this->iso_code = $country->iso_code;
        $this->national_number = $country->national_number;
        $this->name = optional($country->translate(app()->getLocale()))->name ?? $country->name;
    }

    public function resetForm()
    {
        $this->countryId = null;
        $this->name = '';
        $this->iso_code = '';
        $this->national_number = '';
        $this->resetValidation();
        $this->resetErrorBag();
    }

    public function cancel()
    {
        $this->resetForm();
        $this->resetErrorBag();
        $this->resetValidation();
        $this->dispatch('notify', type: 'info', message: __('countries.cancelled'));
    }

    public function render()
    {
        return view('livewire.countries.country-form');
    }
}
