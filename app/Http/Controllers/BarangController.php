<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Telegram;
use Illuminate\Support\Facades\DB;

use App\User;
use App\Kategori;
use App\Barang;
use App\Stock;
use App\Cad;
use App\TokoPinjam;
use App\Configures;
use App\Assembly;

class BarangController extends Controller
{
    public function index()
    {
        if (!Session::get('login')) {
            return redirect('/login');
        }

        $kategoris = Kategori::orderBy('created_at', 'DESC')->get();
        $barangs = Barang::orderBy('kode', 'ASC')->get();

        return view('barangs.index', compact('barangs', 'kategoris'));
    }

    //CAD INDEX
    public function cad_index()
    {
        if (!Session::get('login')) {
            return redirect('/login');
        }

        $kategoris = Kategori::orderBy('created_at', 'DESC')->get();
        $barangs = Cad::orderBy('kode', 'ASC')->get();

        $lpp_cad = Cad::select
                                (
                                    'NAME', 
                                    DB::raw('COUNT(*) SEMUA'),
                                    DB::raw('COUNT(IF(RECID = 0, 1,NULL)) READY'),
                                    DB::raw('COUNT(IF(RECID = 1, 1,NULL)) PINJAM'),
                                    DB::raw('COUNT(IF(RECID = 3, 1,NULL)) ALOKASI'),
                                    DB::raw('COUNT(IF(RECID = 5, 1,NULL)) RUSAK'),
									DB::raw('COUNT(IF(RECID = 9, 1,NULL)) NA')
                                )
                        ->groupBy('name')
                        ->get();

        return view('barangs.cad_index', compact('barangs', 'kategoris', 'lpp_cad'));
    }

    public function assembly()
    {
        if (!Session::get('login')) {
            return redirect('/login');
        }

        $data_toko = Assembly::whereNotIn('department_id', [765, 768])->get();
        $data_dept = Assembly::whereIn('department_id', [765, 768])->get();

        return view('barangs.assembly', compact('data_toko', 'data_dept'));
    }

    public function save(Request $request)
    {
        
        $this->validate($request, [
            'kode_barang' => 'required|string|max:100',
            'nama_barang' => 'required|string',
            'kategori' => 'required',
        ]);

        try {
            $Barang = Barang::create([
                'kode' => $request->kode_barang,
                'name' => $request->nama_barang,
                'kategori_id' => $request->kategori,
                'mac' => $request->to_by_mac
            ]);

            $Stock = Stock::create([
                'barang_id' => $Barang->id,
                'in' => 0,
                'out' => 0,
                'bap' => 0,
                'adj' => 0,
                'total' => 0
            ]);

            return redirect('/barang')->with(['message_success' => $Barang->name . ' Telah disimpan.']);
        } catch (\Exception $e) {
            return redirect('/barang')->with(['message_error' => $e->getMessage()]);
        }
    }

