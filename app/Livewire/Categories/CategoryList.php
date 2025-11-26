<?php

namespace App\Livewire\Categories;

use Livewire\Component;
use App\Models\Category;
use App\Models\Item;

class CategoryList extends Component
{
    public $search = '';
    public $pendingDeleteId = null;
    // قائمة الأصناف المفتوحة للحفاظ على التوسعة بعد الحفظ/إعادة العرض
    public array $openCategoryIds = [];
    public ?int $highlightId = null;
    public ?int $selectedId = null;
    public ?int $deleteTargetId = null;
    public int $deleteChildrenCount = 0;
    public int $deleteItemsCount = 0;
    public array $deleteDescendantIds = [];
    public string $deleteSearch = '';
    public ?int $bulkSourceId = null;
    public ?int $bulkTargetId = null;
    public string $bulkSearch = '';
    public string $bulkSourceSearch = '';
    public string $bulkTargetSearch = '';
    public bool $bulkConfirming = false;

    protected $listeners = ['categoryCreated' => 'handleCategoryCreated', 'categoryUpdated' => 'handleCategoryUpdated', 'categoryDeleted' => 'handleCategoryDeleted'];

    public function edit(int $id): void
    {
        $this->dispatch('editCategory', id: $id);
        $this->selectedId = $id;
    }

    public function create(int $parentId): void
    {
        $this->dispatch('createCategory', parentId: $parentId);
        $this->dispatch('openCollapse', target: 'children-' . $parentId);
    }

    public function createRoot(): void
    {
        $this->dispatch('createCategory', parentId: null);
    }

    public function handleCategoryCreated(int $id, ?int $parentId = null): void
    {
        $this->highlightId = $id;
        $this->selectedId = $id;
        if ($parentId) {
            $ids = $this->openCategoryIds;
            $ids[] = $parentId;
            $this->openCategoryIds = array_values(array_unique($ids));
            $this->dispatch('openCollapse', target: 'children-' . $parentId);
        }
    }

    public function handleCategoryUpdated(int $id, ?int $parentId = null): void
    {
        $this->highlightId = $id;
        $this->selectedId = $id;
        if ($parentId) {
            $ids = $this->openCategoryIds;
            $ids[] = $parentId;
            $this->openCategoryIds = array_values(array_unique($ids));
            $this->dispatch('openCollapse', target: 'children-' . $parentId);
        }
    }

    public function handleCategoryDeleted(int $id, ?int $parentId = null): void
    {
        if ($this->selectedId === $id) {
            $this->selectedId = null;
        }
        if ($this->highlightId === $id) {
            $this->highlightId = null;
        }
        if ($parentId) {
            $ids = $this->openCategoryIds;
            $ids[] = $parentId;
            $this->openCategoryIds = array_values(array_unique($ids));
            $this->dispatch('openCollapse', target: 'children-' . $parentId);
        }
    }

    public function confirmDelete(int $id): void
    {
        $this->pendingDeleteId = $id;
        $this->deleteTargetId = null;
        $this->deleteChildrenCount = Category::query()->where('parent_id', $id)->count();
        $this->deleteItemsCount = Item::query()->where('category_id', $id)->count();
        $this->deleteDescendantIds = $this->collectDescendants($id);
        $this->dispatch('openModal', target: 'category-delete-modal');
    }

    public function delete(): void
    {
        if (!$this->pendingDeleteId) return;
        $cat = Category::find($this->pendingDeleteId);
        if (!$cat) { $this->pendingDeleteId = null; return; }

        if ($this->deleteChildrenCount > 0 || $this->deleteItemsCount > 0) {
            return; // يتطلب نقل المحتويات أولاً
        }

        $parentId = $cat->parent_id;
        $cat->delete();
        session()->flash('message', __('categories.deleted_success'));
        $this->dispatch('categoryDeleted', id: $cat->id, parentId: $parentId);
        $this->pendingDeleteId = null;
        $this->dispatch('closeModal', target: 'category-delete-modal');
    }

