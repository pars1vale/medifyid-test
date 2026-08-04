<form method="POST" action="{{ $method == 'new' ? url('kategori/form/new') : url('kategori/form/edit/'.$item->id) }}">
  @csrf

  <div class="form-group">
    <label>Kode Kategori</label>
    <input type="text" class="form-control" name="kode" required value="{{ old('kode', $item->kode ?? '') }}">
    <small class="text-muted">Kode harus unik, tidak boleh sama dengan kategori lain.</small>
  </div>

  <div class="form-group">
    <label>Nama Kategori</label>
    <input type="text" class="form-control" name="nama" required value="{{ old('nama', $item->nama ?? '') }}">
  </div>

  <button class="btn btn-primary mt-3">Submit</button>
</form>
