<?php

namespace App\Livewire\Treasuries;

use Livewire\Component;
use Illuminate\Validation\Rule;
use App\Models\Treasury;

class TreasuryForm extends Component
{
    public $name = '';
    public $is_main = false;
    public $status = 'open';
    public $treasury_id = null;
    public $editing = false;

    protected $listeners = ['editTreasury' => 'loadTreasury'];

    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'is_main' => ['boolean'],
            'status' => [$this->editing ? 'required' : 'nullable', Rule::in(['open', 'closed'])],
        ];
    }

    public function submit()
    {
        $this->validate();

        if ($this->editing) {
            // Update existing treasury
            if (!auth()->user()?->can('edit-treasuries')) {
                $this->dispatch('notify', type: 'danger', message: __('treasuries.unauthorized'));
                return;
            }
            try {
                $tr = Treasury::find($this->treasury_id);
                if (!$tr) {
                    return;
                }
                $tr->is_main = (bool) $this->is_main;
                // Update translated name and status for current locale
                $tr->translateOrNew(app()->getLocale())->name = $this->name;
                $tr->status = $this->status;
                $tr->save();
                $this->dispatch('notify', type: 'success', message: __('treasuries.updated'));
                $this->dispatch('treasurySaved');
                $this->resetForm();
            } catch (\Throwable $e) {
                $this->dispatch('notify', type: 'danger', message: __('treasuries.save_failed'));
            }
        } else {
            // Create new treasury
            if (!auth()->user()?->can('create-treasuries')) {
                $this->dispatch('notify', type: 'danger', message: __('treasuries.unauthorized'));
                return;
            }
            try {
                $tr = new Treasury();
                $tr->is_main = (bool) $this->is_main;
                // enforce default status as open on creation
                $tr->status = 'open';
                // Set translated name for current locale
                $tr->translateOrNew(app()->getLocale())->name = $this->name;
                $tr->save();

                $this->dispatch('notify', type: 'success', message: __('treasuries.created'));
                $this->dispatch('treasurySaved');
                $this->resetForm();
            } catch (\Throwable $e) {
                $this->dispatch('notify', type: 'danger', message: __('treasuries.save_failed'));
            }
        }
    }

    public function render()
    {
        $mainTreasuries = Treasury::query()
            ->with('translations')
            ->where('is_main', true)
            ->where('status', 'open')
            ->orderByDesc('created_at')
            ->get();
        return view('livewire.treasuries.treasury-form', [
            'mainTreasuries' => $mainTreasuries,
        ]);
    }

    public function loadTreasury(int $id): void
    {
        $tr = Treasury::find($id);
        if (!$tr) return;
        $this->treasury_id = $tr->id;
        $this->name = optional($tr->translate(app()->getLocale()))->name ?? '';
        $this->is_main = (bool) $tr->is_main;
        $this->status = $tr->status;
        $this->editing = true;
        $this->resetErrorBag();
        $this->resetValidation();
    }

    private function resetForm(): void
    {
        $this->reset(['name', 'is_main', 'status', 'treasury_id', 'editing']);
        $this->status = 'open';
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function cancel(): void
    {
        $this->resetForm();
        $this->dispatch('notify', type: 'info', message: __('treasuries.cancelled'));
    }
}
