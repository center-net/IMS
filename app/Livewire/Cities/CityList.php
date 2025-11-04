<?php

namespace App\Livewire\Cities;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\City;
use App\Models\Country;

class CityList extends Component
{
    use WithPagination;

    public $search = '';
    public $countryFilter = null;
    public $perPage = 10;
    public $pendingDeleteId = null;
    protected $paginationTheme = 'bootstrap';

    protected $listeners = ['citySaved' => '$refresh'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCountryFilter()
    {
        $this->resetPage();
    }

    public function edit($id)
    {
        $this->dispatch('editCity', id: $id);
    }

    public function confirmDelete($id)
    {
        if (!auth()->user()?->can('delete-cities')) {
            $this->dispatch('notify', type: 'danger', message: __('cities.unauthorized'));
            return;
        }
        $this->pendingDeleteId = $id;
        $this->dispatch('showConfirmModal');
    }

    public function deleteConfirmed()
    {
        if (!$this->pendingDeleteId) return;
        try {
            $city = City::findOrFail($this->pendingDeleteId);
            $city->delete();
            $this->dispatch('notify', type: 'success', message: __('cities.deleted'));
        } catch (\Throwable $e) {
            $this->dispatch('notify', type: 'danger', message: __('cities.delete_failed'));
        } finally {
            $this->pendingDeleteId = null;
            $this->dispatch('hideConfirmModal');
        }
    }

    public function render()
    {
        $query = City::query()
            ->when($this->countryFilter, function ($q) {
                $q->where('country_id', $this->countryFilter);
            })
            ->when($this->search, function ($q) {
                $term = $this->search;
                $q->whereTranslationLike('name', "%{$term}%");
            })
            ->orderByDesc('id');

        $cities = $query->paginate($this->perPage);
        $countries = Country::orderBy('id', 'desc')->get();

        return view('livewire.cities.city-list', [
            'cities' => $cities,
            'countries' => $countries,
        ]);
    }
}

