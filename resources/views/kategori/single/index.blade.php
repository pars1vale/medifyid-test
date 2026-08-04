@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="form-group mb-2">
          <a href="{{ url('kategori') }}" class="btn btn-secondary">Kembali ke Daftar Kategori</a>
        </div>
        <div class="card">
          <div class="card-header">Kategori</div>

          <div class="card-body">
            <table>
              <tr>
                <th>Kode</th>
                <td>:</td>
                <td>{{ $data->kode }}</td>
              </tr>
              <tr>
                <th>Nama</th>
                <td>:</td>
                <td>{{ $data->nama }}</td>
              </tr>
            </table>

            <a class="btn btn-info mt-2" href="{{ url('kategori/form/edit') }}/{{ $data->id }}">Edit</a>
            <a class="btn btn-success mt-2" href="{{ url('kategori/print/' . $data->kode) }}" target="_blank">Print PDF</a>
            <a class="btn btn-danger mt-2" href="{{ url('kategori/delete') }}/{{ $data->id }}"
              onclick="return confirm('Kategori ini dipakai oleh {{ $data->masterItems->count() }} item. Menghapusnya akan melepas kategori dari semua item tersebut. Lanjutkan?');">Delete</a>

            <hr>
            <h5>Item dengan kategori ini ({{ $data->masterItems->count() }})</h5>

            @if ($data->masterItems->isEmpty())
              <p class="text-muted">Belum ada item dengan kategori ini.</p>
            @else
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>View</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($data->masterItems as $mi)
                    <tr>
                      <td>{{ $mi->kode }}</td>
                      <td>{{ $mi->nama }}</td>
                      <td><a href="{{ url('master-items/view/' . $mi->kode) }}" class="btn btn-primary btn-sm">View</a></td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('js')
@endsection
