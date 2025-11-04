@extends('layouts.app')

@section('title', __('menu.users'))

@section('content')
<div class="container py-3">
    <div class="row g-3">
        <div class="col-12 col-lg-8">
            @livewire('users.user-list')
        </div>
        <div class="col-12 col-lg-4">
            @livewire('users.user-form')
        </div>
    </div>
</div>
@endsection

