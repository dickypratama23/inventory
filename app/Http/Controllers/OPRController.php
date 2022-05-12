<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use PDF;
use Fpdf;
use Telegram;
use Telegram\Bot\FileUpload\InputFile;

use App\Department;
use App\User;
use App\Transaksi;
use App\Barang;
use App\Transaksi_detail;
use App\Stock;
use App\Alokasi;
use App\GO;
use App\Configures;
use App\Opr;

class OPRController extends Controller
{
    public function create()
    {
        if (!Session::get('login')) {
            return redirect('/login');
        }

        function numberToRomanRepresentation($number)
        {
            $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
            $returnValue = '';
            while ($number > 0) {
                foreach ($map as $roman => $int) {
                    if ($number >= $int) {
                        $number -= $int;
                        $returnValue .= $roman;
                        break;
                    }
                }
            }
            return $returnValue;
        }

        $faktur = 'O/' . date('Ymd') . '/' . numberToRomanRepresentation(date('y')) . '/' . numberToRomanRepresentation(date('m')) . '/' . strtotime(date('his'));
        $departments = Department::orderBy('created_at', 'DESC')->get();
        $users = User::orderBy('created_at', 'DESC')->get();

        return view('opr.create', compact('departments', 'users', 'faktur'));
    }

    public function save(Request $request)
    {

        //VALIDASI
        $this->validate($request, [
            'invoice' => 'required',
            'ke' => 'required',
            'pic' => 'required|string',
            'keterangan' => 'required|string',
            'pembuat' => 'required'
        ]);

        try {
            //MENYIMPAN DATA KE TABLE INVOICES
            $transaksi = Transaksi::create([
                'invoice' => $request->invoice,
                'department_id' => $request->ke,
                'pic' => $request->pic,
                'user_id' => $request->pembuat,
                'status' => 0,
                'note' => $request->keterangan,
                'sign' => ''
            ]);

            return redirect(route('opr.add', ['id' => $transaksi->id]));
        } catch (\Exception $e) {
            return redirect()->back()->with(['message_error' => $e->getMessage()]);
        }
    }

    public function add($id)
    {
        if (!Session::get('login')) {
            return redirect('/login');
        }

        $transaksi = Transaksi::with(['department', 'detail', 'detail.barang'])
            ->where([
                ['id', $id],
                ['status', 0]
            ])->first();


        $barang = Barang::orderBy('kode', 'ASC')->get();
        return view('opr.add', compact('transaksi', 'barang'));
    }

    public function update(Request $request, $id)
    {
        $USER = User::where('nik', session('nik'))->first();

        //VALIDASI
        $this->validate($request, [
            'barang_id' => 'required|exists:barangs,id',
            'qty' => 'required|integer'
            //'note' => 'required|string'
        ]);
        
        //VALIDASI STOCK
        $stock_barang = Alokasi::where([
                ['barang_id', $request->barang_id],
                ['user_id', $USER->id]
            ])->first();
        
        if(!$stock_barang){
            return redirect()->back()->with(['message_error' => 'Stock Barang Tidak Mencukupi ']);
        }
        
        if ($stock_barang->total < $request->qty) {
            return redirect()->back()->with(['message_error' => 'Stock Barang Tidak Mencukupi, Sisa Barang ' . $stock_barang->total]);
        }

        

        try {
            //SELECT DARI TABLE invoices BERDASARKAN ID
            $transaksi = Transaksi::find($id);

            //SELECT DARI TABLE products BERDASARKAN ID
            $barang = Barang::find($request->barang_id);

            //SELECT DARI TABLE invoice_details BERDASARKAN product_id & invoice_id
            $transaksi_detail = $transaksi->detail()->where('barang_id', $barang->id)->first();

            //JIKA DATANYA ADA
            if ($transaksi_detail) {
                //MAKA DATA TERSEBUT DI UPDATE QTY NYA
                $transaksi_detail->update([
                    'qty' => $request->qty
                ]);
            } else {
                //JIKA TIDAK MAKA DITAMBAHKAN RECORD BARU
                Transaksi_detail::create([
                    'transaksi_id' => $transaksi->id,
                    'barang_id' => $request->barang_id,
                    'qty' => $request->qty,
                    'serial_number' => $request->serial_number,
                    'note' => $request->note,
                    'rtype' => 'OA'
                ]);
            }

            //KEMUDIAN DI-REDIRECT KEMBALI KE FORM YANG SAMA
            return redirect()->back()->with(['message_success' => 'Product Telah Ditambahkan']);
        } catch (\Exception $e) {
            //dd($e->getMessage());
            return redirect()->back()->with(['message_error' => $e->getMessage()]);
        }
    }

