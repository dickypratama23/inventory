<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use PDF;

Use App\Permintaan;
use App\DemandProses;
use App\User;
use App\Department;
use App\Transaksi;
use App\Transaksi_detail;
use App\Barang;

class PermintaanController extends Controller
{
	public function index()
	{
		if (!Session::get('login')) {
			return redirect('/login');
		}


		$pp = Permintaan::select(
															'jenis_pp', 
															'nomor_pp', 
															'note', 
															'created_at', 
															DB::raw('date(serah1) serah1'), 
															DB::raw('date(serah2) serah2'), 
															DB::raw('date(realisasi) realisasi'), 
															DB::raw('if(realisasi!="0000-00-00",count(*),0) terpenuhi'), 
															DB::raw('count(*) as total'),
															DB::raw('sum(minus) as ttl_minus')
														)
											->groupBy('nomor_pp')
											->orderBy('nomor_pp','DESC')
											->get();
		
		$pps = Permintaan::orderBy('nomor_pp','DESC')->get();

		return view('pp.index', compact('pp', 'pps'));
	}

	public function expBaru()
	{
		if (!Session::get('login')) {
			return redirect('/login');
		}

		$pp = Permintaan::where([
			'realisasi' => '0000-00-00 00:00:00'
		])->get();
		
		foreach ($pp as $key => $value) {
			$dp = DemandProses::create([
				'auto' => 'IDM-BTM-CABANG/' . $value->nomor_pp,
				'cabang' => $value->nomor_pp,
				'barang_id' => $value->barang_id,
				'qty' => $value->qty,
				'minus' => 0,
				'note' => '',
				'status' => 0,
				'so' => 0,
				'so_ket' => '',
				'proses' => Carbon::now(),
				'realisasi' => '0000-00-00 00:00:00'
			]);
		}

		Permintaan::where([
			'realisasi' => '0000-00-00 00:00:00'
		])->delete();

		return redirect()->back();
	}

	public function index_baru()
	{
		if (!Session::get('login')) {
			return redirect('/login');
		}

		$dp_cabang = DemandProses::where('auto', 'like', '%cabang%')->orderBy('proses', 'desc')->orderBy('cabang')->get();
		$dp_ho = DemandProses::where('auto', 'like', '%ho%')->orderBy('proses', 'desc')->orderBy('cabang')->get();

		return view('pp.index_baru', compact('dp_cabang', 'dp_ho'));
	}

	public function so($no_pp, $id_barang)
	{
		if (!Session::get('login')) {
			return redirect('/login');
		}

		
		dd($no_pp);
	}

	public function proses(Request $request)
	{

		$CABANG = 0;
		if (preg_match('/\bCABANG\b/', $request->docno_permintaan)) {
			$CABANG = 1;
		}

		$invoice = 'IN/' . $request->docno_permintaan . '/' . $request->nomor_permintaan;

		$dept = Department::where('kdtk', 'HO')->first();
		$department_id = $dept->id;
		if($CABANG == 1){
			$dept = Department::where('kdtk', 'GA')->first();
			$department_id = $dept->id;
		}
	
		$pic = $request->pic;
		$user = User::where('nik', Session('nik'))->first();
		$user_id = $user->id;
		$note = $request->note;

		$barang = Barang::where('kode', $request->kode_barang_permintaan)->first();
		$barang_id = $barang->id;
		$qty = $request->qty_terima;

		$qty_sbl = DemandProses::where([['auto', $request->docno_permintaan], ['barang_id', $barang_id]])->first();
		$qty0 = $qty_sbl->minus;
		$qty1 = $qty_sbl->qty;

		DemandProses::where([['auto', $request->docno_permintaan], ['barang_id', $barang_id]])->update([
			'minus' => $qty + $qty0,
			'note' => $note,
			'status' => $qty1 == $qty + $qty0 ? 1 : 2, //1 OK, 2 MASIH BELUM KLOP BARANG DARI GA
			'realisasi' => Carbon::now()
		]);

		//insery ke transaksi
		$transaksi = Transaksi::create([
			'invoice' => $invoice,
			'department_id' => $department_id,
			'pic' => $pic,
			'user_id' => $user_id,
			'status' => 2,
			'note' => $note,
			'sign' => '',
			'send_telegram' => 1
		]);
		
		$transaksi_id = $transaksi->id;

		//insery ke transaksi detail
		$detail = Transaksi_detail::create([
			'transaksi_id' => $transaksi_id,
			'barang_id' => $barang_id,
			'cad_id' => 0,
			'qty' => $qty,
			'cads' => 0,
			'note' => $request->docno_permintaan,
			'rtype' => 'I',
			'approve' => 0,
			'user_id' => 0,
			'ho' => 0
		]);

		return redirect()->back()->with(['message_success' => 'Transaksi Masuk Berhasil']);
	}