    public function transferAndDelete(): void
    {
        if (!$this->pendingDeleteId) return;
        $cat = Category::find($this->pendingDeleteId);
        if (!$cat) { $this->pendingDeleteId = null; return; }

        if ($this->deleteChildrenCount === 0 && $this->deleteItemsCount === 0) {
            $this->delete();
            return;
        }

        if (!$this->deleteTargetId) {
            $this->dispatch('notify', type: 'warning', message: __('categories.select_target_required'));
            return;
        }
        if ($this->deleteTargetId === $this->pendingDeleteId) {
            $this->dispatch('notify', type: 'warning', message: __('categories.target_same_as_source'));
            return;
        }
        if (in_array($this->deleteTargetId, $this->deleteDescendantIds, true)) {
            $this->dispatch('notify', type: 'warning', message: __('categories.target_in_descendants'));
            return;
        }

        Category::query()->where('parent_id', $this->pendingDeleteId)->update(['parent_id' => $this->deleteTargetId]);
        Item::query()->where('category_id', $this->pendingDeleteId)->update(['category_id' => $this->deleteTargetId]);

        $parentId = $cat->parent_id;
        $cat->delete();
        session()->flash('message', __('categories.deleted_success'));
        $this->dispatch('categoryDeleted', id: $cat->id, parentId: $parentId);

        $this->highlightId = $this->deleteTargetId;
        $this->selectedId = $this->deleteTargetId;
        $this->openCategoryIds = array_values(array_unique(array_merge($this->openCategoryIds, [$this->deleteTargetId])));
        $this->dispatch('openCollapse', target: 'children-' . $this->deleteTargetId);

        $this->pendingDeleteId = null;
        $this->deleteTargetId = null;
        $this->dispatch('closeModal', target: 'category-delete-modal');
    }

    private function collectDescendants(int $id): array
    {
        $ids = [];
        $children = Category::query()->where('parent_id', $id)->get(['id']);
        foreach ($children as $c) {
            $ids[] = $c->id;
            $ids = array_merge($ids, $this->collectDescendants($c->id));
        }
        return array_values(array_unique($ids));
    }

    public function render()
    {
        $with = ['translations', 'children.translations', 'children.children.translations'];
        $rootsQuery = Category::query()->with($with)->whereNull('parent_id')->orderBy('id');

        if ($this->search) {
            $term = $this->search;

            $matchIds = Category::query()
                ->where(function ($q) use ($term) {
                    $q->whereHas('translations', function ($t) use ($term) {
                        $t->where('name', 'like', "%{$term}%");
                    })->orWhere('code', 'like', "%{$term}%");
                })
                ->pluck('id')
                ->all();

            $map = Category::query()->select('id', 'parent_id')->get()->keyBy('id');

            $open = [];
            $rootIds = [];
            foreach ($matchIds as $id) {
                $pid = $map[$id]->parent_id ?? null;
                $root = $id;
                while ($pid) {
                    $open[] = $pid;
                    $root = $pid;
                    $pid = ($map[$pid]->parent_id ?? null);
                }
                $rootIds[] = $root;
            }

            $this->openCategoryIds = array_values(array_unique($open));

            if (!empty($rootIds)) {
                $rootsQuery = Category::query()->with($with)->whereNull('parent_id')->whereIn('id', array_values(array_unique($rootIds)))->orderBy('id');
            } else {
                $rootsQuery = Category::query()->with($with)->whereNull('parent_id')->whereRaw('0 = 1');
            }
        }

        return view('livewire.categories.category-list', [
            'roots' => $rootsQuery->get(),
            'deleteOptions' => $this->categoryOptions(),
            'bulkDescendantIds' => $this->bulkSourceId ? $this->collectDescendants($this->bulkSourceId) : [],
        ]);
    }

    private function categoryOptions()
    {
        $locale = app()->getLocale();
        $all = Category::with('translations')->get()->sortBy(function ($c) use ($locale) {
            $name = optional($c->translate($locale))->name ?? $c->name ?? '';
            return [$c->parent_id ? 1 : 0, $name];
        })->values();
        $term = trim($this->deleteSearch);
        if ($term === '') return $all;
        $lower = mb_strtolower($term);
        return $all->filter(function ($c) use ($locale, $lower) {
            $name = optional($c->translate($locale))->name ?? $c->name ?? '';
            return mb_strpos(mb_strtolower($name), $lower) !== false;
        })->values();
    }

