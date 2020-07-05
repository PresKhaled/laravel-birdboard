<footer>
    <form method="POST" action="{{ $project->url() }}" class="text-right">
        @csrf
        @method('delete')

        <button type="submit" class="btn btn-danger" style="font-size: 12px; padding: 5px 10px">Delete</button>
    </form>
</footer>