    public function deleteBarang($id)
    {
        //SELECT DARI TABLE invoice_details BERDASARKAN ID
        $detail = Transaksi_detail::find($id);

        //KEMUDIAN DIHAPUS
        $detail->delete();

        //DAN DI-REDIRECT KEMBALI
        return redirect()->back()->with(['message_success' => 'Product telah dihapus']);
    }

    public function selesai($id)
    {
        $transaksi = Transaksi::find($id);
        $transaksi->update([
            'status' => 1
        ]);

        return redirect('/opr/new')->with(['message_success' => 'Transaksi Barang Keluar Berhasil Di Buat, Silahkan Hubungi Atasan Anda Untuk Di Setujui']);
    }

    //HITUNG STOCK OPR
    public function hitung()
    {
        if (!Session::get('login')) {
            return redirect('/login');
        }

        $user = User::where('role',5)->get();
        $barang = Barang::where('opr',1)->get();
        $opr_lpp = Opr::all();

        // foreach ($user as  $dt_user) {
        //     foreach ($barang as  $dt_barang) {
        //         Opr::insert([
        //             'user_id' => $dt_user->id,
        //             'barang_id' => $dt_barang->id,
        //             'awal' => 0,
        //             'in' => 0,
        //             'out' => 0,
        //             'bap' => 0,
        //             'adj' => 0
        //         ]);
        //     }
        // }

        $PERIODE_NOW = date('ym');
        $const = Configures::where('rtype', 'OPR')->first();
        list($tahun, $bulan) = explode('|', $const->val);
        $thn = substr($tahun, 2);
        $PERIODE_TUTUPAN = $thn . $bulan;

        $PERIODE_TUTUPAN_SEBELUMNYA = $thn . $bulan - 1;
        if ($bulan == '01') {
            $PERIODE_TUTUPAN_SEBELUMNYA = $thn - 1 . '12';
        }

        //CEK TUTUPAN
        if ($PERIODE_NOW == $PERIODE_TUTUPAN) {
            echo "Tutupan Sudah Di Lakukan";
            //return;
        }

        //BUAT TABLE

        if (!Schema::hasTable('opr_lpp_' . $PERIODE_TUTUPAN)) {
            Schema::create('opr_lpp_' . $PERIODE_TUTUPAN, function (Blueprint $table) {
                $table->integer('user_id');
                $table->integer('barang_id');
                $table->integer('awal');
                $table->integer('in');
                $table->integer('out');
                $table->integer('bap');
                $table->integer('adj');
                $table->integer('total');

                $table->primary(['user_id', 'barang_id']);
            });
        }

        //INSERT BARANG
        foreach ($user as  $dt_user) {
            foreach ($barang as  $dt_barang) {
                try {
                    DB::table('opr_lpp_' . $PERIODE_TUTUPAN)->insert([
                        'user_id' => $dt_user->id,
                        'barang_id' => $dt_barang->id,
                        'awal' => 0,
                        'in' => 0,
                        'out' => 0,
                        'bap' => 0,
                        'adj' => 0,
                        'total' => 0,
                    ]);
                } catch (\Illuminate\Database\QueryException $e) {
                }
            }
        }

        //UPDATE AWAL

        //DATA USER
        foreach ($user as  $dt_user) {
            echo $dt_user->nik . ' | ' . $dt_user->name . '<br>';

            $transaksi = Transaksi::where([
                ['pic', $dt_user->nik . ' | ' . $dt_user->name],
                ['invoice', 'like', 'ALO%']
            ])->get();

            //DATA TRANSAKSI PER USER
            foreach ($transaksi as  $dt) {
                $transaksi = Transaksi::find($dt->id);
                $transaksi_details = $transaksi->detail()->get();

                //DETAIL TRANSAKSI
                foreach ($transaksi_details as  $td) {
                    echo $td->barang_id . '<br>';
                }
            }

            return;

        }






        // try {
        //     $opr_lpp = DB::table('opr_lpp_' . $PERIODE_TUTUPAN_SEBELUMNYA)->get();
        //     foreach ($opr_lpp as $row) {
        //         DB::table('opr_lpp_' . $PERIODE_TUTUPAN)->where('barang_id', $row->barang_id)->update([
        //             'awal' => $row->akhir
        //         ]);
        //     }
        // } catch (\Illuminate\Database\QueryException $e) {
        // }

        //UPDATE OPR MASUK

        

    }
}
