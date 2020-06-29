@extends('layouts.app')

@section('content')
    <div class="container m-auto">
        <div class="d-flex justify-content-between mb-4" style="align-items: baseline">
            <h1 class="text-secondary">Projects</h1>
            <button class="btn btn-primary" onclick="window.location = '{{ route('createProject') }}'">New</button>
        </div>
        @forelse ($projects as $project)
            <div class="card mb-3"
                onmouseover="$(this).css('box-shadow', '0 0 0 0.2rem rgba(97, 157, 206, 0.5)')"
                onmouseout="$(this).css('box-shadow', '')" onclick="window.location = '{{ $project->url() }}'" style="cursor: pointer">

                <div class="card-header">
                    {{ $project->title }}
                </div>
                <div class="card-body">
                    <h5 class="card-title text-secondary" style="line-height: 1.5">{{ $project->description }}</h5>
                    <p class="card-text text-secondary">{{ $project->created_at->diffForHumans() }}</p>
                </div>

            </div>
        @empty
            <div class="alert alert-info" role="alert">
                No projects yet.
            </div>
        @endforelse
    </div>
@endsection