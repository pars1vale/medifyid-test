<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Barryvdh\DomPDF\Facade\Pdf; // ASUMSI: package barryvdh/laravel-dompdf, alias facade default
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class KategoriController extends Controller
{
    public function index()
    {
        return view('kategori.index.index');
    }

    // filter data berdasarkan inputan user (kode, nama)
    public function search(Request $request)
    {
        $kode = $request->kode;
        $nama = $request->nama;

        $data_search = Kategori::query();

        if (! empty($kode)) {
            $data_search = $data_search->where('kode', $kode);
        }
        if (! empty($nama)) {
            $data_search = $data_search->where('nama', 'LIKE', '%'.$nama.'%');
        }

        $data_search = $data_search->withCount('masterItems')->orderBy('id')->get();

        return json_encode([
            'status' => 200,
            'data' => $data_search,
        ]);
    }

    public function formView($method, $id = 0)
    {
        if ($method == 'new') {
            $item = [];
        } else {
            $item = Kategori::find($id);
            if (! $item) {
                abort(404, 'Kategori tidak ditemukan.');
            }
        }
        $data['item'] = $item;
        $data['method'] = $method;

        return view('kategori.form.index', $data);
    }

    // kas tampil detail kategori + daftar item yang memakai kategori tersebut
    public function singleView($kode)
    {
        $data['data'] = Kategori::where('kode', $kode)->with('masterItems')->first();
        if (! $data['data']) {
            abort(404, 'Kategori tidak ditemukan.');
        }

        return view('kategori.single.index', $data);
    }

    // generate dan download PDF detail kategori + daftar item yang memakainya
    public function printPdf($kode)
    {
        $data['data'] = Kategori::where('kode', $kode)->with('masterItems')->first();
        if (! $data['data']) {
            abort(404, 'Kategori tidak ditemukan.');
        }
        $data['printedAt'] = now();

        $pdf = Pdf::loadView('kategori.single.print', $data);
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('kategori-'.$data['data']->kode.'.pdf');
    }

    public function formSubmit(Request $request, $method, $id = 0)
    {
        if ($method == 'new') {
            $data_item = new Kategori;
            $request->validate([
                'nama' => 'required|string|max:255',
                'kode' => 'required|string|max:50|unique:kategoris,kode',
            ]);
        } else {
            $data_item = Kategori::find($id);
            if (! $data_item) {
                abort(404, 'Kategori tidak ditemukan.');
            }
            $request->validate([
                'nama' => 'required|string|max:255',
                'kode' => ['required', 'string', 'max:50', Rule::unique('kategoris', 'kode')->ignore($data_item->id)],
            ]);
        }

        $data_item->nama = $request->nama;
        $data_item->kode = $request->kode;
        $data_item->save();

        return redirect('kategori')->with('success', 'Kategori berhasil disimpan.');
    }

    // hapus kategori, lepas relasi dari semua item, dan kasih tahu dampaknya
    public function delete($id)
    {
        $data_item = Kategori::find($id);
        if (! $data_item) {
            abort(404, 'Kategori tidak ditemukan.');
        }

        $affectedCount = $data_item->masterItems()->count();
        $nama = $data_item->nama;

        DB::transaction(function () use ($data_item) {
            $data_item->masterItems()->detach();
            $data_item->delete();
        });

        $message = $affectedCount > 0
            ? "Kategori '{$nama}' berhasil dihapus. {$affectedCount} item kehilangan kategori ini."
            : "Kategori '{$nama}' berhasil dihapus.";

        return redirect('kategori')->with('success', $message);
    }
}
