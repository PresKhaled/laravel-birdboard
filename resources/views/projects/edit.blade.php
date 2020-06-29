@extends('layouts.app')

@section('content')
    <div class="container m-auto">
        <h1 class="text-secondary mb-3">Edit project</h1>

        @include('projects._form', ['action' => route('updateProject', $project->id), 'method' => 'POST', 'isUpdate' => true])
    </div>
@endsection