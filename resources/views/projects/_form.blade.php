<form action="{{ $action }}" method="{{ $method }}">
    @csrf

    @if (isset($isUpdate))
        @method('patch')
    @endif

    <div class="form-group">
        <label for="title">Title</label>
        <input type="text" name="title" class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" id="title" value="{{ old('title') ?? $project->title ?? '' }}" placeholder="e.g. Improve Testing">
        @error('title')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="form-group">
        <label for="description">Description</label>
        <input type="text" name="description" class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" id="description" value="{{ old('description') ?? $project->description ?? '' }}" placeholder="e.g. Imagine app without Testing! :O">
        @error('description')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="form-group">
        <label for="notes">Notes</label>
        <textarea type="text" name="notes" rows="10" class="form-control {{ $errors->has('notes') ? 'is-invalid' : '' }}" id="notes" placeholder="e.g. Explore the full world of Testing, DON'T STUCK WITH ONE THING">{{ old('notes') ?? $project->notes ?? '' }}</textarea>
        @error('notes')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="d-flex justify-content-between">
        <button class="btn btn-success" type="submit">{{ isset($isUpdate) ? 'Update' : 'Create' }}</button>
        <a class="btn btn-danger" href="{{ isset($isUpdate) ? route('project', $project->id) : route('projects') }}">Cancel</a>
    </div>
</form>