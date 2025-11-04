<?php

namespace App\Livewire\Countries;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Country;

class CountryList extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $pendingDeleteId = null;
    protected $paginationTheme = 'bootstrap';

    protected $listeners = ['countrySaved' => '$refresh'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function edit($id)
    {
        if (!auth()->user()?->can('edit-countries')) {
            $this->dispatch('notify', type: 'danger', message: __('countries.unauthorized'));
            return;
        }
        $this->dispatch('editCountry', $id);
    }

    public function confirmDelete($id)
    {
        if (!auth()->user()?->can('delete-countries')) {
            $this->dispatch('notify', type: 'danger', message: __('countries.unauthorized'));
            $this->dispatch('hideConfirmModal');
            return;
        }
        $this->pendingDeleteId = $id;
        $this->dispatch('showConfirmModal');
    }

    public function deleteConfirmed()
    {
        if ($this->pendingDeleteId) {
            $this->delete($this->pendingDeleteId);
            $this->pendingDeleteId = null;
        }
    }

    public function delete($id)
    {
        if (!auth()->user()?->can('delete-countries')) {
            $this->dispatch('notify', type: 'danger', message: __('countries.unauthorized'));
            $this->dispatch('hideConfirmModal');
            return;
        }
        try {
            $country = Country::find($id);
            if ($country) {
                $country->delete();
                $this->dispatch('notify', type: 'success', message: __('countries.deleted'));
                $this->dispatch('hideConfirmModal');
                $this->resetPage();
            } else {
                $this->dispatch('notify', type: 'warning', message: __('countries.not_found'));
            }
        } catch (\Throwable $e) {
            $this->dispatch('notify', type: 'danger', message: __('countries.delete_failed'));
        }
    }

    public function render()
    {
        $query = Country::query()
            ->when($this->search, function ($q) {
                $term = $this->search;
                $q->where(function ($qq) use ($term) {
                    $qq->whereTranslationLike('name', "%{$term}%")
                       ->orWhere('iso_code', 'like', "%{$term}%")
                       ->orWhere('national_number', 'like', "%{$term}%");
                });
            })
            ->orderByDesc('id');

        $countries = $query->paginate($this->perPage);

        return view('livewire.countries.country-list', [
            'countries' => $countries,
        ]);
    }
}

