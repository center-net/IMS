    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @livewireScripts
    <script>
        // Global toast notifications
        window.addEventListener('notify', function (event) {
            const detail = event.detail || {};
            const type = detail.type || 'info';
            const message = detail.message || '';
            const container = document.getElementById('alerts-container');
            if (!container || !message) return;

            const toast = document.createElement('div');
            toast.className = `toast align-items-center text-bg-${type} border-0 shadow`;
            toast.setAttribute('role', 'alert');
            toast.setAttribute('aria-live', 'assertive');
            toast.setAttribute('aria-atomic', 'true');
            toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">${message}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            `;
            container.appendChild(toast);

            const delay = typeof detail.delay === 'number'
                ? detail.delay
                : (type === 'danger' || type === 'warning' ? 4000 : (type === 'success' ? 2500 : 3000));
            const t = new bootstrap.Toast(toast, { autohide: true, delay });
            t.show();
        });
    </script>
    <script>
        // Initialize Bootstrap tooltips globally and after Livewire updates
        function initTooltips() {
            var list = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            list.forEach(function (el) {
                try { new bootstrap.Tooltip(el); } catch (e) {}
            });
        }
        document.addEventListener('DOMContentLoaded', initTooltips);
        window.addEventListener('livewire:load', function () {
            initTooltips();
            try {
                if (window.Livewire && typeof Livewire.hook === 'function') {
                    Livewire.hook('message.processed', function () { initTooltips(); });
                }
            } catch (e) {}
        });
    </script>
    {{-- Output stacked scripts from child views --}}
    @stack('scripts')
