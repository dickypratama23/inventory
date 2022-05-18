<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\User;
use App\Stock;
use App\Alokasi;
use App\Transaksi;
use App\Transaksi_detail;
use App\Barang;
use App\Configures;

class HitungUlangController extends Controller
{
    public function hitung_ulang()
    {

        Stock::where('id', '>', 0)->update([
            'in' => 0,
            'out' => 0,
            'alo' => 0,
            'total' => 0,
        ]);

        $tahun = date('Y');
        $bulan = date('m');
        //BARANG MASUK
        $transaksi_in = Transaksi_detail::whereYear('created_at', '=', $tahun)
            ->whereMonth('created_at', '=', $bulan)
            ->where('rtype', 'I')
            ->groupBy('barang_id')
            ->select('barang_id', DB::raw('SUM(qty) qty'))
            ->get();

        foreach ($transaksi_in as  $upd_in) {
            Stock::where('barang_id', $upd_in->barang_id)->update(['in' => $upd_in->qty]);
        }

        //BARANG KELUAR
        $transaksi_out = Transaksi_detail::whereYear('created_at', '=', $tahun)
            ->whereMonth('created_at', '=', $bulan)
            ->where('rtype', 'O')
            ->groupBy('barang_id')
            ->select('barang_id', DB::raw('SUM(qty) qty'))
            ->get();

        foreach ($transaksi_out as  $upd_out) {
            Stock::where('barang_id', $upd_out->barang_id)->update(['out' => $upd_out->qty]);
        }

        //BARANG ALOKASI
        $transaksi_alo = Transaksi_detail::whereYear('created_at', '=', $tahun)
            ->whereMonth('created_at', '=', $bulan)
            ->where('rtype', 'AL')
            ->groupBy('barang_id')
            ->select('barang_id', DB::raw('SUM(qty) qty'))
            ->get();

        foreach ($transaksi_alo as  $upd_alo) {
            Stock::where('barang_id', $upd_alo->barang_id)->update(['alo' => $upd_alo->qty]);
        }

        //TOTAL
        $total = Stock::all();

        foreach ($total as  $upd) {
            Stock::where('barang_id', $upd->barang_id)->update(['total' => $upd->begbal + $upd->in - $upd->out - $upd->alo]);
        }









        //PERHITUNGAN LAMA
        // $stock = Stock::all();
        // foreach ($stock as  $st) {
        //     $stock = Stock::where('barang_id', $st->barang->id)->first();

        //     $sum_transaksi_in = Transaksi_detail::where([
        //         ['barang_id', $st->barang->id],
        //         ['rtype', 'I'],
        //     ])->sum('qty');

        //     $stock->update([
        //         'in' => $sum_transaksi_in,
        //         'total' => $sum_transaksi_in - $stock->out - $stock->bap + $stock->adj
        //     ]);

        //     $sum_transaksi_out = Transaksi_detail::where([
        //         ['barang_id', $st->barang->id],
        //         ['rtype', 'O'],
        //         ['approve', 2],
        //     ])
        //     ->orWhere([
        //         ['barang_id', $st->barang->id],
        //         ['rtype', 'A'],
        //         ['approve', 2],
        //     ])->sum('qty');

        //     $stock->update([
        //         'out' => $sum_transaksi_out,
        //         'total' => $stock->in - $sum_transaksi_out - $stock->bap + $stock->adj
        //     ]);
        // }

        ///////////////////////////////////////////////////////////////////
        ////////// LPP LAPANGAN
        ///////////////////////////////////////////////////////////////////

        Alokasi::truncate();

        $OPR = User::where('role', 5)->get();
        foreach ($OPR as  $user) {

            $alokasi = Transaksi::where([
                ['invoice', 'like', 'A/%'],
                ['pic', 'like', $user->nik . '%']
            ])
            ->orWhere([
                ['invoice', 'like', 'O/%'],
                ['user_id', 'like', $user->id . '%']
            ])
            ->get();

            foreach ($alokasi as  $st) {
                
                $transaksi = Transaksi::find($st->id);
                $transaksi_details = $transaksi->detail()->get();

                foreach ($transaksi_details as  $td) {
                    $AloOpr = Alokasi::where([
                        ['user_id', $user->id],
                        ['barang_id', $td->barang_id]
                    ])->first();

                    if(!$AloOpr){
                        $Alokasi = Alokasi::create([
                            'user_id' => $user->id,
                            'barang_id' => $td->barang_id,
                            'in' => 0,
                            'out' => 0,
                            'bap' => 0,
                            'adj' => 0,
                            'total' => 0,
                        ]);
                    }
                    
                    $Alokasi_2 = Alokasi::where([
                        ['user_id', $user->id],
                        ['barang_id', $td->barang_id]
                    ])->first();

                    $QTY_IN_AWAL = $Alokasi_2->in;
                    $QTY_OUT_AWAL = $Alokasi_2->out;

                    $sum_transaksi_in = Transaksi_detail::where([
                        ['barang_id', $td->barang_id],
                        ['rtype', 'AL'],
                        ['transaksi_id', $transaksi->id]
                    ])->sum('qty');

                    $Alokasi_2->update([
                        'in' => $QTY_IN_AWAL + $sum_transaksi_in,
                        'total' => $QTY_IN_AWAL + $sum_transaksi_in - $Alokasi_2->out - $Alokasi_2->bap + $Alokasi_2->adj
                    ]);

                    $sum_transaksi_out = Transaksi_detail::where([
                        ['barang_id', $td->barang_id],
                        ['rtype', 'OA'],
                        ['transaksi_id', $transaksi->id],
                        ['approve', 2],
                    ])->sum('qty');

                    $Alokasi_2->update([
                        'out' => $QTY_OUT_AWAL + $sum_transaksi_out,
                        'total' => $Alokasi_2->in - ($QTY_OUT_AWAL + $sum_transaksi_out) - $Alokasi_2->bap + $Alokasi_2->adj
                    ]);
                }
            }
        }
    
    }

