<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Kategori {{ $data->kode }}</title>
  <style>
    @page {
      margin: 100px 40px 80px 40px;
    }

    body {
      font-family: 'DejaVu Sans', sans-serif;
      font-size: 12px;
      color: #222;
    }

    .header {
      margin-bottom: 20px;
    }

    .header h1 {
      font-size: 18px;
      margin: 0 0 4px 0;
    }

    .info-table {
      width: 100%;
      margin-bottom: 20px;
    }

    .info-table td {
      padding: 3px 0;
      vertical-align: top;
    }

    .info-table td.label {
      width: 120px;
      font-weight: bold;
    }

    .info-table td.sep {
      width: 15px;
    }

    table.items-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    table.items-table thead th {
      background-color: #f0f0f0;
      border: 1px solid #999;
      padding: 6px;
      text-align: left;
      font-size: 11px;
    }

    table.items-table tbody td {
      border: 1px solid #ccc;
      padding: 6px;
      font-size: 11px;
    }

    table.items-table tbody tr {
      page-break-inside: avoid;
    }

    .no-items {
      color: #777;
      font-style: italic;
    }

    /* Footer dicetak berulang di setiap halaman */
    .footer {
      position: fixed;
      bottom: -60px;
      left: 0px;
      right: 0px;
      height: 40px;
      font-size: 9px;
      color: #777;
      text-align: right;
      border-top: 1px solid #ccc;
      padding-top: 4px;
    }
  </style>
</head>

<body>

  <div class="footer">
    Dicetak pada: {{ $printedAt->translatedFormat('d F Y, H:i') }} WIB
  </div>

  <div class="header">
    <h1>Detail Kategori</h1>
  </div>

  <table class="info-table">
    <tr>
      <td class="label">Kode Kategori</td>
      <td class="sep">:</td>
      <td>{{ $data->kode }}</td>
    </tr>
    <tr>
      <td class="label">Nama Kategori</td>
      <td class="sep">:</td>
      <td>{{ $data->nama }}</td>
    </tr>
    <tr>
      <td class="label">Jumlah Item</td>
      <td class="sep">:</td>
      <td>{{ $data->masterItems->count() }}</td>
    </tr>
  </table>

  <h3>Daftar Item dengan Kategori Ini</h3>

  @if ($data->masterItems->isEmpty())
    <p class="no-items">Belum ada item dengan kategori ini.</p>
  @else
    <table class="items-table">
      <thead>
        <tr>
          <th style="width: 15%;">Kode</th>
          <th style="width: 45%;">Nama</th>
          <th style="width: 20%;">Jenis</th>
          <th style="width: 20%;">Supplier</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($data->masterItems as $mi)
          <tr>
            <td>{{ $mi->kode }}</td>
            <td>{{ $mi->nama }}</td>
            <td>{{ $mi->jenis }}</td>
            <td>{{ $mi->supplier }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @endif

</body>

</html>
