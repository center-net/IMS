@extends('layouts.app')

@section('title', __('menu.logs'))

@section('content')
<div class="container py-3">
    <div class="row g-3">
        <div class="col-12">
            @livewire('logs.system-log-list')
        </div>
    </div>
</div>
@endsection

