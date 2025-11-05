@php($status = session('status'))
@if($status)
    <div class="alert alert-success d-flex align-items-center" role="alert">
        <i class="bi-check-circle me-2"></i>
        <div>{{ $status }}</div>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-warning" role="alert">
        <div class="d-flex align-items-center mb-2">
            <i class="bi-exclamation-triangle me-2"></i>
            <strong>{{ __('alerts.check_data') }}</strong>
        </div>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
