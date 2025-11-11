<?php

namespace App\Livewire\Categories;

use Livewire\Component;
use App\Models\Category;

class CategoryList extends Component
{
    public $search = '';
    public $pendingDeleteId = null;
    // قائمة الأصناف المفتوحة للحفاظ على التوسعة بعد الحفظ/إعادة العرض
    public array $openCategoryIds = [];

    protected $listeners = ['categorySaved' => '$refresh'];

    public function edit(int $id): void
    {
        $this->dispatch('editCategory', id: $id);
    }


    public function confirmDelete(int $id): void
    {
        $this->pendingDeleteId = $id;
    }

    public function delete(): void
    {
        if (!$this->pendingDeleteId) return;
        $cat = Category::find($this->pendingDeleteId);
        if ($cat) {
            $cat->delete();
            session()->flash('message', __('categories.deleted_success'));
        }
        $this->pendingDeleteId = null;
        $this->dispatch('categorySaved');
    }

    public function render()
    {
        $query = Category::query()->with(['translations', 'children.translations', 'children.children.translations']);
        if ($this->search) {
            $term = $this->search;
            $query->where(function ($q) use ($term) {
                $q->whereHas('translations', function ($t) use ($term) {
                    $t->where('name', 'like', "%{$term}%");
                })->orWhere('code', 'like', "%{$term}%");
            });
        }

        return view('livewire.categories.category-list', [
            'roots' => $query->whereNull('parent_id')->orderBy('id')->get(),
        ]);
    }
}