    private function bulkCategoryOptions()
    {
        $locale = app()->getLocale();
        $all = Category::with('translations')->get()->sortBy(function ($c) use ($locale) {
            $name = optional($c->translate($locale))->name ?? $c->name ?? '';
            return [$c->parent_id ? 1 : 0, $name];
        })->values();
        $term = trim($this->bulkSearch);
        if ($term === '') return $all;
        $lower = mb_strtolower($term);
        return $all->filter(function ($c) use ($locale, $lower) {
            $name = optional($c->translate($locale))->name ?? $c->name ?? '';
            return mb_strpos(mb_strtolower($name), $lower) !== false;
        })->values();
    }

    public function bulkSourceOptions()
    {
        $locale = app()->getLocale();
        $all = Category::with('translations')->get()->sortBy(function ($c) use ($locale) {
            $name = optional($c->translate($locale))->name ?? $c->name ?? '';
            return [$c->parent_id ? 1 : 0, $name];
        })->values();
        $term = trim($this->bulkSourceSearch);
        if ($term === '') return $all;
        $lower = mb_strtolower($term);
        return $all->filter(function ($c) use ($locale, $lower) {
            $name = optional($c->translate($locale))->name ?? $c->name ?? '';
            return mb_strpos(mb_strtolower($name), $lower) !== false;
        })->values();
    }

    public function bulkTargetOptions()
    {
        $locale = app()->getLocale();
        $all = Category::with('translations')->get()->sortBy(function ($c) use ($locale) {
            $name = optional($c->translate($locale))->name ?? $c->name ?? '';
            return [$c->parent_id ? 1 : 0, $name];
        })->values();
        $term = trim($this->bulkTargetSearch);
        if ($term === '') return $all;
        $lower = mb_strtolower($term);
        return $all->filter(function ($c) use ($locale, $lower) {
            $name = optional($c->translate($locale))->name ?? $c->name ?? '';
            return mb_strpos(mb_strtolower($name), $lower) !== false;
        })->values();
    }

    public function confirmBulk(): void
    {
        if (!auth()->user() || !auth()->user()->can('delete-categories')) {
            $this->dispatch('notify', type: 'danger', message: __('categories.permission_denied'));
            return;
        }
        if (!$this->bulkSourceId || !$this->bulkTargetId) {
            $this->dispatch('notify', type: 'warning', message: __('categories.select_source_target_required'));
            return;
        }
        if ($this->bulkSourceId === $this->bulkTargetId) {
            $this->dispatch('notify', type: 'warning', message: __('categories.source_target_cannot_equal'));
            return;
        }
        $desc = $this->collectDescendants($this->bulkSourceId);
        if (in_array($this->bulkTargetId, $desc, true)) {
            $this->dispatch('notify', type: 'warning', message: __('categories.target_in_descendants'));
            return;
        }
        $this->bulkConfirming = true;
    }

    public function openBulkModal(): void
    {
        $this->bulkSourceId = null;
        $this->bulkTargetId = null;
        $this->bulkSearch = '';
        $this->bulkSourceSearch = '';
        $this->bulkTargetSearch = '';
        $this->bulkConfirming = false;
        $this->dispatch('openModal', target: 'category-bulk-transfer-modal');
    }

