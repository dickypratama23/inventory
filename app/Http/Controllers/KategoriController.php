<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Telegram\Bot\Laravel\Facades\Telegram;
use Fpdf;
use Excel;

use App\Kategori;
use App\Transaksi;
use App\Exports\ManagementExport;

class KategoriController extends Controller
{
    public function index()
    {
        if(!Session::get('login')){
            return redirect('/login');
        }

        $kategoris = Kategori::orderBy('created_at', 'DESC')->get();
        return view('kategoris.index', compact('kategoris'));
    }

    public function save(Request $request)
    {
        $this->validate($request, [
            'nama_kategori' => 'required|string|max:100',
            'desk_kategori' => 'required|string'
        ]);

        
        try {
            $kategori = Kategori::create([
                'name' => $request->nama_kategori,
                'deskripsi' => $request->desk_kategori
            ]);
            
            return redirect('/kategori')->with(['message_success' => $kategori->name . ' Telah disimpan']);
        } catch(\Exception $e) {

            return redirect('/kategori')->with(['message_error' => $e->getMessage()]);

        }
    }

    public function edit($id)
    {
        if(!Session::get('login')){
            return redirect('/login');
        }
        
        $kategori = Kategori::find($id);
        return view('kategoris.edit', compact('kategori'));
    }

    public function update(Request $request, $id)
    {
        $kategori = Kategori::find($id);
        $kategori->update([
            'name' => $request->nama_kategori,
            'deskripsi' => $request->desk_kategori
        ]);
        
        return redirect('/kategori')->with(['message_success' => $kategori->name . ' Diperbaharui']);
    }

    public function destroy($id)
    {
        $kategori = Kategori::find($id);
        $kategori->delete();
        return redirect('/kategori')->with(['message_success' => $kategori->name . ' Dihapus']);
    }

    public function export_excel()
    {
        return Excel::download(new ManagementExport, 'ManagementExport.xlsx');
    }
}
