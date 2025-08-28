<div class="card mb-4">
    <div class="card-header">Tambah Season</div>
    <div class="card-body">
        <form action="/admin/data/season" method="POST">
            @csrf
            <div class="mb-3">
                <label for="seasonName" class="form-label">Season Name</label>
                <input type="text" name="season_name" id="seasonName" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Tambah</button>
        </form>
    </div>
</div>
