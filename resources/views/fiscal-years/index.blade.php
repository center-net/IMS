@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-7 mb-3">
            @livewire('fiscal-years.fiscal-year-list')
        </div>
        @can('create-fiscal-years')
            <div class="col-lg-5 mb-3">
                @livewire('fiscal-years.fiscal-year-form')
            </div>
        @endcan
    </div>
</div>
@endsection