    public function begbal()
    {
        // $TAHUN = date('Y');
        // $BULAN = date('m') - 1;

        // if($BULAN == 0)
        // {
        //     $BULAN = 12;
        //     $TAHUN = $TAHUN - 1;
        // }

        // if($BULAN < 10)
        // {
        //     $BULAN = '0' . $BULAN;
        // }

        // $stock = Stock::all();
        // foreach ($stock as  $st) {
        //     $stock = Stock::where('barang_id', $st->barang->id)->first();

        //     $sum_transaksi_in = Transaksi_detail::where([
        //         ['barang_id', $st->barang->id],
        //         ['rtype', 'I'],
        //     ])
        //     ->whereRaw('YEAR(created_at) <= ' . $TAHUN . ' AND MONTH(created_at) <= ' . $BULAN)
        //     ->sum('qty');

        //     $sum_transaksi_out = Transaksi_detail::whereRaw("barang_id = '" . $st->barang->id . "' AND rtype IN ('O','A') AND approve = 2 AND YEAR(created_at) = " . $TAHUN . " AND MONTH(created_at) = " . $BULAN)
        //     ->sum('qty');

        //     $stock->update([
        //         'begbal' => $sum_transaksi_in - $sum_transaksi_out,
        //     ]);

            

        //     // $stock->update([
        //     //     'out' => $sum_transaksi_out,
        //     //     'total' => $stock->in - $sum_transaksi_out - $stock->bap + $stock->adj
        //     // ]);
        // }
    }

