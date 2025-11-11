<div>
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title mb-3">{{ $categoryId ? __('categories.edit_title') : __('categories.create_title') }}</h5>

            <form wire:submit.prevent="save" class="vstack gap-3">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">{{ __('categories.parent') }}</label>
                        <select class="form-select" wire:model="parent_id">
                            <option value="">{{ __('categories.no_parent') }}</option>
                            @foreach($options as $opt)
                                <option value="{{ $opt->id }}">{{ optional($opt->translate(app()->getLocale()))->name ?? $opt->name }}</option>
                            @endforeach
                        </select>
                        @error('parent_id')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        @php($locale = app()->getLocale())
                        <label class="form-label">{{ $locale === 'ar' ? __('categories.name_ar') : __('categories.name_en') }}</label>
                        <input type="text" class="form-control" wire:model.defer="name">
                        @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="d-flex gap-2 justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> {{ $categoryId ? __('categories.update') : __('categories.create') }}
                    </button>
                    <button type="button" class="btn btn-secondary" wire:click="resetForm">
                        {{ __('categories.reset') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
