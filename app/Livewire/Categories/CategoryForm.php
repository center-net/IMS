<?php

namespace App\Livewire\Categories;

use Livewire\Component;
use App\Models\Category;

class CategoryForm extends Component
{
    public ?int $categoryId = null;
    public ?int $parent_id = null;
    public string $name = '';

    protected $listeners = ['editCategory' => 'load'];

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
    }

    public function resetForm(): void
    {
        $this->categoryId = null;
        $this->parent_id = null;
        $this->name = '';
    }

    public function save(): void
    {
        $this->validate();

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
        $this->dispatch('categorySaved');
        $this->dispatch('notify', type: 'success', message: $this->categoryId ? __('categories.updated_success') : __('categories.created_success'));
        session()->flash('message', $this->categoryId ? __('categories.updated_success') : __('categories.created_success'));
        $this->resetForm();
    }

    public function getCategoryOptionsProperty()
    {
        return Category::with('translations')->orderBy('id')->get();
    }

    public function render()
    {
        return view('livewire.categories.category-form', [
            'options' => $this->categoryOptions,
        ]);
    }
}
