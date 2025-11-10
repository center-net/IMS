<?php

namespace App\Livewire\Currencies;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Currency;
use App\Services\CurrencyRateService;

class CurrencyList extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $pendingDeleteId = null;
    public $liveRates = [];
    public $liveProvider = null;
    protected $paginationTheme = 'bootstrap';

    protected $listeners = ['currencySaved' => '$refresh'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function edit(int $id): void
    {
        $this->dispatch('editCurrency', id: $id);
    }

    public function confirmDelete(int $id): void
    {
        if (!auth()->user()?->can('delete-currencies')) {
            $this->dispatch('notify', type: 'danger', message: __('currencies.unauthorized'));
            return;
        }
        $this->pendingDeleteId = $id;
    }

    public function delete(): void
    {
        if (!$this->pendingDeleteId) return;
        $currency = Currency::find($this->pendingDeleteId);
        if ($currency) {
            $currency->delete();
            session()->flash('message', __('currencies.deleted_success'));
        }
        $this->pendingDeleteId = null;
        $this->dispatch('currencySaved');
    }

    public function render()
    {
        $query = Currency::query()->with('translations');
        if ($this->search) {
            $term = $this->search;
            $query->where(function ($q) use ($term) {
                $q->whereHas('translations', function ($t) use ($term) {
                    $t->where('name', 'like', "%{$term}%");
                })
                ->orWhere('code', 'like', "%{$term}%")
                ->orWhere('symbol', 'like', "%{$term}%");
            });
        }

        $currencies = $query->orderByDesc('id')->paginate($this->perPage);

        // Fetch live rates to USD in one request
        $codes = $currencies->pluck('code')->all();
        $service = app(CurrencyRateService::class);
        $this->liveRates = $service->getRatesToUSD($codes);
        $this->liveProvider = $service->getRatesProvider();

        return view('livewire.currencies.currency-list', [
            'currencies' => $currencies,
        ]);
    }
}