    public function bulkTransferAndDelete(): void
    {
        if (!auth()->user() || !auth()->user()->can('delete-categories')) {
            $this->dispatch('notify', type: 'danger', message: __('categories.permission_denied'));
            return;
        }
        if (!$this->bulkConfirming) {
            $this->confirmBulk();
            return;
        }

        if (!$this->bulkSourceId || !$this->bulkTargetId) {
            $this->dispatch('notify', type: 'warning', message: __('categories.select_source_target_required'));
            return;
        }
        if ($this->bulkSourceId === $this->bulkTargetId) {
            $this->dispatch('notify', type: 'warning', message: __('categories.source_target_cannot_equal'));
            return;
        }

        $source = Category::find($this->bulkSourceId);
        $target = Category::find($this->bulkTargetId);
        if (!$source || !$target) {
            $this->dispatch('notify', type: 'danger', message: __('categories.invalid_selection'));
            return;
        }

        $desc = $this->collectDescendants($this->bulkSourceId);
        if (in_array($this->bulkTargetId, $desc, true)) {
            $this->dispatch('notify', type: 'warning', message: __('categories.target_in_descendants'));
            return;
        }

        $childrenCount = Category::query()->where('parent_id', $this->bulkSourceId)->count();
        $itemsCount = Item::query()->where('category_id', $this->bulkSourceId)->count();

        if ($childrenCount === 0 && $itemsCount === 0) {
            $this->dispatch('notify', type: 'info', message: __('categories.no_content_to_transfer'));
        }

        Category::query()->where('parent_id', $this->bulkSourceId)->update(['parent_id' => $this->bulkTargetId]);
        Item::query()->where('category_id', $this->bulkSourceId)->update(['category_id' => $this->bulkTargetId]);

        $parentId = $source->parent_id;
        $source->delete();

        logger()->info('categories_bulk_transfer_delete', [
            'user_id' => auth()->id(),
            'source' => $this->bulkSourceId,
            'target' => $this->bulkTargetId,
            'children_moved' => $childrenCount,
            'items_moved' => $itemsCount,
            'at' => now()->toDateTimeString(),
        ]);

        $this->highlightId = $this->bulkTargetId;
        $this->selectedId = $this->bulkTargetId;
        $this->openCategoryIds = array_values(array_unique(array_merge($this->openCategoryIds, [$this->bulkTargetId])));
        $this->dispatch('openCollapse', target: 'children-' . $this->bulkTargetId);

        $this->dispatch('notify', type: 'success', message: __('categories.bulk_transfer_success'));
        session()->flash('message', __('categories.bulk_transfer_success'));
        $this->dispatch('closeModal', target: 'category-bulk-transfer-modal');
        $this->bulkConfirming = false;
    }

    private function defaultCategoryId(): ?int
    {
        $cand = Category::query()->whereNull('parent_id')->orderBy('id')->pluck('id')->all();
        foreach ($cand as $id) {
            if ($this->pendingDeleteId && $id === $this->pendingDeleteId) continue;
            if (in_array($id, $this->deleteDescendantIds, true)) continue;
            return $id;
        }
        $first = Category::query()->orderBy('id')->value('id');
        if ($first && $first !== $this->pendingDeleteId && !in_array($first, $this->deleteDescendantIds, true)) return $first;
        return null;
    }

    public function deleteCascadeTransferToDefault(): void
    {
        if (!$this->pendingDeleteId) return;
        $cat = Category::find($this->pendingDeleteId);
        if (!$cat) { $this->pendingDeleteId = null; return; }

        $defaultId = $this->defaultCategoryId();
        if (!$defaultId) return;

        $ids = array_merge([$cat->id], $this->deleteDescendantIds);

        Item::query()->whereIn('category_id', $ids)->update(['category_id' => $defaultId]);

        Category::query()->whereIn('id', $ids)->orderByDesc('id')->delete();

        logger()->info('categories_delete_cascade_transfer_default', [
            'user_id' => auth()->id(),
            'deleted_ids' => $ids,
            'default_target' => $defaultId,
            'at' => now()->toDateTimeString(),
        ]);

        $this->highlightId = $defaultId;
        $this->selectedId = $defaultId;
        $this->openCategoryIds = array_values(array_unique(array_merge($this->openCategoryIds, [$defaultId])));
        $this->dispatch('openCollapse', target: 'children-' . $defaultId);

        $this->pendingDeleteId = null;
        $this->deleteTargetId = null;
        $this->dispatch('closeModal', target: 'category-delete-modal');
    }

    public function deleteCascadePurgeAll(): void
    {
        if (!$this->pendingDeleteId) return;
        $cat = Category::find($this->pendingDeleteId);
        if (!$cat) { $this->pendingDeleteId = null; return; }

        $ids = array_merge([$cat->id], $this->deleteDescendantIds);

        $items = Item::query()->whereIn('category_id', $ids)->pluck('id')->all();
        Item::query()->whereIn('id', $items)->delete();

        Category::query()->whereIn('id', $ids)->orderByDesc('id')->delete();

        logger()->warning('categories_delete_cascade_purge_all', [
            'user_id' => auth()->id(),
            'deleted_category_ids' => $ids,
            'deleted_item_ids' => $items,
            'at' => now()->toDateTimeString(),
        ]);

        $this->pendingDeleteId = null;
        $this->deleteTargetId = null;
        $this->highlightId = null;
        $this->selectedId = null;
        $this->dispatch('closeModal', target: 'category-delete-modal');
    }
}
