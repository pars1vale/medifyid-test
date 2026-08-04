<form method="POST" enctype="multipart/form-data"
  action="{{ $method == 'new' ? url('master-items/form/new') : url('master-items/form/edit/' . $item->id) }}">
  @csrf
  @if ($method == 'edit')
    <div class="form-group">
      <label>Kode Barang</label>
      <input type="text" class="form-control" name="kode_barang" required readonly value="{{ $item->kode ?? '' }}">
    </div>
  @endif

  <div class="form-group">
    <label>Nama</label>
    <input type="text" class="form-control" name="nama" required value="{{ $item->nama ?? '' }}">
  </div>

  <div class="form-group">
    <label>Harga Beli</label>
    <input type="number" class="form-control" name="harga_beli" required value="{{ $item->harga_beli ?? '' }}">
  </div>

  <div class="form-group">
    <label>Laba (dalam persen)</label>
    <input type="number" class="form-control" name="laba" required value="{{ $item->laba ?? '' }}">
  </div>

  @php $selected = $item->supplier ?? ''; @endphp
  <div class="form-group">
    <label>Supplier</label>
    <select class="form-control" required name="supplier">
      <option @if ($selected == '') selected @endif value="">--Pilih--</option>
      <option @if ($selected == 'Tokopaedi') selected @endif>Tokopaedi</option>
      <option @if ($selected == 'Bukulapuk') selected @endif>Bukulapuk</option>
      <option @if ($selected == 'TokoBagas') selected @endif>TokoBagas</option>
      <option @if ($selected == 'E Commurz') selected @endif>E Commurz</option>
      <option @if ($selected == 'Blublu') selected @endif>Blublu</option>
    </select>
  </div>

  @php $selected = $item->jenis ?? ''; @endphp
  <div class="form-group">
    <label>Jenis</label>
    <select class="form-control" required name="jenis">
      <option @if ($selected == '') selected @endif value="">--Pilih--</option>
      <option @if ($selected == 'Obat') selected @endif>Obat</option>
      <option @if ($selected == 'Alkes') selected @endif>Alkes</option>
      <option @if ($selected == 'Matkes') selected @endif>Matkes</option>
      <option @if ($selected == 'Umum') selected @endif>Umum</option>
      <option @if ($selected == 'ATK') selected @endif>ATK</option>
    </select>
  </div>

  <div class="form-group">
    <label>Kategori</label>
    <div>
      @forelse ($kategoris as $kategori)
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="kategori_ids[]" value="{{ $kategori->id }}" id="kategori-{{ $kategori->id }}"
            @if (in_array($kategori->id, $selectedKategoriIds)) checked @endif>
          <label class="form-check-label" for="kategori-{{ $kategori->id }}">
            {{ $kategori->nama }} ({{ $kategori->kode }})
          </label>
        </div>
      @empty
        <p class="text-muted">Belum ada kategori. <a href="{{ url('kategori/form/new') }}" target="_blank">Buat kategori baru</a>.</p>
      @endforelse
    </div>
  </div>

  <div class="form-group">
    <label>Foto</label>

    @if (!empty($item) && $item->foto_url)
      <div class="mb-2">
        <img src="{{ $item->foto_url }}" alt="Foto {{ $item->nama }}" style="max-width: 200px; max-height: 200px; display:block;">
        <small class="text-muted">Foto saat ini. Pilih file baru di bawah kalo mau ganti.</small>
      </div>
    @endif

    <input type="file" class="form-control" name="foto" accept="image/png, image/jpeg, image/webp">
    <small class="text-muted">Format jpg/jpeg/png/webp, maksimal 2MB.</small>
  </div>

  <button class="btn btn-primary mt-3">Submit</button>

</form>
