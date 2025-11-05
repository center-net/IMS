@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            @livewire('companies.company-form')
        </div>
        <div class="col-md-8">
            @livewire('companies.company-list')
        </div>
    </div>
</div>
@endsection

