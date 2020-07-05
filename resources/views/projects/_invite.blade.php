<div class="invitation card" style="flex: 1">
    <div class="card-body">
        <h4 class="card-title text-secondary">Invite</h4>
        <form action="{{ route('invitation', $project->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="invite">Email address</label>
                <input id="invite" type="email" name="email" class="form-control @error('email', 'invitation') is-invalid @enderror" onchange="this.form.submit()" placeholder="e.g. example@none.none" aria-describedby="emailHelp">
                @error('email', 'invitation')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </form>
    </div>
</div>