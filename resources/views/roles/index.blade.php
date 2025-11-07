@extends('layouts.app')

@section('title', __('menu.roles'))

@section('content')
<div class="container py-3">
    <div class="row g-3">
        <div class="col-12 col-lg-8">
            @livewire('roles.role-list')
        </div>
        @canany(['create-roles','edit-roles'])
            <div class="col-12 col-lg-4">
                @livewire('roles.role-form')
            </div>
        @endcanany
    </div>
</div>
@endsection
