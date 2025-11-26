<div class="modal fade" id="category-form-modal" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $categoryId ? __('categories.edit_title') : __('categories.create_title') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form wire:submit.prevent="save" class="vstack gap-3">
                    <div class="row g-3">
                        @if(!$forceRoot)
                        <div class="col-12">
                            @if(!$lockParent)
                                <label class="form-label">{{ __('categories.parent') }}</label>
                                <select class="form-select" wire:model="parent_id">
                                    <option value="">{{ __('categories.no_parent') }}</option>
                                @foreach($options as $opt)
                                    <option value="{{ $opt->id }}">{{ optional($opt->translate(app()->getLocale()))->name ?? optional($opt->translate('en'))->name ?? optional($opt->translate('ar'))->name ?? '' }}</option>
                                @endforeach
                                </select>
                                @error('parent_id')<div class="text-danger small">{{ $message }}</div>@enderror
                            @endif
                        </div>
                        @endif
                        <div class="col-12">
                            @php($locale = app()->getLocale())
                            <label class="form-label">{{ $locale === 'ar' ? __('categories.name_ar') : __('categories.name_en') }}</label>
                            <input type="text" class="form-control" wire:model.debounce.400ms="name" placeholder="{{ $locale === 'ar' ? 'أدخل اسم القسم' : 'Enter category name' }}">
                            @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
                            @if($nameDuplicateDetails)
                                <div class="text-warning small mt-1">{{ $nameDuplicateDetails }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <button type="submit" class="btn btn-primary" {{ $nameDuplicateDetails ? 'disabled' : '' }}>
                            <i class="bi bi-save"></i> {{ $categoryId ? __('categories.update') : __('categories.create') }}
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="resetForm">
                            {{ __('categories.reset') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