	public function pp_detail($no_pp)
	{
		if (!Session::get('login')) {
			return redirect('/login');
		}

		$NO_PP = $no_pp;
		$det_pp = Permintaan::where('nomor_pp', $NO_PP)->get();

		return view('pp.detail', compact('NO_PP', 'det_pp'));
	}

	public function buat()
	{
		if (!Session::get('login')) {
			return redirect('/login');
		}

		$del_pp_st0 = Permintaan::where('status', 0);
		$del_pp_st0->delete();

		$pps = Permintaan::groupBy('nomor_pp')->orderBy('nomor_pp','DESC')->first();
		
		if(!$pps)
		{
			$NO_PP = 1;
		}else{
			$NO_PP = $pps->nomor_pp + 1;
		}

		return view('pp.buat', compact('NO_PP'));
	}

	public function buat2($no_pp)
	{
		if (!Session::get('login')) {
			return redirect('/login');
		}

		$NO_PP = $no_pp;
		$J_PP = Permintaan::where('nomor_pp', $NO_PP)->first();

		$PP = Permintaan::where('nomor_pp', $NO_PP)->get();

		return view('pp.buat2', compact('NO_PP', 'J_PP', 'PP'));
	}

	public function save(Request $request)
	{
		if (!Session::get('login')) {
			return redirect('/login');
		}

		//VALIDASI
		$this->validate($request, [
			'no_pp' => 'required',
			'jenis_pp' => 'required',
			'barang_id' => 'required',
			'qty' => 'required'
		]);
		
		$JENIS_PP = $request->jenis_pp;
		$NO_PP = $request->no_pp;
		$BARANG_ID = $request->barang_id;
		$QTY = $request->qty;

		// dd($QTY);
		try {
			//MENYIMPAN DATA KE TABLE INVOICES
			$Permintaan = Permintaan::create([
				'jenis_pp' => $JENIS_PP,
				'nomor_pp' => $NO_PP,
				'barang_id' => $BARANG_ID,
				'qty' => $QTY,
				'serah1' => '0000-00-00 00:00:00',
				'serah2' => '0000-00-00 00:00:00',
				'realisasi' => '0000-00-00 00:00:00',
				'minus' => 0,
				'note' => '',
				'status' => 0
			]);

			return redirect(route('lpp.buat2', ['id' => $NO_PP]));
		} catch (\Exception $e) {
				//return redirect()->back()->with(['message_error' => $e->getMessage()]);
		}

		
	}

	public function update(Request $request)
	{
		if (!Session::get('login')) {
			return redirect('/login');
		}

		//VALIDASI
		$this->validate($request, [
			'nomor_pp' => 'required',
			'barang_id' => 'required',
			'minus' => 'required',
			'note' => 'required'
		]);

		$pp_upd = Permintaan::where([
			['nomor_pp', $request->nomor_pp],
			['barang_id', $request->barang_id],
		]);

		$pp_upd->update([
				'realisasi' => Carbon::now(),
				'minus' => $request->minus,
				'note' => $request->note,
		]);

		return redirect()->back()->with(['message_success' => 'Status PP Telah Berubah']);

	}

	public function deleteBarang($id, $id2)
	{
			//SELECT DARI TABLE invoice_details BERDASARKAN ID
			$detail = Permintaan::where([
				['nomor_pp', $id],
				['barang_id', $id2]
			]);

			//KEMUDIAN DIHAPUS
			$detail->delete();
			
			$pp_exist = Permintaan::where([
				['nomor_pp', $id]
			])->count();

			if($pp_exist == 0){
				
				//DAN AWAL
				return redirect(route('lpp.buat'))->with(['message_success' => 'PP di cancel']);	
			} else {

				//DAN DI-REDIRECT KEMBALI
				return redirect()->back()->with(['message_success' => 'Product telah dihapus']);
			}

	}

	public function selesai($id)
	{
			$transaksi = Permintaan::where('nomor_pp', $id);
			$transaksi->update([
					'status' => 1
			]);

			return redirect(route('lpp.buat'))->with(['message_success' => 'PP Berhasil Di Buat']);	
			//return redirect('/transin/new')->with(['message_success' => 'Transaksi Barang Masuk Berhasil']);
	}

	public function histori_penerimaan()
	{

	}
}
