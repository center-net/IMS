<?php

namespace App\Livewire\Cities;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\City;
use App\Models\Country;
use Livewire\Attributes\Url;

class CityList extends Component
{
    use WithPagination;

    public $search = '';
    #[Url]
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

    private function normalizeCountryFilter(): void
    {
        if ($this->countryFilter && !is_numeric($this->countryFilter)) {
            $code = strtoupper((string) $this->countryFilter);
            $country = Country::where('iso_code', $code)->first();
            if ($country) {
                $this->countryFilter = $country->id;
                return;
            }
            // Fallback by translated name (ar/en)
            $byName = Country::whereTranslation('name', $this->countryFilter)->first();
            if ($byName) {
                $this->countryFilter = $byName->id;
            }
        }
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
        $this->normalizeCountryFilter();
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
