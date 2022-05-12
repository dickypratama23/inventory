<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use PDF;
use Excel;
use Exception;
use Carbon\Carbon;

use App\Department;
use App\User;
use App\Transaksi;
use App\Barang;
use App\Transaksi_detail;
use App\Stock;
use App\Alokasi;
use App\Configures;
use App\Permintaan;
use App\Demand;
use App\DemandProses;

use App\Exports\LPPExport;

class LPPController extends Controller
{
    public function index()
    {

        if (!Session::get('login')) {
            return redirect('/login');
        }

        app('App\Http\Controllers\HitungUlangController')->hitung_ulang();
        app('App\Http\Controllers\HitungUlangController')->begbal();

        $lpps = Stock::where('recid',1)->orderBy('barang_id', 'ASC')->get();

        return view('lpp.index', compact('lpps'));
    }

    public function alokasi()
    {
        if (!Session::get('login')) {
            return redirect('/login');
        }

        app('App\Http\Controllers\HitungUlangController')->hitung_ulang();

        $users = User::where('role', 5)->get();
        $lpps = Alokasi::orderBy('user_id', 'ASC')
            ->orderBy('barang_id', 'ASC')
            ->get();

        return view('lpp.alokasi', compact('lpps', 'users'));
    }

    public function lppExport()
    {
        return Excel::download(new LPPExport, 'LPPExport.xlsx');
    }

    public function permintaan()
    {
        if (!Session::get('login')) {
            return redirect('/login');
        }

        $ttl_toko = Department::where('jenis', 'toko')->count();
        
        list($tahun, $bulan) = explode('|',date('Y|m'));
        
		//3 BULAN AKHIR (EXCLUDE BULAN INI)
        $l1 = date('ym',strtotime("01-" . $bulan . '-' . $tahun . " -3 Months"));
        $l2 = date('ym', strtotime("01-" . $bulan . '-' . $tahun . " -2 Months"));
        $l3 = date('ym', strtotime("01-" . $bulan . '-' . $tahun . " -1 Months"));
		
		//3 BULAN AKHIR (INCLUDE BULAN INI)
        // $l1 = date('ym',strtotime("01-" . $bulan . '-' . $tahun . " -2 Months"));
        // $l2 = date('ym', strtotime("01-" . $bulan . '-' . $tahun . " -1 Months"));
        // $l3 = date('ym', strtotime("01-" . $bulan . '-' . $tahun));
        
        $permintaan = Demand::orderBy('barang_id', 'ASC')->get();
        foreach($permintaan as $row){
            $id = $row->barang_id;
            $jenis = $row->jenis;
            $ttl2 = $row->ttl2;
            $min = $row->min;
			$ft = $row->ft;

            //last 3 bulan
            try {
                $data = DB::table('st_edp_' . $l1)->where('id', $id)->first();
                $out1 = $data->out + $data->alo;
            } catch (Exception $e) {
                $out1 = 0;
            }

            //last 2 bulan
            try {
                $data = DB::table('st_edp_' . $l2)->where('id', $id)->first();
                $out2 = $data->out + $data->alo;
            } catch (Exception $e) {
                $out2 = 0;
            }

            //last 1 bulan
            try {
                $data = DB::table('st_edp_' . $l3)->where('id', $id)->first();
                $out3 = $data->out + $data->alo;
            } catch (Exception $e) {
                $out3 = 0;
            }
			
			//BULAN INI
            // try {
            //     $data = DB::table('stocks')->where('id', $id)->first();
            //     $out3 = $data->out + $data->alo;
            // } catch (Exception $e) {
            //     $out3 = 0;
            // }

            //saldo now
            try {
                $data = Stock::where('barang_id', $id)->first();
                $saldo = $data->total;
            } catch (Exception $e) {
                $saldo = 0;
            }

            //pp on progress
            try {
                $data = DemandProses::where([ ['barang_id', $id], ['status', '!=', 1]])->select('barang_id', DB::raw('SUM(qty - minus) total'))->groupBy('barang_id')->first();
                $belum = $data->total;
                //dd($belum);
            } catch (Exception $e) {
                $belum = 0;
            }

            
            $ttl1 = $out1 + $out2 + $out3;
            $avg = $ttl1 / 3;

            //buffer stock
            if($jenis == 'CAD'){
                $buffer = $ttl_toko * (5/100) * $ttl2;
            }

            if ($jenis == 'SPARE') {
                $buffer = ceil($avg);
            }

            $max = ceil($buffer + $avg);

            //minta
            $minta = 0;
            try {
                if(($max - $saldo) / $min > 0.6){
                    $minta = ceil(($max - $saldo) / $min) * $min;
                }
            } catch (Exception $e) {
                $minta = 0;
            }
		
			

            $upd = Demand::where('id', $id)->update([
                'ttl1' => $ttl1,
                'avg' => $avg,
                'buffer' => $buffer,
                'max' => $max,
                'saldo' => $saldo,
                'belum' => $belum,
                'minta' => $minta + $ft,
                'kurang' => $belum - ($minta + $ft) < 0 ? $minta + $ft -  $belum : 0
            ]);

            // $upd = Demand::where('id', $id)->update([
            //     'ttl1' => 0,
            //     'avg' => 0,
            //     'buffer' => 0,
            //     'max' => 0,
            //     'saldo' => 0,
            //     'belum' => 0,
            //     'minta' => $ft,
            //     'kurang' => $ft
            // ]);
			
			
        }

        $cabang = Demand::where([['jenis2', 'cabang'], ['kurang', '>', 0]])->orderBy('minta', 'DESC')->get();
        //$ho = Demand::where([['belum', 0], ['minta', '!=', 0], ['jenis2', 'HO']])->havingRaw('IFNULL(belum,0) - IFNULL(minta,0) < 0')->orderBy('minta', 'DESC')->get();
		$ho = Demand::where([['jenis2', 'HO'], ['kurang','>',0]])->orderBy('minta', 'DESC')->get();
        return view('lpp.permintaan', compact('cabang', 'ho'));

    }

    public function permintaan_proses($tipe)
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

        $tipe_pp = strtoupper($tipe);
        $auto = numberToRomanRepresentation(date('m')) . '/IDM-BTM-' . $tipe_pp . '/' . date('d') . '/' . date('Y') . '/' . strtotime(date('his'));
        $cabang = strtotime(date('his'));

        $dem = Demand::where([['belum', 0], ['kurang', '>', 0], ['jenis2', $tipe]])->get();
        foreach ($dem as $row) {
            $barang_id = $row->barang_id;
            $qty = str_replace('-','', $row->kurang);

            DemandProses::create([
                'auto' => $auto,
                'cabang' => $cabang,
                'barang_id' => $barang_id,
                'qty' => $qty,
                'minus' => 0,
                'note' => '',
                'status' => 0,
                'proses' => Carbon::now(),
                'realisasi' => '0000-00-00 00:00:00'
            ]);
        }

        return redirect()->back()->with(['message_success' => 'Permintaan Berhasil Di Buat']);
    }
}
