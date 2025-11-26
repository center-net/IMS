<?php

namespace App\Livewire\Categories;

use Livewire\Component;
use App\Models\Category;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class CategoryForm extends Component
{
    public ?int $categoryId = null;
    public ?int $parent_id = null;
    public string $name = '';
    public bool $forceRoot = false;
    public bool $lockParent = false;
    public ?string $nameDuplicateDetails = null;

    protected $listeners = ['editCategory' => 'load', 'createCategory' => 'createUnderParent'];

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'parent_id' => ['nullable', 'integer', 'exists:categories,id'],
        ];
    }

    public function load(int $id): void
    {
        $cat = Category::with('translations')->findOrFail($id);
        $this->categoryId = $cat->id;
        $this->parent_id = $cat->parent_id;
        $this->name = optional($cat->translate(app()->getLocale()))?->name ?? '';
        $this->forceRoot = false;
        $this->lockParent = false;
        $this->nameDuplicateDetails = null;
        $this->dispatch('openModal', target: 'category-form-modal');
    }

    public function resetForm(): void
    {
        $this->categoryId = null;
        $this->parent_id = null;
        $this->name = '';
        $this->forceRoot = false;
        $this->lockParent = false;
        $this->nameDuplicateDetails = null;
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function createUnderParent(?int $parentId): void
    {
        $this->categoryId = null;
        $this->parent_id = $parentId;
        $this->name = '';
        $this->forceRoot = $parentId === null;
        $this->lockParent = $parentId !== null;
        $this->nameDuplicateDetails = null;
        $this->dispatch('openModal', target: 'category-form-modal');
    }

    public function updatedName(): void
    {
        $this->nameDuplicateDetails = null;
        $name = trim($this->name);
        if ($name === '') return;
        $locale = app()->getLocale();
        $dupes = Category::query()
            ->whereHas('translations', function ($t) use ($locale, $name) {
                $t->where('locale', $locale)->where('name', $name);
            })
            ->when($this->categoryId, function ($q) {
                $q->where('id', '!=', $this->categoryId);
            })
            ->get(['id', 'parent_id', 'code']);

        if ($dupes->count() > 0) {
            $types = [];
            foreach ($dupes as $d) { $types[] = $d->parent_id ? 'فرعي' : 'رئيسي'; }
            $list = $dupes->pluck('code')->implode(', ');
            $suggestions = [
                $name . ' ' . Str::random(3),
                $name . ' ' . date('His'),
            ];
            $this->nameDuplicateDetails = 'اسم مستخدم (' . implode('/', array_unique($types)) . '): ' . $list . ' | اقتراحات: ' . implode(', ', $suggestions);
        }
    }

    public function save(): void
    {
        $this->validate();

        if ($this->forceRoot) {
            $this->parent_id = null;
        }

        $this->ensureUniqueHierarchyName();

        $cat = $this->categoryId ? Category::findOrFail($this->categoryId) : new Category();
        $cat->parent_id = $this->parent_id;
        $cat->save();

        // Translation for current locale only
        $locale = app()->getLocale();
        $cat->translateOrNew($locale)->name = $this->name;
        $cat->save();

        // Open parent collapse if created under a parent
        if (!$this->categoryId && $this->parent_id) {
            $this->dispatch('openCollapse', target: 'children-' . $this->parent_id);
        }
        if ($this->categoryId) {
            $this->dispatch('categoryUpdated', id: $cat->id, parentId: $this->parent_id);
        } else {
            $this->dispatch('categoryCreated', id: $cat->id, parentId: $this->parent_id);
        }
        $this->dispatch('notify', type: 'success', message: $this->categoryId ? __('categories.updated_success') : __('categories.created_success'));
        session()->flash('message', $this->categoryId ? __('categories.updated_success') : __('categories.created_success'));
        $this->dispatch('closeModal', target: 'category-form-modal');
        $this->resetForm();
    }

    private function ensureUniqueHierarchyName(): void
    {
        $locale = app()->getLocale();
        $name = trim($this->name);

        $globalExists = Category::query()
            ->whereHas('translations', function ($t) use ($locale, $name) {
                $t->where('locale', $locale)->where('name', $name);
            })
            ->when($this->categoryId, function ($q) {
                $q->where('id', '!=', $this->categoryId);
            })
            ->exists();

        if ($globalExists) {
            logger()->warning('duplicate_category_name_global', ['user_id' => auth()->id(), 'name' => $name, 'locale' => $locale]);
            throw ValidationException::withMessages([
                'name' => 'الاسم مستخدم مسبقاً على مستوى جميع الأقسام',
            ]);
        }

        // Unique among siblings (same parent_id) for current locale
        $siblings = Category::query()
            ->where('parent_id', $this->parent_id)
            ->when($this->categoryId, function ($q) {
                $q->where('id', '!=', $this->categoryId);
            })
            ->whereHas('translations', function ($t) use ($locale, $name) {
                $t->where('locale', $locale)->where('name', $name);
            })
            ->exists();

        if ($siblings) {
            logger()->warning('duplicate_category_name_siblings', ['user_id' => auth()->id(), 'name' => $name, 'locale' => $locale, 'parent_id' => $this->parent_id]);
            throw ValidationException::withMessages([
                'name' => 'اسم القسم مستخدم بالفعل ضمن نفس المستوى',
            ]);
        }

        // Name must not equal any ancestor name (immediate parent or higher) for current locale
        $pid = $this->parent_id;
        while ($pid) {
            $parent = Category::with('translations')->find($pid);
            if (!$parent) break;
            $parentName = optional($parent->translate($locale))->name;
            if ($parentName && $parentName === $name) {
                logger()->warning('duplicate_category_name_ancestor', ['user_id' => auth()->id(), 'name' => $name, 'locale' => $locale, 'ancestor_id' => $parent->id]);
                throw ValidationException::withMessages([
                    'name' => 'اسم القسم لا يمكن أن يكون مطابقاً لاسم الأب أو أي سلف',
                ]);
            }
            $pid = $parent->parent_id;
        }
    }

    public function getCategoryOptionsProperty()
    {
        $locale = app()->getLocale();
        return Category::with('translations')->get()->sortBy(function ($c) use ($locale) {
            $name = optional($c->translate($locale))->name ?? $c->name ?? '';
            return [$c->parent_id ? 1 : 0, $name];
        })->values();
    }

    public function render()
    {
        return view('livewire.categories.category-form', [
            'options' => $this->categoryOptions,
        ]);
    }
}
