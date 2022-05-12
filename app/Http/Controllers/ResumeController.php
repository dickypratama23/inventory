<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use App\Transaksi;
use App\Transaksi_detail;
use App\Resume;
use App\Configures;
use App\Barang;

class ResumeController extends Controller
{
    public function index()
    {
        if (!Session::get('login')) {
            return redirect('/login');
        }

        $datas = Transaksi::where('invoice', 'like', '%serv/i%')->get();
        foreach ($datas as $row) {
            foreach ($row->detail as $data) {
                try {
                    Resume::create([
                        'inv_in' => $row->invoice,
                        'inv_out' => "",
                        'barang_id' => $data->barang_id,
                        'department_id' => $row->department_id,
                        'pic1' => $row->pic,
                        'pic2' => "",
                        'user_id' => $row->user_id,
                        'status' => $data->approve,
                        'note' => $data->note,
                        'ganti' => 0,
                        'item' => "",
                        'sign' => "",
                        'telegram' => 0,
                        'created_at' => $row->created_at,
                        'updated_at' => $row->created_at,
                        'approved_at' => "0000-00-00 00:00:00",
                    ]);
                } catch (\Exception $e) {
                }
            }
        }

        $datas = Transaksi::where('invoice', 'like', '%serv/o%')->get();
        foreach ($datas as $row) {
            $placeholders = array();
            foreach ($row->detail as $data) {
                $placeholders[] = $data->barang->name;
            }

            $ganti = count($placeholders);
            $item = implode(', ', $placeholders);

            Resume::where([['inv_in', $row->inv_relation], ['barang_id', $row->barang_id]])->update([
                'inv_out' => $row->invoice,
                'pic2' => $row->pic,
                'status' => $row->status,
                'ganti' => $ganti,
                'item' => $item,
                'sign' => $row->sign,
                'updated_at' => $row->created_at,
                'approved_at' => $row->approved_at,
            ]);
        }
        
    }

    public function service_tutupan()
    {
        $PERIODE_NOW = date('ym');
        $const = Configures::where('rtype', 'SERVICE')->first();
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

        if (!Schema::hasTable('resume_' . $PERIODE_TUTUPAN)) {
            Schema::create('resume_' . $PERIODE_TUTUPAN, function (Blueprint $table) {
                $table->integer('barang_id')->primary();
                $table->string('nama');
                $table->integer('awal');
                $table->integer('masuk');
                $table->integer('selesai');
                $table->integer('akhir');
            });
        }
        

        //INSERT BARANG
        $barang = Barang::where('service',1)->get();

        foreach ($barang as  $data) {
            try {
                DB::table('resume_' . $PERIODE_TUTUPAN)->insert([
                    'barang_id' => $data->id,
                    'nama' => $data->name,
                    'awal' => 0,
                    'masuk' => 0,
                    'selesai' => 0,
                    'akhir' => 0
                ]);
            } catch (\Illuminate\Database\QueryException $e) {
            }
        }

        //UPDATE RESUME AWAL
        try {
            $resume = DB::table('resume_' . $PERIODE_TUTUPAN_SEBELUMNYA)->get();
            foreach ($resume as $row) {
                DB::table('resume_' . $PERIODE_TUTUPAN)->where('barang_id', $row->barang_id)->update([
                    'awal' => $row->akhir
                ]);

                //echo $row->nama . '|' . $row->akhir . '</br>';
            }
        } catch (\Illuminate\Database\QueryException $e) {
        }

        //UPDATE RESUME MASUK
        $resume = Resume::whereYear('created_at', $tahun)->whereMonth('created_at', $bulan)->groupBy('barang_id')
            ->select('barang_id', DB::raw('count(*) masuk'))
            ->get();

        foreach ($resume as  $row) {
            DB::table('resume_' . $PERIODE_TUTUPAN)->where('barang_id', $row->barang_id)->update([
                'masuk' => $row->masuk
            ]);

            //echo $row->barang->name . '|' . $row->masuk . '</br>';
        }

        //UPDATE RESUME SELESAI
        $resume = Resume::whereYear('updated_at', $tahun)->whereMonth('updated_at', $bulan)->where('status', 2)->groupBy('barang_id')
            ->select('barang_id', DB::raw('count(*) selesai'))
            ->get();

        foreach ($resume as  $row) {
            DB::table('resume_' . $PERIODE_TUTUPAN)->where('barang_id', $row->barang_id)->update([
                'selesai' => $row->selesai
            ]);

            //echo $row->barang->id . ' - ' . $row->barang->name . '|' . $row->selesai . '</br>';
        }

        //UPDATE RESUME AKHIR
        $resume = DB::table('resume_' . $PERIODE_TUTUPAN)->get();
        foreach ($resume as  $row) {
            DB::table('resume_' . $PERIODE_TUTUPAN)->where('barang_id', $row->barang_id)->update([
                'akhir' => $row->awal + $row->masuk - $row->selesai
            ]);
        }

        dd($resume);
    }

    public function gl_acc()
    {
        // $SER_IN = Transaksi::where('invoice', 'like', 'SERV/I%')
        //     ->whereMonth('created_at', '>', date('m') - 4)
        //     ->whereYear('created_at', 2020)
        //     ->orderBy('created_at', 'DESC')
        //     ->get();

        // $SER_OUT = Transaksi::where('invoice', 'like', 'SERV/O%')
        //     ->whereMonth('created_at', '>', date('m') - 4)
        //     ->whereYear('created_at', 2020)
        //     ->orderBy('created_at', 'DESC')
        //     ->get();

        $SER_OUT = Transaksi_detail::where([
            ['to_gl', '!=', 1],
            ['created_at', '>' , '2020-11-25'],
            ['rtype', 'o'],
            ['note', 'Ganti Spare Part']
        ])->get();



        return view('service.acc_gl',  compact('SER_OUT'));
    }

    public function gl_process($id)
    {
        Transaksi_detail::where('id', $id)->update(['to_gl' => 1]);

        return back();
    }
}