    public function tutupan()
    {
        $PERIODE_NOW = date('ym');
        $const = Configures::where('rtype', 'TUTUPAN')->first();
        list($tahun, $bulan) = explode('|', $const->val);
        $thn = substr($tahun, 2);
        $PERIODE_TUTUPAN = $thn . $bulan;

        $PERIODE_TUTUPAN_SEBELUMNYA = $thn . $bulan - 1;
        if($bulan == '01')
        {
            $PERIODE_TUTUPAN_SEBELUMNYA = $thn-1 . '12';
        }


        //CEK TUTUPAN
        if($PERIODE_NOW == $PERIODE_TUTUPAN){
            echo "Tutupan Sudah Di Lakukan";
            return;
        }
            


        //BUAT TABLE

        if (!Schema::hasTable('st_edp_' . $PERIODE_TUTUPAN)) {
            Schema::create('st_edp_' . $PERIODE_TUTUPAN, function (Blueprint $table) {
                $table->increments('id');
                $table->integer('barang_id');
                $table->integer('begbal');
                $table->integer('in');
                $table->integer('out');
                $table->integer('alo');
                $table->integer('bap');
                $table->integer('adj');
                $table->integer('total');
            });
        }

        if (!Schema::hasTable('transaksi_' . $PERIODE_TUTUPAN)) {
            Schema::create('transaksi_' . $PERIODE_TUTUPAN, function (Blueprint $table) {
                $table->increments('id');
                $table->string('invoice')->unique();
                $table->string('inv_relation')->default('');
                $table->unsignedInteger('barang_id')->default(0);
                $table->unsignedInteger('department_id');
                $table->string('pic');
                $table->unsignedInteger('user_id');
                $table->boolean('status')->default(false);
                $table->string('note')->nullable();
                $table->text('note2')->nullable();
                $table->timestamps();
                $table->dateTime('approved_at')->default('0000-00-00 00:00:00');
                $table->text('sign')->nullable();
                $table->boolean('send_telegram');
            });
        }
        
        if (!Schema::hasTable('transaksi_detail_' . $PERIODE_TUTUPAN)) {
            Schema::create('transaksi_detail_' . $PERIODE_TUTUPAN, function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('transaksi_id');
                $table->unsignedInteger('barang_id')->default(0);
                $table->unsignedInteger('cad_id')->default(0);
                $table->integer('qty');
                $table->string('Serial_number')->nullable();
                $table->boolean('cads');
                $table->string('note')->nullable();
                $table->string('rtype');
                $table->integer('approve')->default(0);
                $table->integer('user_id');
                $table->integer('ho')->default(0);
                $table->text('docno_ho')->nullable();
                $table->timestamps();
                $table->dateTime('approved_at');
            });
        }

        //INSERT BARANG

        $barang = Barang::all();

        foreach ($barang as  $data) {
            try {
                DB::table('st_edp_' . $PERIODE_TUTUPAN)->insert([
                    'id' => $data->id,
                    'barang_id' => $data->id,
                    'begbal' => 0,
                    'in' => 0,
                    'out' => 0,
                    'alo' => 0,
                    'bap' => 0,
                    'adj' => 0,
                    'total' => 0,
                ]);
            } catch (\Illuminate\Database\QueryException $e) {
                
            }
        }

        //INSERT TRANSAKSI BY PERIODE TUTUPAN

        $trans = Transaksi::whereYear('created_at', '=', $tahun)
            ->whereMonth('created_at', '=', $bulan)
            ->get();

        foreach ($trans as  $in_trans) {
            DB::table('transaksi_' . $PERIODE_TUTUPAN)->insert([
                'id' => $in_trans->id,
                'invoice' => $in_trans->invoice,
                'inv_relation' => $in_trans->inv_relation,
                'barang_id' => $in_trans->barang_id,
                'department_id' => $in_trans->department_id,
                'pic' => $in_trans->pic,
                'user_id' => $in_trans->user_id,
                'status' => $in_trans->status,
                'note' => $in_trans->note,
                'note2' => $in_trans->note2,
                'created_at' => $in_trans->created_at,
                'updated_at' => $in_trans->updated_at,
                'approved_at' => $in_trans->approved_at,
                'sign' => $in_trans->sign,
                'send_telegram' => $in_trans->send_telegram
            ]);
        }

        //INSERT TRANSAKSI DETAIL BY PERIODE TUTUPAN

