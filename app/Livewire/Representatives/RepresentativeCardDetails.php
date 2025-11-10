<?php

namespace App\Livewire\Representatives;

use App\Models\Representative;
use Livewire\Component;

class RepresentativeCardDetails extends Component
{
    public int $representativeId;

    public function mount(int $representativeId): void
    {
        $this->representativeId = $representativeId;
    }

    public function render()
    {
        $rep = Representative::query()
            ->with(['translations', 'card.translations'])
            ->find($this->representativeId);

        return view('livewire.representatives.representative-card-details', [
            'representative' => $rep,
            'card' => $rep?->card,
        ]);
    }
}

