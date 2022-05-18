<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use App\Transaksi;
use App\Transaksi_detail;

class HistoriController extends Controller
{
    public function histori(Request $request)
    {
        if (!Session::get('login')) {
            return redirect('/login');
        }

        $tipe = $request->tipe;
        $barang = $request->id_barang;

        $data = array();

        if($tipe == 'masuk'){
            $data = Transaksi_detail::where([
                'rtype' => 'i',
                'barang_id' => $barang,
            ])->orderBy('created_at', 'DESC')->get();
        }

        if ($tipe == 'keluar') {
            $data = Transaksi_detail::where([
                'barang_id' => $barang,
            ])
            ->whereIn('rtype', ['O', 'OA'])
            ->orderBy('created_at', 'DESC')->get();

            
        }

        if ($tipe == 'service') {
            $data = Transaksi::where([
                ['invoice', 'like', 'SERV/O%'],
                'barang_id' => $barang,
                'status' => 2
            ])
            ->orderBy('created_at', 'DESC')->get();
        }

        if($tipe == 'alokasi'){
            $data = Transaksi_detail::where([
                'barang_id' => $barang,
            ])
            ->where('rtype', 'AL')
            ->orderBy('created_at', 'DESC')->get();
        }

        $r_data = array();
        foreach ($data as $key => $value) {
            
            $masuk = Transaksi::with('detail')->where('invoice', $value->inv_relation)->first();
            try {
                foreach ($masuk->detail as $key => $brg) {
                    $kerusakan = $brg->note;
                    $sn = $brg->Serial_number;
                }
            } catch (\Throwable $th) {
                $nm_barang = null;
                $kerusakan = null;
                $sn = null;
            }

            $dt['barang'] = $value->barang->name;
            $dt['sn'] = $sn;
            $dt['kerusakan'] = $kerusakan;
            $dt['qty'] = null;
            $dt['department'] = $tipe == 'service' ? $value->department->kdtk . ' - ' . $value->department->name : null;
            $dt['masuk'] = $masuk->created_at ?? null ? $masuk->created_at->format('Y-m-d')  : '';
            $dt['selesai'] = $tipe == 'service' ? $value->created_at->format('Y-m-d') : '';
            $dt['keluar'] = ($tipe == 'service' && $value->pic != 'NIK - NAMA PERSONIL TOKO')  ? $value->updated_at->format('Y-m-d') : null;

            array_push($r_data, $dt);
        }

        if($tipe != 'service'){

            $r_data = array();
            foreach ($data as $key => $value) {
                $dt['barang'] = $value->barang->name;
                $dt['qty'] = $value->qty;
                $dt['department'] = $tipe == 'alokasi' ? $value->transaksi->department->kdtk . ' (' . $value->transaksi->pic . ')' : $value->transaksi->department->kdtk . ' - ' . $value->transaksi->department->name;
                $dt['masuk'] = $tipe == 'masuk' ? $value->transaksi->created_at->format('Y-m-d') : '';
                $dt['selesai'] = null;
                $dt['keluar'] = $tipe == 'keluar' || $tipe == 'alokasi' ? $value->transaksi->created_at->format('Y-m-d') : '';

                array_push($r_data, $dt);
            }
        }

        return view('histori',compact('tipe', 'r_data'));
    }
}
