@extends('layouts.app')

@section('content')
    <div class="container m-auto">

        <header>
            {{-- Quick Navigation --}}
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb" style="align-items: baseline">
                    <li class="breadcrumb-item" style="align-self: center"><a href="{{ route('projects') }}">Projects</a></li>
                    <li class="breadcrumb-item active" style="align-items: baseline" aria-current="page">
                        <div class="dropdown">
                            <button class="btn btn-info dropdown-toggle" style="padding: 2px 8px; color: #fff" type="button" id="quick-navigation" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ Str::limit($project->title, $maxChar = 25) }}
                            </button>
                            <div class="dropdown-menu" aria-labelledby="quick-navigation">
                                @foreach ($project->owner->projects->take(5) as $item)
                                    @if ($item->id == $project->id)
                                        @continue
                                    @endif
                                    <a class="dropdown-item" href="{{ $item->url() }}">{{ Str::limit($item->title, $maxChar) }}</a>
                                @endforeach
                                <a class="dropdown-item text-info" href="{{ route('createProject') }}">+ New</a>
                            </div>
                        </div>
                    </li>
                </ol>
            </nav>

            {{-- Heading --}}
            <div class="d-flex justify-content-between my-4" style="align-items: center">
                {{-- Project title --}}
                <h1 class="text-secondary m-0 pr-5">{{ $project->title }}</h1>

                {{-- Project users --}}
                <div class="d-flex items-center text-center" style="align-items: center; font-size: 12px">
                    <div title="{{ $project->owner->name }}">
                        <img
                        src="{{ gravatar_url($project->owner->email) }}"
                        alt="{{ $project->owner->name }}'s avatar"
                        class="user_gravatar mb-1">
                    
                        <span class="d-block text-info">{{ Str::limit($project->owner->name, $nameLimit = 15) }}</span>
                    </div>

                    @foreach ($project->members as $member)
                        <div class="ml-3" title="{{ $member->name }}">
                            <img
                            src="{{ gravatar_url($member->email) }}"
                            alt="{{ $member->name }}'s avatar"
                            class="user_gravatar mb-1">

                            <span class="d-block text-secondary">{{ Str::limit($member->name, $nameLimit) }}</span>
                        </div>
                    @endforeach

                    {{-- Project edit --}}
                    <button class="btn btn-primary ml-5" onclick="window.location = '{{ route('editProject', $project->id) }}'">Edit</button>
                </div>
            </div>
        </header>

        <div class="d-flex mb-5">
            {{-- Project Details --}}
            <div class="title card" style="flex: 2">
                <div class="card-body">
                    <h5 class="card-title text-secondary" style="line-height: 1.5">{{ $project->description }}</h5>
                    <p class="card-text text-secondary">{{ $project->created_at->diffForHumans() }}</p>
                </div>
            </div>

            {{-- Project Invitation --}}
            @can('manage', $project)
                @include('projects._invite', ['errorsBag' => 'invitaion'])
            @endcan
        </div>

        {{-- Tasks --}}
        <div class="mb-5">
            <h4 class="text-secondary">Tasks</h4>

            {{-- Current Project Tasks --}}
            <div>
                @foreach ($project->tasks as $task)
                <form action="{{ route('updateTask', [$project->id, $task->id]) }}" method="POST">
                    @csrf
                    @method('patch')
                    <div class="d-flex justify-content-between">
                        <div class="form-group w-100">
                        <!-- TODO: Fix validation and old value issue -->
                        <input type="text" name="body" value="{{ $task->body }}" class="form-control" style="{{ $task->completed ? 'text-decoration: line-through' : '' }}" onchange="this.form.submit()" placeholder="Add a new Task...">
                            {{--@error('body')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror--}}
                        </div>
                        <div class="form-check pt-1 ml-3">
                            <input name="completed" class="form-check-input position-static" style="transform: scale(1.6)" onchange="this.form.submit()" type="checkbox" {{ $task->completed ? "checked='checked'" : '' }}>
                        </div>
                    </div>
                </form>
                @endforeach
                

                {{-- Add a new Task --}}
                <form id="add-task" class="w-100" action="{{ route('saveTask', $project->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <!-- TODO: Fix validation and old value issue -->
                        <input type="text" name="body" class="form-control" onchange="$('#add-task').submit()" placeholder="Add a new Task...">
                        {{--@error('body')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror--}}
                    </div>
                </form>

            </div>
        </div>

        {{-- Notes --}}
        <div class="mb-5">
            <h4 class="text-secondary">Notes</h4>
            <form id="update-project" action="{{ route('updateProject', $project->id) }}" method="POST">
                @csrf
                @method('patch')
                <div class="form-group">
                    <textarea type="text" name="notes" rows="10" onblur="$('#update-project').submit()" class="form-control {{ $errors->has('notes') ? 'is-invalid' : '' }}" placeholder="Project notes">{{ old('notes') ?? $project->notes }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </form>
        </div>

        {{-- Project Activities --}}
        <div class="mb-5" style="line-height: 2.2">
            <h4 class="text-secondary">Project Activities</h4>
            @include('projects._activities')
        </div>

        {{-- Delete the Project --}}
        @can ('manage', $project)
            @include('projects._delete')
        @endcan
    </div>
@endsection