<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

use App\Department;
use App\User;
use App\Transaksi;
use App\Barang;
use App\Transaksi_detail;
use App\Stock;
use App\Cad;
use App\TokoService;
use App\Configures;

class ApprovalController extends Controller
{
    public function approval_out()
    {
        if (!Session::get('login')) {
            return redirect('/login');
        }

        $transaksi  = Transaksi::with(['department', 'detail'])
            ->where([
                ['invoice', 'like', 'OUT%'],
                ['status', '=', 1]
            ])
            ->orWhere([
                ['invoice', 'like', 'A/%'],
                ['status', '=', 1]
            ])
            ->orWhere([
                ['invoice', 'like', 'O/%'],
                ['status', '=', 1]
            ])
            ->orderBy('created_at', 'DESC')
            ->get();

        $pinjam  = Transaksi::with(['department', 'detail'])
            ->where([
                ['invoice', 'like', 'LEN%'],
                ['status', '=', 1]
            ])
            ->orderBy('created_at', 'DESC')->get();

        $service  = Transaksi::with(['department', 'detail'])
            ->where([
                ['invoice', 'like', 'SERV/O%'],
                ['status', '=', 1]
            ])
            ->orderBy('created_at', 'DESC')->get();

        $service_in  = Transaksi::with(['department', 'detail'])
            ->where([
                ['invoice', 'like', 'SERV/I%'],
                ['status', '=', 1]
            ])
            ->orderBy('created_at', 'DESC')->get();

        $bap  = Transaksi::with(['department', 'detail'])
            ->where([
                ['invoice', 'like', 'BAP%'],
                ['status', '=', 1]
            ])
            ->orderBy('created_at', 'DESC')->get();

        return view('approves.index',  compact('transaksi', 'pinjam', 'service', 'service_in', 'bap'));
    }

    public function approve_out($id)
    {
        $user = User::where('nik', session('nik'))->first();

        $transaksi = Transaksi::find($id);
        $transaksi->update([
            'status' => 2,
            'approved_at' => Carbon::now() //TODO: PERUBAHAN
        ]);

        $transaksi_det = Transaksi_detail::where('transaksi_id', $id);
        $transaksi_det->update([
            'approve' => 2,
            'user_id' => $user->id,
            'approved_at' => Carbon::now() //TODO: PERUBAHAN
        ]);

        app('App\Http\Controllers\HitungUlangController')->hitung_ulang();

        return redirect('/approval')->with(['message_success' => 'Approve untuk ' . $transaksi->invoice . ' berhasil']);
    }

    public function reject_out($id)
    {
        $transaksi = Transaksi::find($id);
        $transaksi->update([
            'status' => 9
        ]);

        $transaksi_det = Transaksi_detail::where('transaksi_id', $id);
        $transaksi_det->update([
            'approve' => 9
        ]);

        return redirect('/approval')->with(['message_success' => 'Transaksi ' . $transaksi->invoice . ' di tolak']);
    }


    public function approve_lent($id)
    {
        $user = User::where('nik', session('nik'))->first();

        $transaksi = Transaksi::find($id);
        $transaksi->update([
            'status' => 2,
            'approved_at' => Carbon::now() //TODO: PERUBAHAN
        ]);

        $transaksi_det = Transaksi_detail::where('transaksi_id', $id);
        $transaksi_det->update([
            'approve' => 2,
            'user_id' => $user->id,
            'approved_at' => Carbon::now() //TODO: PERUBAHAN
        ]);

        return redirect('/approval')->with(['message_success' => 'Approve untuk ' . $transaksi->invoice . ' berhasil']);
    }

    public function reject_lent($id)
    {
        $transaksi = Transaksi::find($id);
        $transaksi_detail = $transaksi->detail()->get();

        foreach ($transaksi_detail as  $cad_id) {
            $Barang_cad = Cad::where('id', $cad_id->cad_id)->update(['recid' => 0]);
        }

        $transaksi->update([
            'status' => 9
        ]);

        $transaksi_det = Transaksi_detail::where('transaksi_id', $id);
        $transaksi_det->update([
            'approve' => 9
        ]);

        return redirect('/approval')->with(['message_success' => 'Transaksi ' . $transaksi->invoice . ' di tolak']);
    }

    public function approve_service($id, $sid, $bi)
    {
        
        $transaksi = Transaksi::find($id);
        $transaksi->update([
            'status' => 2,
            'approved_at' => Carbon::now() //TODO: PERUBAHAN
        ]);

        $transaksi_det = Transaksi_detail::where('transaksi_id', $id);
        $transaksi_det->update([
            'approve' => 2,
            'approved_at' => Carbon::now() //TODO: PERUBAHAN
        ]);

        $transaksi_barang_serv_in = Transaksi_detail::where([['transaksi_id', $sid], ['barang_id', $bi]]);
        $transaksi_barang_serv_in->update([
            'approve' => 2,
            'approved_at' => Carbon::now() //TODO: PERUBAHAN
        ]);

        $STATUS_TOKO = TokoService::where([['docno', $transaksi->inv_relation], ['kode_barang', $transaksi->barang->kode]]);
        $STATUS_TOKO->update([
            'RECID2' => 1
        ]);


        app('App\Http\Controllers\HitungUlangController')->hitung_ulang();
        return redirect('/approval')->with(['message_success' => 'Approve untuk ' . $transaksi->invoice . ' berhasil']);
    }

    public function reject_service($id, $sid, $bi)
    {
        $transaksi = Transaksi::find($id);
        $transaksi->delete();

        return redirect('/approval')->with(['message_success' => 'Reject untuk ' . $transaksi->invoice . ' berhasil']);
    }

    public function approve_bap($id)
    {
        $user = User::where('nik', session('nik'))->first();

        $transaksi = Transaksi::find($id);
        $transaksi->update([
            'status' => 2,
            'approved_at' => Carbon::now() //TODO: PERUBAHAN
        ]);

        $transaksi_det = Transaksi_detail::where('transaksi_id', $id);
        $transaksi_det->update([
            'approve' => 2,
            'user_id' => $user->id,
            'approved_at' => Carbon::now() //TODO: PERUBAHAN
        ]);

        return redirect('/approval')->with(['message_success' => 'Approve untuk ' . $transaksi->invoice . ' berhasil']);
    }
}
