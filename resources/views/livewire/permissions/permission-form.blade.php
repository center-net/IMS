<div class="card shadow-sm">
    <div class="card-body">
        <h5 class="card-title mb-3">
            {{ $permissionId ? __('permissions.edit_title') : __('permissions.create_title') }}
        </h5>

        {{-- المعرف (slug) يُنشأ تلقائياً من الاسم الظاهر ويتم إخفاؤه من النموذج --}}

        <div class="mb-3">
            <label class="form-label">{{ __('permissions.display_name') }}</label>
            <input type="text" class="form-control @error('display_name') is-invalid @enderror" wire:model.defer="display_name">
            @error('display_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="d-flex gap-2">
            <button class="btn btn-primary" wire:click="save"><i class="bi-check"></i> {{ __('permissions.save') }}</button>
            <button class="btn btn-secondary" wire:click="cancel"><i class="bi-arrow-counterclockwise"></i> {{ __('permissions.cancel') }}</button>
        </div>
    </div>
</div>
