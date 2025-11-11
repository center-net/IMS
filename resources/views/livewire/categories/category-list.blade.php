<div>
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-0">{{ __('categories.title') }}</h5>
                <div class="d-flex align-items-center gap-2">
                    <input type="text" class="form-control form-control-sm" placeholder="{{ __('categories.search') }}" wire:model.live="search" style="max-width: 220px;">
                </div>
            </div>

            <ul class="list-group">
                @forelse($roots as $root)
                    @include('livewire.categories._node', ['node' => $root, 'level' => 0, 'openCategoryIds' => $openCategoryIds])
                @empty
                    <li class="list-group-item text-center text-muted">{{ __('categories.empty') }}</li>
                @endforelse
            </ul>

            @if($pendingDeleteId)
                <div class="alert alert-warning mt-3 d-flex justify-content-between align-items-center">
                    <div>{{ __('categories.delete_confirm') }}</div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-danger" wire:click="delete"><i class="bi-trash"></i> {{ __('categories.delete') }}</button>
                        <button class="btn btn-sm btn-secondary" wire:click="$set('pendingDeleteId', null)">{{ __('categories.cancel') }}</button>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- تم حذف مودال إضافة المادة والمكوّن المرتبط به بناءً على طلبك --}}

    @push('scripts')
    <script>
        // Listen for event to open a collapse list by id
        window.addEventListener('openCollapse', function (event) {
            var detail = event.detail || {};
            var targetId = detail.target;
            if (!targetId) return;
            var el = document.getElementById(targetId);
            if (!el) return;
            try {
                var c = bootstrap.Collapse.getInstance(el) || new bootstrap.Collapse(el, { toggle: false });
                c.show();
            } catch (e) {
                // Fallback: add show class
                el.classList.add('show');
            }
            // Also update the toggle button aria-expanded if present
            var btn = document.querySelector('[data-bs-target="#' + targetId + '"]');
            if (btn) {
                btn.setAttribute('aria-expanded', 'true');
            }
        });
    </script>
    @endpush
</div>
