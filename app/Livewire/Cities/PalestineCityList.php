<?php

namespace App\Livewire\Cities;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\City;
use App\Models\Country;

class PalestineCityList extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $pendingDeleteId = null;
    protected $paginationTheme = 'bootstrap';

    protected $listeners = ['citySaved' => '$refresh'];

    public function updatingSearch()
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

    private function getPalestineId(): ?int
    {
        $palestine = Country::where('iso_code', 'PS')->first();
        return $palestine?->id;
    }

    public function render()
    {
        $countryId = $this->getPalestineId();

        $query = City::query()
            ->where('country_id', $countryId)
            ->when($this->search, function ($q) {
                $term = $this->search;
                $q->whereTranslationLike('name', "%{$term}%");
            })
            ->orderByDesc('id');

        $cities = $query->paginate($this->perPage);

        return view('livewire.cities.palestine-city-list', [
            'cities' => $cities,
        ]);
    }
}

