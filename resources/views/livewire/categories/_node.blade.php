@php($locale = app()->getLocale())
@php($name = optional($node->translate($locale))->name ?? optional($node->translate('en'))->name ?? optional($node->translate('ar'))->name ?? '')
@php($hasChildren = $node->children && $node->children->count())
@php($isHighlighted = isset($highlightId) && $highlightId === $node->id)
@php($isSelected = isset($selectedId) && $selectedId === $node->id)

<li class="list-group-item {{ $isHighlighted ? 'bg-warning-subtle border-warning' : '' }} {{ $isSelected ? 'border-primary' : '' }}" wire:key="category-node-{{ $node->id }}">
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
                @if($isHighlighted)
                    <span class="badge bg-warning text-dark me-2"><i class="bi bi-star-fill"></i></span>
                @endif
                <span class="badge bg-light text-secondary">{{ $node->code }}</span>
            </div>
        </div>
        <div class="d-flex gap-1">
            @can('edit-categories')
            <button type="button" class="btn btn-sm btn-outline-primary" wire:click.stop="edit({{ $node->id }})" data-bs-toggle="tooltip" title="{{ __('categories.edit') }}">
                <i class="bi bi-pencil-square"></i>
            </button>
            @endcan
            @can('create-categories')
            <button type="button" class="btn btn-sm btn-success" wire:click.stop="create({{ $node->id }})" data-bs-toggle="tooltip" title="{{ __('categories.create') }}">
                <i class="bi bi-plus-lg"></i>
            </button>
            @endcan
            @can('delete-categories')
            <button type="button" class="btn btn-sm btn-outline-danger" wire:click.stop="confirmDelete({{ $node->id }})" data-bs-toggle="tooltip" title="{{ __('categories.delete') }}">
                <i class="bi-trash"></i>
            </button>
            @endcan
        </div>
    </div>

    @if($hasChildren)
        <ul class="list-group ms-4 mt-2 collapse category-children {{ $isOpen ? 'show' : '' }}" id="children-{{ $node->id }}" wire:key="children-{{ $node->id }}">
            @foreach($node->children as $child)
                @include('livewire.categories._node', ['node' => $child, 'level' => $level + 1, 'openCategoryIds' => $openCategoryIds ?? [], 'highlightId' => $highlightId ?? null, 'selectedId' => $selectedId ?? null])
            @endforeach
        </ul>
    @endif
</li>
