<?php

namespace App\Http\Controllers;

use App\Models\MasterItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MasterItemsController extends Controller
{
    public function index()
    {
        return view('master_items.index.index');
    }

    // filter data berdasarkan inputan user (kode, nama, harga min, harga max)
    public function search(Request $request)
    {
        $kode = $request->kode;
        $nama = $request->nama;
        $hargamin = $request->hargamin;
        $hargamax = $request->hargamax;

        $data_search = MasterItem::query();

        if (! empty($kode)) {
            $data_search = $data_search->where('kode', $kode);
        }
        if (! empty($nama)) {
            $data_search = $data_search->where('nama', 'LIKE', '%'.$nama.'%');
        }
        if (! empty($hargamin)) {
            $data_search = $data_search->where('harga_beli', '>=', $hargamin)->where('harga_beli', '<=', $hargamax);
        }

        $data_search = $data_search->select('id', 'kode', 'nama', 'jenis', 'harga_beli', 'laba', 'supplier', 'foto')->orderBy('id')->get();

        $data_search->each(function ($item) {
            $item->foto_url = $item->foto_url;
        });

        return json_encode([
            'status' => 200,
            'data' => $data_search,
        ]);
    }

    //   kas tampil halaman form tambah atau ubah data Master Item.
    //   kalo method bernilai "new", maka form akan digunakan untuk menambahkan data baru.
    //   kalo tidak, data akan diambil berdasarkan ID untuk ditampilkan pada form edit.
    public function formView($method, $id = 0)
    {
        if ($method == 'new') {
            $item = [];
        } else {
            $item = MasterItem::find($id);
        }
        $data['item'] = $item;
        $data['method'] = $method;

        return view('master_items.form.index', $data);
    }

    // kas tampil detail data master item yang dipilih
    public function singleView($kode)
    {
        $data['data'] = MasterItem::where('kode', $kode)->first();

        return view('master_items.single.index', $data);
    }

    //  * Menyimpan data Master Item.
    //  *
    //  * dua proses:
    //  * - Menambahkan data baru (Create)
    //  * - Memperbarui data yang sudah ada (Update)
    //  *
    //  * when method bernilai "new", then buat objek MasterItem baru + bikin kode item baru otomat.
    //  * when method bukan "new", then data diambil berdasarkan ID baru update tanpa ubah kode item.
    public function formSubmit(Request $request, $method, $id = 0)
    {
        $request->validate([
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($method == 'new') {
            $data_item = new MasterItem;
            $kode = MasterItem::count('id');
            $kode = $kode + 1;
            $kode = str_pad($kode, 5, '0', STR_PAD_LEFT);
            sleep(3);
        } else {
            $data_item = MasterItem::find($id);
            $kode = $data_item->kode;
        }

        $data_item->nama = $request->nama;
        $data_item->harga_beli = $request->harga_beli;
        $data_item->laba = $request->laba;
        $data_item->kode = $kode;
        $data_item->supplier = $request->supplier;
        $data_item->jenis = $request->jenis;

        // kalo user upload foto baru, hapus foto lama (kalo ada) baru simpan yang baru
        if ($request->hasFile('foto')) {
            if (! empty($data_item->foto)) {
                Storage::disk('public')->delete($data_item->foto);
            }
            $data_item->foto = $request->file('foto')->store('master_items', 'public');
        }

        $data_item->save();

        return redirect('master-items');
    }

    public function delete($id)
    {
        $data_item = MasterItem::find($id);
        $data_item->delete();

        return redirect('master-items');
    }

    public function updateRandomData()
    {
        $data = MasterItem::get();
        foreach ($data as $item) {
            $kode = $item->id;
            $kode = str_pad($kode, 5, '0', STR_PAD_LEFT);

            $item->harga_beli = rand(100, 1000000);
            $item->laba = rand(10, 99);
            $item->kode = $kode;
            $item->supplier = $this->getRandomSupplier();
            $item->jenis = $this->getRandomJenis();
            $item->save();
        }
    }

    private function getRandomSupplier()
    {
        $array = ['Tokopaedi', 'Bukulapuk', 'TokoBagas', 'E Commurz', 'Blublu'];
        $random = rand(0, 4);

        return $array[$random];
    }

    private function getRandomJenis()
    {
        $array = ['Obat', 'Alkes', 'Matkes', 'Umum', 'ATK'];
        $random = rand(0, 4);

        return $array[$random];
    }
}
