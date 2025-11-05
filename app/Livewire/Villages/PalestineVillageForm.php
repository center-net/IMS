<?php

namespace App\Livewire\Villages;

use Livewire\Component;
use App\Models\Village;
use App\Models\City;
use App\Models\Country;

class PalestineVillageForm extends Component
{
    public $villageId = null;
    public $name = '';
    public $city_id = null;
    public $citySearch = '';

    protected $listeners = ['editVillage' => 'loadVillage'];

    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'city_id' => ['required', 'integer', 'exists:cities,id'],
        ];
    }

    private function getPalestineId(): ?int
    {
        $palestine = Country::where('iso_code', 'PS')->first();
        return $palestine?->id;
    }

    private function ensureCityIsPalestine(int $cityId): bool
    {
        $palestineId = $this->getPalestineId();
        return City::where('id', $cityId)->where('country_id', $palestineId)->exists();
    }

    public function save()
    {
        $data = $this->validate();

        // تأكيد أن المدينة المختارة ضمن فلسطين
        if (!$this->ensureCityIsPalestine((int) $data['city_id'])) {
            $this->dispatch('notify', type: 'danger', message: __('villages.unauthorized'));
            return;
        }

        try {
            if ($this->villageId) {
                if (!auth()->user()?->can('edit-villages')) {
                    $this->dispatch('notify', type: 'danger', message: __('villages.unauthorized'));
                    return;
                }
                $village = Village::findOrFail($this->villageId);
                $village->city_id = $data['city_id'];
                $village->translateOrNew(app()->getLocale())->name = $data['name'];
                $village->save();
                $this->dispatch('notify', type: 'success', message: __('villages.updated_success'));
            } else {
                if (!auth()->user()?->can('create-villages')) {
                    $this->dispatch('notify', type: 'danger', message: __('villages.unauthorized'));
                    return;
                }
                $village = new Village();
                $village->city_id = $data['city_id'];
                $village->translateOrNew(app()->getLocale())->name = $data['name'];
                $village->save();
                $this->dispatch('notify', type: 'success', message: __('villages.created'));
            }
            $this->dispatch('villageSaved');
            $this->resetForm();
            $this->resetErrorBag();
            $this->resetValidation();
        } catch (\Throwable $e) {
            $this->dispatch('notify', type: 'danger', message: __('villages.save_failed'));
        }
    }


    public function loadVillage($id)
    {
        $this->resetErrorBag();
        $this->resetValidation();
        if (!auth()->user()?->can('edit-villages')) {
            $this->dispatch('notify', type: 'danger', message: __('villages.unauthorized'));
            return;
        }
        $village = Village::findOrFail($id);
        $this->villageId = $village->id;
        $this->city_id = $village->city_id;
        $this->name = optional($village->translate(app()->getLocale()))->name ?? $village->name;
    }

    public function resetForm()
    {
        $this->villageId = null;
        $this->name = '';
        $this->city_id = null;
        $this->resetValidation();
        $this->resetErrorBag();
    }

    public function cancel()
    {
        $this->resetForm();
        $this->resetErrorBag();
        $this->resetValidation();
        $this->dispatch('notify', type: 'info', message: __('villages.cancelled'));
    }

    public function render()
    {
        $palestineId = $this->getPalestineId();
        $cities = City::query()
            ->where('country_id', $palestineId)
            ->when($this->citySearch, function ($q) {
                $term = $this->citySearch;
                $q->whereTranslationLike('name', "%{$term}%");
            })
            ->orderBy('id', 'desc')
            ->get();
        return view('livewire.villages.palestine-village-form', [
            'cities' => $cities,
        ]);
    }
}

