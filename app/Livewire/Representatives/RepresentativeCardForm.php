<?php

namespace App\Livewire\Representatives;

use Livewire\Component;
use App\Models\Representative;
use App\Models\RepresentativeCard;

class RepresentativeCardForm extends Component
{
    public int $representativeId;
    public bool $canEdit = true;

    // Representative core fields
    public string $rep_name = '';
    public string $rep_code = '';

    // Card fields
    public string $name = '';
    public ?string $code = null;
    public string $role = '';
    public string $branch = '';
    public string $status = 'active';

    public string $phone = '';
    public string $email = '';

    public $commission_rate = null; // numeric|string
    public string $commission_method = 'gross_sales';
    public $commission_min = null; // numeric|string
    public $commission_max = null; // numeric|string

    public string $notes = '';

    // Meta
    public ?int $attachmentsCount = null;
    public ?string $created_at = null;
    public ?string $updated_at = null;
    public ?string $created_by_name = null;

    protected function rules(): array
    {
        return [
            'rep_name' => ['nullable','string','max:255'],
            'rep_code' => ['nullable','string','max:50'],

            'name' => ['nullable','string','max:255'],
            'code' => ['nullable','string','max:50'],
            'role' => ['nullable','string','max:100'],
            'branch' => ['nullable','string','max:100'],
            'status' => ['required','in:active,suspended'],

            'phone' => ['nullable','string','max:50'],
            'email' => ['nullable','email','max:255'],

            'commission_rate' => ['nullable','numeric','min:0'],
            'commission_method' => ['required','in:gross_sales,profit,after_collection'],
            'commission_min' => ['nullable','numeric','min:0'],
            'commission_max' => ['nullable','numeric','min:0'],

            'notes' => ['nullable','string','max:2000'],
        ];
    }

    public function mount(int $representativeId): void
    {
        $this->representativeId = $representativeId;

        $rep = Representative::with(['translations', 'card', 'card.translations', 'card.creator'])->find($representativeId);
        if (!$rep) {
            return;
        }

        $locale = app()->getLocale();

        $this->rep_name = optional($rep->translate($locale))->name ?? '';
        $this->rep_code = (string)($rep->code ?? '');

        $card = $rep->card;
        if ($card) {
            $this->name = optional($card->translate($locale))->name ?? '';
            $this->code = $card->code;
            $this->role = (string)($card->role ?? '');
            $this->branch = (string)($card->branch ?? '');
            $this->status = $card->status ?? 'active';

            $this->phone = (string)($card->phone ?? '');
            $this->email = (string)($card->email ?? '');

            $this->commission_rate = $card->commission_rate;
            $this->commission_method = $card->commission_method ?? 'gross_sales';
            $this->commission_min = $card->commission_min;
            $this->commission_max = $card->commission_max;

            $this->notes = optional($card->translate($locale))->notes ?? '';

            $attachments = json_decode($card->attachments ?? '[]', true);
            $this->attachmentsCount = is_array($attachments) ? count($attachments) : 0;
            $this->created_at = optional($card->created_at)->format('Y-m-d H:i');
            $this->updated_at = optional($card->updated_at)->format('Y-m-d H:i');
            $this->created_by_name = optional($card->creator)->name;
        }

        $this->canEdit = auth()->user()?->can('edit-representatives') ?? false;
    }

    public function save(): void
    {
        if (!auth()->user()?->can('edit-representatives')) {
            $this->dispatch('notify', type: 'danger', message: __('representatives.unauthorized'));
            return;
        }

        $data = $this->validate();

        $rep = Representative::findOrFail($this->representativeId);

        // Update Representative base fields (name translation, code)
        $locale = app()->getLocale();
        $rep->translateOrNew($locale)->name = $data['rep_name'] ?? '';
        if (!empty($data['rep_code'])) {
            $rep->code = $data['rep_code'];
        }
        $rep->save();

        // Update or create Representative Card
        $card = $rep->card ?: new RepresentativeCard(['representative_id' => $rep->id]);
        $card->code = $data['code'] ?? $card->code;
        $card->role = $data['role'] ?? null;
        $card->branch = $data['branch'] ?? null;
        $card->status = $data['status'] ?? 'active';
        $card->phone = $data['phone'] ?? null;
        $card->email = $data['email'] ?? null;
        $card->commission_rate = $data['commission_rate'] ?? null;
        $card->commission_method = $data['commission_method'];
        $card->commission_min = $data['commission_min'] ?? null;
        $card->commission_max = $data['commission_max'] ?? null;
        $card->save();

        $card->translateOrNew($locale)->name = $data['name'] ?? '';
        $card->translateOrNew($locale)->notes = $data['notes'] ?? '';
        $card->save();

        $this->dispatch('notify', type: 'success', message: __('representatives.updated_success'));
        // Close modal after successful update
        $this->dispatch('closeRepModal');
    }

    public function cancel(): void
    {
        $this->dispatch('closeRepModal');
    }

    public function render()
    {
        return view('livewire.representatives.representative-card-form');
    }
}
