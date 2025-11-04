<?php

namespace App\Livewire\Villages;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Village;
use App\Models\Country;
use App\Models\City;

class VillageList extends Component
{
    use WithPagination;

    public $search = '';
    public $countryFilter = null;
    public $cityFilter = null;
    public $perPage = 10;
    public $pendingDeleteId = null;
    protected $paginationTheme = 'bootstrap';

    protected $listeners = ['villageSaved' => '$refresh'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCountryFilter()
    {
        $this->resetPage();
    }

    public function updatingCityFilter()
    {
        $this->resetPage();
    }

    public function edit($id)
    {
        $this->dispatch('editVillage', id: $id);
    }

    public function confirmDelete($id)
    {
        if (!auth()->user()?->can('delete-villages')) {
            $this->dispatch('notify', type: 'danger', message: __('villages.unauthorized'));
            return;
        }
        $this->pendingDeleteId = $id;
        $this->dispatch('showConfirmModal');
    }

    public function deleteConfirmed()
    {
        if (!$this->pendingDeleteId) return;
        try {
            $village = Village::findOrFail($this->pendingDeleteId);
            $village->delete();
            $this->dispatch('notify', type: 'success', message: __('villages.deleted'));
        } catch (\Throwable $e) {
            $this->dispatch('notify', type: 'danger', message: __('villages.delete_failed'));
        } finally {
            $this->pendingDeleteId = null;
            $this->dispatch('hideConfirmModal');
        }
    }

    public function render()
    {
        $query = Village::query()
            ->when($this->countryFilter, function ($q) {
                $q->whereHas('city', function ($qq) {
                    $qq->where('country_id', $this->countryFilter);
                });
            })
            ->when($this->cityFilter, function ($q) {
                $q->where('city_id', $this->cityFilter);
            })
            ->when($this->search, function ($q) {
                $term = $this->search;
                $q->whereTranslationLike('name', "%{$term}%");
            })
            ->orderByDesc('id');

        $villages = $query->paginate($this->perPage);
        $countries = Country::orderBy('id', 'desc')->get();
        $cities = City::orderBy('id', 'desc')->get();

        return view('livewire.villages.village-list', [
            'villages' => $villages,
            'countries' => $countries,
            'cities' => $cities,
        ]);
    }
}

