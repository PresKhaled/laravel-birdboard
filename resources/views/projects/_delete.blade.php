<footer>
    <form method="POST" action="{{ $project->url() }}" class="text-right">
        @csrf
        @method('delete')

        <button type="submit" class="btn btn-danger">Delete</button>
    </form>
</footer>