    //CAD SAVE
    public function cad_save(Request $request)
    {
        
        $this->validate($request, [
            'kode_barang' => 'required|string',
            'nama_barang' => 'required|string',
            'kategori' => 'required',
            'mac_sn' => 'required',
        ]);

        $last_no = Cad::where('kategori_id', $request->kategori)->orderBy('kode', 'DESC')->first();
        if ($last_no) {
            $kobar = (int) substr($last_no->kode, 12, 3) + 1;
        } else {
            $kobar = 1;
        }
        
        if ($kobar < 10) {
            $kobar = "00" . $kobar;
        } elseif ($kobar < 100) {
            $kobar = "0" . $kobar;
        }

        try {
            $Barang = Cad::create([
                'kode' => 'CAD-' . $request->kode_barang . '-' . $kobar,
                'name' => $request->nama_barang,
                'kategori_id' => $request->kategori,
                'mac' => $request->mac_sn
            ]);

            return redirect('/CAD')->with(['message_success' => $Barang->name . ' Telah disimpan.']);
        } catch (\Exception $e) {
            return redirect('/CAD')->with(['message_error' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        if (!Session::get('login')) {
            return redirect('/login');
        }
        $kategoris = Kategori::orderBy('created_at', 'DESC')->get();
        $barang = Barang::find($id);
        return view('barangs.edit', compact('barang', 'kategoris'));
    }

    //CAD EDIT
    public function cad_edit($id)
    {
        if (!Session::get('login')) {
            return redirect('/login');
        }
        $kategoris = Kategori::orderBy('created_at', 'DESC')->get();
        $barang = Cad::find($id);

        return view('barangs.cad_edit', compact('barang', 'kategoris'));
    }

    public function update(Request $request, $id)
    {
        if ($request->to_by_mac) {
            $mac = 1;
        }

        $mac = 0;
        try {

            $Barang = Barang::find($id);
            $Barang->update([
                'kode' => $request->kode_barang,
                'name' => $request->nama_barang,
                'kategori_id' => $request->kategori,
                'mac' => $mac
            ]);

            return redirect('/barang')->with(['message_success' => $Barang->name . ' Diperbaharui']);
        } catch (\Exception $e) {
            return redirect('/barang')->with(['message_error' => $e->getMessage()]);
        }
    }

    //CAD UPDATE
    public function cad_update(Request $request, $id)
    {
        $CEK_KAT_ID = Cad::find($id);

        if ($CEK_KAT_ID->kategori_id == $request->kategori) {
            $last_no = Cad::where('kategori_id', $request->kategori)->orderBy('kode', 'DESC')->first();
            if ($last_no) {
                $kobar = (int) substr($last_no->kode, 12, 3);
            } else {
                $kobar = 1;
            }

            $kode = 'CAD-' . $request->barang_kode_name . '-';
        } else {

            $last_no = Cad::where('kategori_id', $request->kategori)->orderBy('kode', 'DESC')->first();

            if ($last_no) {
                $kobar = (int) substr($last_no->kode, 12, 3) + 1;
            } else {
                $kobar = 1;
            }

            $kode = 'CAD-' . $request->barang_kode_name . '-';
        }

        if ($kobar < 10) {
            $kobar = "00" . $kobar;
        } elseif ($kobar < 100) {
            $kobar = "0" . $kobar;
        }

        try {

            $Barang = Cad::find($id);
            $Barang->update([
                'kode' => $kode . $kobar,
                'name' => $request->nama_barang,
                'kategori_id' => $request->kategori,
                'mac' => $request->mac_sn
            ]);

            return redirect('/CAD')->with(['message_success' => $Barang->name . ' Diperbaharui']);
        } catch (\Exception $e) {
            return redirect('/CAD')->with(['message_error' => $e->getMessage()]);
        }
    }

    //CAD RETURN / KEMBALI
    public function cad_return($id)
    {
        $user = User::where('nik', session('nik'))->first();
        $barang = Cad::find($id);

        $KDTK = $barang->department->kdtk;
        $NAMATOKO = $barang->department->name;
        $KODE_BARANG = substr($barang->kode, 4, 7);
        $NAMA_BARANG = $barang->name;
        $SN = $barang->mac;

        $barang->update([
            'department_id' => 0,
            'recid' => 0,
            'user_id' => $user->id
        ]);

        $ret = TokoPinjam::where('kdtk', $KDTK)
            ->where('kode_barang', $KODE_BARANG)
            ->where('sn', $SN)
            ->where('recid', 0)
            ->update([
                'kembali' => Carbon::now(),
                'recid' => 1
            ]);

        $error_telegram = array();
        $text = "Pengembalian Cadangan EDP\n\n"
            . "<b>Toko:</b> " .  $KDTK . ' :: ' . $NAMATOKO . " \n"
            . "<b>Barang: </b> " . $KODE_BARANG . ' :: ' . $NAMA_BARANG . "\n"
            . "<b>S/N: </b> " . $SN . "\n";

        // try {

        //     Telegram::sendMessage([
        //         'chat_id' => 610280902,
        //         'parse_mode' => 'HTML',
        //         'text' => $text
        //     ]);
        // } catch (\Exception $e) {
        //     $error_telegram[] = 610280902;
        // }

        // try {
        //     Telegram::sendMessage([
        //         'chat_id' => -300800445, //CHANEL DISTRIC EDP -300800445 
        //         'parse_mode' => 'HTML',
        //         'text' => $text
        //     ]);
        // } catch (\Exception $e) {
        //     $error_telegram = -300800445;
        // }

        return redirect()->back()->with(['message_success' => $barang->name . ' Di Kembalikan']);
    }

    public function destroy($id)
    {
        $barang = Barang::find($id);
        $barang->delete();
        return redirect('/barang')->with(['message_success' => $barang->name . ' Dihapus']);
    }
}
