<?php

namespace App\Livewire\Villages;

use Livewire\Component;
use App\Models\Village;
use App\Models\City;

class VillageForm extends Component
{
    public $villageId = null;
    public $name = '';
    public $city_id = null;
    // تم إلغاء بند السعر للقرى

    protected $listeners = ['editVillage' => 'loadVillage'];

    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'city_id' => ['required', 'integer', 'exists:cities,id'],
            // لا يوجد سعر للقرى بعد التعديل
        ];
    }

    public function save()
    {
        $data = $this->validate();
        try {
            if ($this->villageId) {
                if (!auth()->user()?->can('edit-villages')) {
                    $this->dispatch('notify', type: 'danger', message: __('villages.unauthorized'));
                    return;
                }
                $village = Village::findOrFail($this->villageId);
                $village->city_id = $data['city_id'];
                // تم حذف السعر من القرى
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
                // تم حذف السعر من القرى
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
        // لا تحميل لسعر القرى بعد الإزالة
        $this->name = optional($village->translate(app()->getLocale()))->name ?? $village->name;
    }

    public function resetForm()
    {
        $this->villageId = null;
        $this->name = '';
        $this->city_id = null;
        // لا إعادة تعيين لسعر القرى بعد الإزالة
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
        $cities = City::with('country')->orderBy('id', 'desc')->get();
        return view('livewire.villages.village-form', [
            'cities' => $cities,
        ]);
    }
}
