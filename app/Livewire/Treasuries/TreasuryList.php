<?php

namespace App\Livewire\Treasuries;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Treasury;

class TreasuryList extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;

    protected $listeners = ['treasurySaved' => '$refresh'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function makeMain(int $id): void
    {
        if (!auth()->user()?->can('set-main-treasuries')) {
            $this->dispatch('notify', type: 'danger', message: __('treasuries.unauthorized'));
            return;
        }
        $tr = Treasury::find($id);
        if (!$tr) return;
        if (!$tr->is_main) {
            $tr->is_main = true;
            $tr->save();
            session()->flash('message', __('treasuries.set_main_success'));
        }
    }

    public function edit(int $id): void
    {
        if (!auth()->user()?->can('edit-treasuries')) {
            $this->dispatch('notify', type: 'danger', message: __('treasuries.unauthorized'));
            return;
        }
        $this->dispatch('editTreasury', id: $id);
    }

    public function delete(int $id): void
    {
        if (!auth()->user()?->can('delete-treasuries')) {
            $this->dispatch('notify', type: 'danger', message: __('treasuries.unauthorized'));
            return;
        }
        $tr = Treasury::find($id);
        if (!$tr) return;
        $tr->delete();
        session()->flash('message', __('treasuries.deleted_success'));
        $this->dispatch('treasurySaved');
    }

    public function toggleStatus(int $id): void
    {
        if (!auth()->user()?->can('edit-treasuries')) {
            $this->dispatch('notify', type: 'danger', message: __('treasuries.unauthorized'));
            return;
        }
        $tr = Treasury::find($id);
        if (!$tr) return;
        $tr->status = $tr->status === 'open' ? 'closed' : 'open';
        $tr->save();
        $this->dispatch('notify', type: 'success', message: __('treasuries.status_toggled'));
        $this->dispatch('treasurySaved');
    }

    public function render()
    {
        $query = Treasury::query()->with(['translations', 'manager', 'manager.translations']);
        if ($this->search) {
            $term = $this->search;
            $query->where(function ($q) use ($term) {
                $q->whereHas('translations', function ($t) use ($term) {
                    $t->where('name', 'like', '%' . $term . '%');
                })
                ->orWhere('code', 'like', '%' . $term . '%');
            });
        }

        // Build a map of main treasury id -> display name to annotate sub-treasuries
        $mainNames = Treasury::query()
            ->with('translations')
            ->where('is_main', true)
            ->get()
            ->mapWithKeys(function ($t) {
                $translated = optional($t->translate(app()->getLocale()))->name;
                return [$t->id => ($translated ?? $t->code)];
            });

        return view('livewire.treasuries.treasury-list', [
            'treasuries' => $query->orderByDesc('created_at')->paginate($this->perPage),
            'mainNames' => $mainNames,
        ]);
    }
}