        $trans_detail = Transaksi_detail::whereYear('created_at', '=', $tahun)
            ->whereMonth('created_at', '=', $bulan)
            ->get();
        //#, , , , , , , , , , , , , , , 
        foreach ($trans_detail as  $in_trans_detail) {
            DB::table('transaksi_detail_' . $PERIODE_TUTUPAN)->insert([
                'id' => $in_trans_detail->id,
                'transaksi_id' => $in_trans_detail->transaksi_id,
                'barang_id' => $in_trans_detail->barang_id,
                'cad_id' => $in_trans_detail->cad_id,
                'qty' => $in_trans_detail->qty,
                'Serial_number' => $in_trans_detail->Serial_number,
                'cads' => $in_trans_detail->cads,
                'note' => $in_trans_detail->note,
                'rtype' => $in_trans_detail->rtype,
                'approve' => $in_trans_detail->approve,
                'user_id' => $in_trans_detail->user_id,
                'ho' => $in_trans_detail->ho,
                'docno_ho' => $in_trans_detail->docno_ho,
                'created_at' => $in_trans_detail->created_at,
                'updated_at' => $in_trans_detail->updated_at,
                'approved_at' => $in_trans_detail->approved_at,
            ]);
        }

        //UPD BEGBAL DI PERIODE TUTUPAN

        try {
            $begbal = DB::table('st_edp_' . $PERIODE_TUTUPAN_SEBELUMNYA)->get();
            foreach ($begbal as  $upd_bb_sbl) {
                DB::table('st_edp_' . $PERIODE_TUTUPAN)->where('barang_id', $upd_bb_sbl->barang_id)->update(['begbal' => $upd_bb_sbl->total]);
            }
        } catch (\Illuminate\Database\QueryException $e) {
        }

        //BARANG MASUK
        $transaksi_in = Transaksi_detail::whereYear('created_at', '=', $tahun)
            ->whereMonth('created_at', '=', $bulan)
            ->where('rtype', 'I')
            ->groupBy('barang_id')
            ->select('barang_id', DB::raw('SUM(qty) qty'))
            ->get();

        foreach ($transaksi_in as  $upd_in) {
            DB::table('st_edp_' . $PERIODE_TUTUPAN)->where('barang_id', $upd_in->barang_id)->update(['in' => $upd_in->qty]);
        }
        
        //BARANG KELUAR
        $transaksi_out = Transaksi_detail::whereYear('created_at', '=', $tahun)
            ->whereMonth('created_at', '=', $bulan)
            ->where('rtype', 'O')
            ->groupBy('barang_id')
            ->select('barang_id', DB::raw('SUM(qty) qty'))
            ->get();

        foreach ($transaksi_out as  $upd_out) {
            DB::table('st_edp_' . $PERIODE_TUTUPAN)->where('barang_id', $upd_out->barang_id)->update(['out' => $upd_out->qty]);
        }

        //BARANG ALOKASI
        $transaksi_alo = Transaksi_detail::whereYear('created_at', '=', $tahun)
            ->whereMonth('created_at', '=', $bulan)
            ->where('rtype', 'A')
            ->groupBy('barang_id')
            ->select('barang_id', DB::raw('SUM(qty) qty'))
            ->get();

        foreach ($transaksi_alo as  $upd_alo) {
            DB::table('st_edp_' . $PERIODE_TUTUPAN)->where('barang_id', $upd_alo->barang_id)->update(['alo' => $upd_alo->qty]);
        }

        //TOTAL
        $total = DB::table('st_edp_' . $PERIODE_TUTUPAN)->get();
        
        foreach ($total as  $upd) {
            DB::table('st_edp_' . $PERIODE_TUTUPAN)->where('barang_id', $upd->barang_id)->update(['total' => $upd->begbal + $upd->in - $upd->out - $upd->alo]);
        }

        //STOCK AWAL BULAN
        try {
            $begbal = DB::table('st_edp_' . $PERIODE_TUTUPAN)->get();
            foreach ($begbal as  $upd_bb) {
                Stock::where('barang_id', $upd_bb->barang_id)->update(['begbal' => $upd_bb->total]);
            }
        } catch (\Illuminate\Database\QueryException $e) {
        }

        //UPDATE CONST
        Configures::where('rtype', 'TUTUPAN')->update(['val' => date('Y|m')]);




        // dd($transaksi_out);
    }

    public function LppOPr()
    {
        
    }
}
