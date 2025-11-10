<?php

namespace App\Livewire\Representatives;

use App\Models\Representative;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class RepresentativeForm extends Component
{
    public ?int $representativeId = null;
    public array $name = [];

    public bool $canCreate = false;
    public bool $canEdit = false;

    protected function rules(): array
    {
        $locales = config('app.locales', [app()->getLocale()]);
        $nameRules = [];
        foreach ($locales as $locale) {
            $nameRules["name.$locale"] = ['required', 'string', 'max:255'];
        }

        return $nameRules;
    }

    public function mount(?int $representativeId = null): void
    {
        $this->representativeId = $representativeId;
        $user = Auth::user();
        $this->canCreate = $user ? $user->can('create-representatives') : false;
        $this->canEdit = $user ? $user->can('edit-representatives') : false;

        if ($representativeId) {
            $rep = Representative::with('translations')->find($representativeId);
            if ($rep) {
                foreach (config('app.locales', [app()->getLocale()]) as $locale) {
                    $this->name[$locale] = $rep->translateOrNew($locale)->name ?? '';
                }
            }
        } else {
            foreach (config('app.locales', [app()->getLocale()]) as $locale) {
                $this->name[$locale] = '';
            }
        }
    }

    public function save(): void
    {
        $user = Auth::user();
        $isEdit = (bool) $this->representativeId;
        if ($isEdit && (! $user || ! $user->can('edit-representatives'))) {
            $this->dispatch('toast', type: 'error', message: __('representatives.unauthorized'));
            return;
        }
        if (! $isEdit && (! $user || ! $user->can('create-representatives'))) {
            $this->dispatch('toast', type: 'error', message: __('representatives.unauthorized'));
            return;
        }

        $this->validate();

        if ($this->representativeId) {
            $rep = Representative::find($this->representativeId);
            if (! $rep) {
                $rep = new Representative();
                $rep->save();
            }
        } else {
            $rep = new Representative();
            $rep->save(); // triggers code auto-generation in model's creating event
        }

        foreach ($this->name as $locale => $value) {
            $rep->translateOrNew($locale)->name = $value;
        }
        $rep->save();

        $this->representativeId = $rep->id;
        $this->dispatch('toast', type: 'success', message: $isEdit ? __('representatives.updated_success') : __('representatives.created_success'));
        $this->dispatch('representativeSaved');
    }

    public function render()
    {
        return view('livewire.representatives.representative-form');
    }
}
