<?php

namespace App\Livewire\Representatives;

use App\Models\Representative;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class RepresentativeList extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedRepresentativeId = null;
    public string $sortBy = 'id';
    public string $sortDir = 'desc';

    protected $queryString = [
        'search' => ['except' => ''],
        'sortBy' => ['except' => 'id'],
        'sortDir' => ['except' => 'desc'],
    ];

    protected $listeners = [
        'representativeSaved' => '$refresh',
        // Listen for child event to close the modal
        'closeRepModal' => 'closeModal',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function selectRepresentative(int $id): void
    {
        $this->selectedRepresentativeId = $id;
        $this->dispatch('openRepresentativeCardModal');
    }

    public function toggleStatus(int $id): void
    {
        if (! Auth::user() || ! Auth::user()->can('edit-representatives')) {
            $this->dispatch('toast', type: 'error', message: __('representatives.unauthorized'));
            return;
        }

        $rep = Representative::with('card')->find($id);
        if (! $rep) {
            $this->dispatch('toast', type: 'error', message: __('representatives.save_failed'));
            return;
        }

        $card = $rep->card;
        if (! $card) {
            $this->dispatch('toast', type: 'error', message: __('representatives.save_failed'));
            return;
        }

        $card->status = $card->status === 'active' ? 'suspended' : 'active';
        $card->save();

        $this->dispatch('notify', type: 'success', message: __('representatives.updated_success'));
    }

    public function sortByField(string $field): void
    {
        if ($this->sortBy === $field) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDir = 'asc';
        }
    }

    public function deleteRepresentative(int $id): void
    {
        if (! Auth::user() || ! Auth::user()->can('delete-representatives')) {
            $this->dispatch('toast', type: 'error', message: __('representatives.unauthorized'));
            return;
        }

        $rep = Representative::find($id);
        if (! $rep) {
            $this->dispatch('toast', type: 'error', message: __('representatives.save_failed'));
            return;
        }

        $rep->delete();
        $this->dispatch('toast', type: 'success', message: __('representatives.updated_success'));
        $this->reset('selectedRepresentativeId');
    }

    public function closeModal(): void
    {
        $this->reset('selectedRepresentativeId');
    }

    public function render()
    {
        $reps = Representative::query()
            ->with(['translations', 'card'])
            ->when($this->search, function ($q) {
                $q->where(function ($qq) {
                    $qq->where('code', 'like', "%{$this->search}%")
                       ->orWhereHas('translations', function ($t) {
                           $t->where('name', 'like', "%{$this->search}%");
                       })
                       ->orWhereHas('card', function ($c) {
                           $c->where('phone', 'like', "%{$this->search}%");
                       });
                });
            })
            ->when($this->sortBy === 'status', function ($q) {
                $q->orderByRaw("(SELECT status FROM representative_cards WHERE representative_cards.representative_id = representatives.id) " . ($this->sortDir === 'asc' ? 'ASC' : 'DESC'));
            })
            ->when($this->sortBy === 'phone', function ($q) {
                $q->orderByRaw("(SELECT phone FROM representative_cards WHERE representative_cards.representative_id = representatives.id) " . ($this->sortDir === 'asc' ? 'ASC' : 'DESC'));
            })
            ->when(! in_array($this->sortBy, ['status','phone']), function ($q) {
                $q->orderBy($this->sortBy, $this->sortDir);
            })
            ->paginate(10);

        return view('livewire.representatives.representative-list', [
            'representatives' => $reps,
        ]);
    }
}
