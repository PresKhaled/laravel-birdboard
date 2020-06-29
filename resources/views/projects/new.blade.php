@extends('layouts.app')

@section('content')
    <div class="container m-auto">
        <h1 class="text-secondary mb-3">New project</h1>

        @include('projects._form', ['action' => route('saveProject'), 'method' => 'POST'])
    </div>
@endsection