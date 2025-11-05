<?php

namespace App\Livewire\Villages;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Village;
use App\Models\Country;
use App\Models\City;

class PalestineVillageList extends Component
{
    use WithPagination;

    public $search = '';
    public $citySearch = '';
    public $cityFilter = null;
    public $perPage = 10;
    public $pendingDeleteId = null;
    protected $paginationTheme = 'bootstrap';

    protected $listeners = ['villageSaved' => '$refresh'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCityFilter()
    {
        $this->resetPage();
    }

    private function getPalestineId(): ?int
    {
        $palestine = Country::where('iso_code', 'PS')->first();
        return $palestine?->id;
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

    public function render()
    {
        $palestineId = $this->getPalestineId();

        $query = Village::query()
            ->whereHas('city', function ($q) use ($palestineId) {
                $q->where('country_id', $palestineId);
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
        $cities = City::query()
            ->where('country_id', $palestineId)
            ->when($this->citySearch, function ($q) {
                $term = $this->citySearch;
                $q->whereTranslationLike('name', "%{$term}%");
            })
            ->orderBy('id', 'desc')
            ->get();

        return view('livewire.villages.palestine-village-list', [
            'villages' => $villages,
            'cities' => $cities,
        ]);
    }
}
