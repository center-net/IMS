<?php

namespace App\Livewire\Offers;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Offer;

class OfferList extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;

    protected $listeners = ['offerSaved' => '$refresh'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function edit(int $id): void
    {
        $this->dispatch('editOffer', id: $id);
    }

    public function delete(int $id): void
    {
        $offer = Offer::find($id);
        if (!$offer) return;
        $offer->delete();
        session()->flash('message', __('offers.deleted_success'));
        $this->dispatch('offerSaved');
    }

    public function render()
    {
        $query = Offer::query()->with('translations');
        if ($this->search) {
            $term = $this->search;
            $query->where(function ($q) use ($term) {
                $q->whereHas('translations', function ($t) use ($term) {
                    $t->where('name', 'like', '%' . $term . '%');
                })
                ->orWhere('code', 'like', '%' . $term . '%');
            });
        }

        return view('livewire.offers.offer-list', [
            'offers' => $query->orderByDesc('created_at')->paginate($this->perPage),
        ]);
    }
}

