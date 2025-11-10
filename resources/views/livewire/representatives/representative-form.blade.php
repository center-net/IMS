<div id="rep-form">
    <h5 class="mb-3">{{ __('representatives.title') }}</h5>

    @php $locales = config('app.locales', [app()->getLocale()]); @endphp

    @if(!$canCreate && !$canEdit)
        <div class="alert alert-warning">{{ __('representatives.unauthorized') }}</div>
    @endif

    <form wire:submit.prevent="save">
        <fieldset @if(!$canCreate && !$canEdit) disabled @endif>

            @foreach($locales as $locale)
                <div class="mb-2">
                    <label class="form-label">{{ __('representatives.name') }} ({{ strtoupper($locale) }})</label>
                    <input type="text" class="form-control" wire:model.defer="name.{{ $locale }}">
                    @error('name.'.$locale) <div class="text-danger small">{{ $message }}</div> @enderror
                </div>
            @endforeach
        </fieldset>

        <div class="mt-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary" @if(!$canCreate && !$canEdit) disabled @endif>{{ __('representatives.update') }}</button>
            <button type="button" class="btn btn-secondary" wire:click="$dispatch('representativeSaved')">{{ __('representatives.cancel') }}</button>
        </div>
    </form>
</div>
