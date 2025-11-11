@php($locale = app()->getLocale())
@php($name = optional($node->translate($locale))->name ?? $node->name)
@php($hasChildren = $node->children && $node->children->count())

<li class="list-group-item" wire:key="category-node-{{ $node->id }}">
    @php($isOpen = in_array($node->id, $openCategoryIds ?? []))
    <div class="d-flex justify-content-between align-items-center category-node category-level-{{ min($level, 6) }}">
        <div class="d-flex align-items-center">
            @if($hasChildren)
                <button class="btn btn-sm btn-link px-0 me-2 category-toggle" type="button"
                        data-bs-toggle="collapse" data-bs-target="#children-{{ $node->id }}"
                        aria-expanded="{{ $isOpen ? 'true' : 'false' }}" aria-controls="children-{{ $node->id }}">
                    <i class="bi bi-chevron-down icon-open"></i>
                    <i class="bi bi-chevron-right icon-closed"></i>
                </button>
            @else
                <span class="text-muted me-2">•</span>
            @endif
            <div class="d-inline-flex align-items-center">
                <strong class="me-2">{{ $name }}</strong>
                <span class="badge bg-light text-secondary">{{ $node->code }}</span>
            </div>
        </div>
        <div class="d-flex gap-1">
            @can('edit-categories')
            <button class="btn btn-sm btn-outline-primary" wire:click="edit({{ $node->id }})" data-bs-toggle="tooltip" title="{{ __('categories.edit') }}">
                <i class="bi bi-pencil-square"></i>
            </button>
            @endcan
            @can('delete-categories')
            <button class="btn btn-sm btn-outline-danger" wire:click="confirmDelete({{ $node->id }})" data-bs-toggle="tooltip" title="{{ __('categories.delete') }}">
                <i class="bi-trash"></i>
            </button>
            @endcan
        </div>
    </div>

    @if($hasChildren)
        <ul class="list-group ms-4 mt-2 collapse category-children {{ $isOpen ? 'show' : '' }}" id="children-{{ $node->id }}">
            @foreach($node->children as $child)
                @include('livewire.categories._node', ['node' => $child, 'level' => $level + 1, 'openCategoryIds' => $openCategoryIds ?? []])
            @endforeach
        </ul>
    @endif
</li>
