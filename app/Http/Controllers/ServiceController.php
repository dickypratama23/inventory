<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Telegram\Bot\FileUpload\InputFile;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; 
use PDF;
use Telegram;
use Fpdf;
use Excel;

use App\TokoService;
use App\User;
use App\Department;
use App\Transaksi;
use App\Barang;
use App\Transaksi_detail;
use App\Configures;
use App\Cad;
use App\Resume;

use App\Mail\Stockmail;
use Illuminate\Support\Facades\Mail;

use App\Exports\ReportExport;
use App\Exports\ServiceExport;

class ServiceController extends Controller
{
    public function index()
    { 
        if (!Session::get('login')) {
            return redirect('/login');
        }

        $Barang_Service = TokoService::selectRaw('*, count(*) as jml_item')->where('RECID1', 0)->groupBy('docno')->get();
        $Barang_Service_Item = TokoService::all();

        return view('service.terima',  compact('Barang_Service', 'Barang_Service_Item'));
    }

    public function ambil()
    {
        if (!Session::get('login')) {
            return redirect('/login');
        }

        $service  = Transaksi::with(['department', 'detail'])
            ->where([
                ['invoice', 'like', 'SERV/O%'],
                ['status', '=', 2],
                ['pic', 'NIK - NAMA PERSONIL TOKO']
            ])
            ->orderBy('updated_at', 'DESC')->get();
        
        $service_in  = Transaksi::with(['department', 'detail'])
            ->where([
                ['invoice', 'like', 'SERV/I%'],
                ['status', '=', 1]
            ])
            ->orderBy('updated_at', 'DESC')->get();

        $cad  = Cad::where([
                ['recid', '1'],
            ])
            ->orderBy('department_id', 'DESC')->get();
        
        return view('service.status_ambil',  compact('service', 'service_in', 'cad'));
    }

    public function report()
    { 
        if (!Session::get('login')) {
            return redirect('/login');
        }

        $service  = Transaksi::with(['department', 'detail'])
            ->where([
                ['invoice', 'like', 'SERV/O%'],
                ['status', '=', 2]
            ])
            ->orderBy('updated_at', 'DESC')->get();

        $service_in  = Transaksi::with(['department', 'detail'])
            ->where([
                ['invoice', 'like', 'SERV/I%'],
                ['status', '=', 1]
            ])
            ->orderBy('created_at', 'DESC')->get();

        return view('service.index',  compact('service', 'service_in'));
    }

    public function report2()
    {
        if (!Session::get('login')) {
            return redirect('/login');
        }

        $service  = Transaksi::with(['department', 'detail'])
            ->where([
                ['invoice', 'like', 'SERV/O%'],
                ['status', '=', 2],
                ['pic', '!=', 'NIK - NAMA PERSONIL TOKO']
            ])
            ->select('inv_relation')->get();

        $service_sls  = Transaksi::with(['department', 'detail'])
            ->where([
                ['invoice', 'like', 'SERV/O%'],
                ['status', '=', 2],
                ['pic', '!=', 'NIK - NAMA PERSONIL TOKO']
            ])
            ->whereYear('created_at', date('Y'))
            ->whereMonth('created_at', date('m'))
            ->select('inv_relation')->get();

        #SORT BY SERVICE AMBIL
        $service_ambil  = Transaksi::with(['department', 'detail'])
        ->where([
            ['invoice', 'like', 'SERV/O%'],
            ['status', '=', 2],
            ['pic', '!=', 'NIK - NAMA PERSONIL TOKO']
        ])
            ->whereYear('updated_at', date('Y'))
            ->whereMonth('updated_at', date('m'))
            ->orderBy('updated_at', 'DESC')
            ->select('inv_relation')->get();

        $inv_rel = array();
        $inv_rel_sls = array();
        $inv_rel_ambil = array();
                
        foreach ($service as $key => $value) {
            $inv_rel[] = $value->inv_relation;
        }

        foreach ($service_sls as $key => $value) {
            $inv_rel_sls[] = $value->inv_relation;
        }

        foreach ($service_ambil as $key => $value) {
            $inv_rel_ambil[] = $value->inv_relation;
        }

        $service = Transaksi::with(['department', 'detail'])
            ->whereIn('invoice', $inv_rel)
            ->whereYear('created_at', date('Y'))
            ->whereMonth('created_at', date('m'))
            ->orderBy('created_at', 'desc')
            ->get();

        $service_sls = Transaksi::with(['department', 'detail'])
            ->whereIn('invoice', $inv_rel_sls)
            ->get();

        $service_ambil = Transaksi::with(['department', 'detail'])
            ->whereIn('invoice', $inv_rel_ambil)
            ->get();


        $data = array();
        foreach ($service as $key => $masuk) {
            foreach ($masuk->detail as $key => $brg) {
                $barang = $brg->barang->name;
                $kerusakan = $brg->note;
                $sn = $brg->Serial_number;
            }

            $dt_ganti = array();
            $s_out = Transaksi::with(['department', 'detail'])->where('inv_relation', $masuk->invoice)->first();
            foreach ($s_out->detail as $ganti) {
                $gt['spart'] = $ganti->barang->name;
                $gt['qty'] = $ganti->qty;

                array_push($dt_ganti, $gt);
            }

            $dt['id'] = $s_out->id;
            $dt['invoice'] = 'IN ' . substr($masuk->invoice,-10) . ' (' . $masuk->id . ')';
            $dt['invoice2'] = 'OUT ' . substr($s_out->invoice, -10) . ' (' . $s_out->id . ')';
            $dt['dept'] = $masuk->department->kdtk;
            $dt['barang'] = $barang;
            $dt['sn'] = $sn;
            $dt['kerusakan'] = $kerusakan;
            $dt['pembawa'] = $masuk->pic;
            $dt['pengambil'] = $s_out->pic;
            $dt['masuk'] = $masuk->created_at->format('Y-m-d H:i:s');
            $dt['selesai'] = $s_out->created_at->format('Y-m-d H:i:s');
            $dt['keluar'] = $s_out->updated_at->format('Y-m-d H:i:s');
            $dt['penggantian'] = $dt_ganti;
            $dt['lampiran'] = $s_out->lampiran;
            
            array_push($data, $dt);
        }

        
        $data2 = array();
        foreach ($service_sls as $key => $masuk) {
            foreach ($masuk->detail as $key => $brg) {
                $barang = $brg->barang->name;
                $kerusakan = $brg->note;
                $sn = $brg->Serial_number;
            }

            $dt_ganti2 = array();
            $s_out = Transaksi::with(['department', 'detail'])->where('inv_relation', $masuk->invoice)->first();
            foreach ($s_out->detail as $ganti) {
                $gt2['spart'] = $ganti->barang->name;
                $gt2['qty'] = $ganti->qty;

                array_push($dt_ganti2, $gt2);
            }

            $dt2['id'] = $s_out->id;
            $dt2['invoice'] = 'IN ' . substr($masuk->invoice,-10) . ' (' . $masuk->id . ')';
            $dt2['invoice2'] = 'OUT ' . substr($s_out->invoice, -10) . ' (' . $s_out->id . ')';
            $dt2['dept'] = $masuk->department->kdtk;
            $dt2['barang'] = $barang;
            $dt2['sn'] = $sn;
            $dt2['kerusakan'] = $kerusakan;
            $dt2['pembawa'] = $masuk->pic;
            $dt2['pengambil'] = $s_out->pic;
            $dt2['masuk'] = $masuk->created_at->format('Y-m-d H:i:s');
            $dt2['selesai'] = $s_out->created_at->format('Y-m-d H:i:s');
            $dt2['keluar'] = $s_out->updated_at->format('Y-m-d H:i:s');
            $dt2['penggantian'] = $dt_ganti2;
            $dt2['lampiran'] = $s_out->lampiran;
            
            array_push($data2, $dt2);
        }

        $data3 = array();
        foreach ($service_ambil as $key => $masuk) {
            foreach ($masuk->detail as $key => $brg) {
                $barang = $brg->barang->name;
                $kerusakan = $brg->note;
                $sn = $brg->Serial_number;
            }

            $dt_ganti3 = array();
            $s_out = Transaksi::with(['department', 'detail'])->where('inv_relation', $masuk->invoice)->first();
            foreach ($s_out->detail as $ganti) {
                $gt3['spart'] = $ganti->barang->name;
                $gt3['qty'] = $ganti->qty;

                array_push($dt_ganti3, $gt3);
            }

            $dt3['id'] = $s_out->id;
            $dt3['invoice'] = 'IN ' . substr($masuk->invoice, -10) . ' (' . $masuk->id . ')';
            $dt3['invoice2'] = 'OUT ' . substr($s_out->invoice, -10) . ' (' . $s_out->id . ')';
            $dt3['dept'] = $masuk->department->kdtk;
            $dt3['barang'] = $barang;
            $dt3['sn'] = $sn;
            $dt3['kerusakan'] = $kerusakan;
            $dt3['pembawa'] = $masuk->pic;
            $dt3['pengambil'] = $s_out->pic;
            $dt3['masuk'] = $masuk->created_at->format('Y-m-d H:i:s');
            $dt3['selesai'] = $s_out->created_at->format('Y-m-d H:i:s');
            $dt3['keluar'] = $s_out->updated_at->format('Y-m-d H:i:s');
            $dt3['penggantian'] = $dt_ganti3;
            $dt3['lampiran'] = $s_out->lampiran;

            array_push($data3, $dt3);
        }

        $price = array_column($data3, 'keluar');
        array_multisort($price, SORT_DESC, $data3);


        //return response()->json($data2);

         

        return view('service.index',  compact('data', 'data2', 'data3'));
    }

    public function report2_filter(Request $request)
    {
        if (!Session::get('login')) {
            return redirect('/login');
        }

        $PERIODE_FIL = $request->filter['periode'];
        list($YEAR, $MONTH) = explode("-", $PERIODE_FIL, 2);

        #SORT BY SERVICE MASUK
        $service  = Transaksi::with(['department', 'detail'])
            ->where([
                ['invoice', 'like', 'SERV/O%'],
                ['status', '=', 2],
                ['pic', '!=', 'NIK - NAMA PERSONIL TOKO']
            ])->select('inv_relation')->get();

        #SORT BY SERVICE SELESAI
        $service_sls  = Transaksi::with(['department', 'detail'])
            ->where([
                ['invoice', 'like', 'SERV/O%'],
                ['status', '=', 2],
                ['pic', '!=', 'NIK - NAMA PERSONIL TOKO']
            ])
            ->whereYear('created_at', $YEAR)
            ->whereMonth('created_at', $MONTH)
            ->select('inv_relation')->get();

        #SORT BY SERVICE AMBIL
        $service_ambil  = Transaksi::with(['department', 'detail'])
            ->where([
                ['invoice', 'like', 'SERV/O%'],
                ['status', '=', 2],
                ['pic', '!=', 'NIK - NAMA PERSONIL TOKO']
            ])
            ->whereYear('updated_at', $YEAR)
            ->whereMonth('updated_at', $MONTH)
            ->orderBy('updated_at', 'DESC')
            ->select('inv_relation')->get();

        $inv_rel = array();
        $inv_rel_sls = array();
        $inv_rel_ambil = array();

        foreach ($service as $key => $value) {
            $inv_rel[] = $value->inv_relation;
        }

        foreach ($service_sls as $key => $value) {
            $inv_rel_sls[] = $value->inv_relation;
        }

        foreach ($service_ambil as $key => $value) {
            $inv_rel_ambil[] = $value->inv_relation;
        }

        $service = Transaksi::with(['department', 'detail'])
            ->whereIn('invoice', $inv_rel)
            ->whereYear('created_at', $YEAR)
            ->whereMonth('created_at', $MONTH)
            ->get();

        $service_sls = Transaksi::with(['department', 'detail'])
            ->whereIn('invoice', $inv_rel_sls)
            ->get();

        $service_ambil = Transaksi::with(['department', 'detail'])
            ->whereIn('invoice', $inv_rel_ambil)
            ->get();


        $data = array();
        foreach ($service as $key => $masuk) {
            foreach ($masuk->detail as $key => $brg) {
                $barang = $brg->barang->name;
                $kerusakan = $brg->note;
                $sn = $brg->Serial_number;
            }

            $dt_ganti = array();
            $s_out = Transaksi::with(['department', 'detail'])->where('inv_relation', $masuk->invoice)->first();
            foreach ($s_out->detail as $ganti) {
                $gt['spart'] = $ganti->barang->name;
                $gt['qty'] = $ganti->qty;

                array_push($dt_ganti, $gt);
            }

            $dt['id'] = $s_out->id;
            $dt['invoice'] = 'IN ' . substr($masuk->invoice, -10) . ' (' . $masuk->id . ')';
            $dt['invoice2'] = 'OUT ' . substr($s_out->invoice, -10) . ' (' . $s_out->id . ')';
            $dt['dept'] = $masuk->department->kdtk;
            $dt['barang'] = $barang;
            $dt['sn'] = $sn;
            $dt['kerusakan'] = $kerusakan;
            $dt['pembawa'] = $masuk->pic;
            $dt['pengambil'] = $s_out->pic;
            $dt['masuk'] = $masuk->created_at->format('Y-m-d H:i:s');
            $dt['selesai'] = $s_out->created_at->format('Y-m-d H:i:s');
            $dt['keluar'] = $s_out->updated_at->format('Y-m-d H:i:s');
            $dt['penggantian'] = $dt_ganti;
            $dt['lampiran'] = $s_out->lampiran;

            array_push($data, $dt);
        }

        $data2 = array();
        foreach ($service_sls as $key => $masuk) {
            foreach ($masuk->detail as $key => $brg) {
                $barang = $brg->barang->name;
                $kerusakan = $brg->note;
                $sn = $brg->Serial_number;
            }

            $dt_ganti2 = array();
            $s_out = Transaksi::with(['department', 'detail'])->where('inv_relation', $masuk->invoice)->first();
            foreach ($s_out->detail as $ganti) {
                $gt2['spart'] = $ganti->barang->name;
                $gt2['qty'] = $ganti->qty;

                array_push($dt_ganti2, $gt2);
            }

            $dt2['id'] = $s_out->id;
            $dt2['invoice'] = 'IN ' . substr($masuk->invoice, -10) . ' (' . $masuk->id . ')';
            $dt2['invoice2'] = 'OUT ' . substr($s_out->invoice, -10) . ' (' . $s_out->id . ')';
            $dt2['dept'] = $masuk->department->kdtk;
            $dt2['barang'] = $barang;
            $dt2['sn'] = $sn;
            $dt2['kerusakan'] = $kerusakan;
            $dt2['pembawa'] = $masuk->pic;
            $dt2['pengambil'] = $s_out->pic;
            $dt2['masuk'] = $masuk->created_at->format('Y-m-d H:i:s');
            $dt2['selesai'] = $s_out->created_at->format('Y-m-d H:i:s');
            $dt2['keluar'] = $s_out->updated_at->format('Y-m-d H:i:s');
            $dt2['penggantian'] = $dt_ganti2;
            $dt2['lampiran'] = $s_out->lampiran;

            array_push($data2, $dt2);
        }

        $data3 = array();
        foreach ($service_ambil as $key => $masuk) {
            foreach ($masuk->detail as $key => $brg) {
                $barang = $brg->barang->name;
                $kerusakan = $brg->note;
                $sn = $brg->Serial_number;
            }

            $dt_ganti3 = array();
            $s_out = Transaksi::with(['department', 'detail'])->where('inv_relation', $masuk->invoice)->first();
            foreach ($s_out->detail as $ganti) {
                $gt3['spart'] = $ganti->barang->name;
                $gt3['qty'] = $ganti->qty;

                array_push($dt_ganti3, $gt3);
            }

            $dt3['id'] = $s_out->id;
            $dt3['invoice'] = 'IN ' . substr($masuk->invoice, -10) . ' (' . $masuk->id . ')';
            $dt3['invoice2'] = 'OUT ' . substr($s_out->invoice, -10) . ' (' . $s_out->id . ')';
            $dt3['dept'] = $masuk->department->kdtk;
            $dt3['barang'] = $barang;
            $dt3['sn'] = $sn;
            $dt3['kerusakan'] = $kerusakan;
            $dt3['pembawa'] = $masuk->pic;
            $dt3['pengambil'] = $s_out->pic;
            $dt3['masuk'] = $masuk->created_at->format('Y-m-d H:i:s');
            $dt3['selesai'] = $s_out->created_at->format('Y-m-d H:i:s');
            $dt3['keluar'] = $s_out->updated_at->format('Y-m-d H:i:s');
            $dt3['penggantian'] = $dt_ganti3;
            $dt3['lampiran'] = $s_out->lampiran;

            array_push($data3, $dt3);
        }

        #return response()->json($data3);

        return view('service.index',  compact('data', 'data2', 'data3', 'PERIODE_FIL'));
    }

    public function print($id)
    {
        if (!Session::get('login')) {
            return redirect('/login');
        }

        $CONF = Configures::where('rtype', 'CHANNEL')->first();
        $ID_TELE_CHANNEL = $CONF->val;

        $service  = Transaksi::find($id);
        $service_in = Transaksi::where('invoice', $service->inv_relation)->first();

        $encoded_image = explode(",", $service->sign)[0];
        $decoded_image = base64_decode($encoded_image);
        file_put_contents("storage/sign/masuk/" . str_replace('/', '_', $service->invoice) . ".png", $decoded_image);
        $path = 'storage/sign/masuk/' . str_replace('/', '_', $service->invoice) . ".png";

        $encoded_image = explode(",", $service->user->sign)[0];
        $decoded_image = base64_decode($encoded_image);
        file_put_contents("storage/sign/user/" . str_replace('/', '_', $service->user->nik) . ".png", $decoded_image);
        $userSign = 'storage/sign/user/' . str_replace('/', '_', $service->user->nik) . ".png";

        Fpdf::AddPage();

        Fpdf::Image('idm.png', 10, 10.5, 40);

        Fpdf::SetFont('helvetica', 'B', 12);
        Fpdf::SetTextColor(0, 0, 0);
        Fpdf::Cell(40, 8, '', 0, 0, 'L');
        Fpdf::Cell(55, 8, 'PT. INDOMARCO PRISMATAMA', 0, 0, 'L');
        Fpdf::Cell(95, 8, 'SURAT JALAN', 0, 0, 'R');
        Fpdf::Ln(4);
        Fpdf::SetFont('helvetica', '', 9);
        Fpdf::Cell(40, 8, '', 0, 0, 'L');
        Fpdf::Cell(55, 8, 'Komplek Ruko Trikarsa Ekualita Blok 1 No. 1A-1B', 0, 0, 'L');
        Fpdf::Cell(95, 8, 'Tanggal : ' . $service->updated_at, 0, 0, 'R');
        Fpdf::Ln(4);
        Fpdf::SetFont('helvetica', '', 9);
        Fpdf::Cell(40, 8, '', 0, 0, 'L');
        Fpdf::Cell(55, 8, 'Kel. Sadai, Kec. Bengkong Kota Batam (29457) ', 0, 0, 'L');
        Fpdf::Cell(95, 8, 'No : ' . $service->invoice, 0, 0, 'R');

        Fpdf::Line(10, 26, 200, 26);

        Fpdf::Ln(13);
        Fpdf::SetFont('helvetica', 'B', 9);
        Fpdf::Cell(40, 8, 'Kepada Yth :', 0, 0, 'L');
        Fpdf::Ln(5);
        Fpdf::Cell(40, 8, $service->department->kdtk . ' - ' . $service->department->name, 0, 0, 'L');
        Fpdf::Ln(5);
        Fpdf::Cell(40, 8, $service->pic, 0, 0, 'L');


        Fpdf::Ln(10);
        Fpdf::SetFont('helvetica', '', 9);
        Fpdf::MultiCell(190, 5, "Bersama ini kami Kirimkan barang dengan keterangan " . $service->note . ", dengan rincian sebagai berikut :", 0, 'L', 0);

        Fpdf::Ln(3);
        Fpdf::SetFont('helvetica', 'B', 9);
        Fpdf::Cell(10, 7, 'No.', 'TB', 0, 'C');
        Fpdf::Cell(116, 7, 'Nama Barang', 'TB', 0, 'L');
        Fpdf::Cell(12, 7, 'Qty', 'TB', 0, 'C');
        Fpdf::Cell(54, 7, 'Keterangan', 'TB', 0, 'L');

        $no = 0;
        if ($service->inv_relation == $service_in->invoice) {
            Fpdf::Ln(5);
            $no = ++$no;

            Fpdf::SetFont('helvetica', 'B', 9);
            Fpdf::Cell(10, 10, $no . ".", 0, 0, 'R');
            Fpdf::Cell(120, 10, $service->barang->name, 0);

            foreach ($service_in->detail as $detail) {
                if ($detail->barang_id == $service->barang_id) {
                    Fpdf::Cell(12, 10, $detail->qty, 0, 0, 'L');
                }
            }

            if ($service->detail->count() == 0) {
                Fpdf::Cell(50, 10, 'Service Biasa', 0, 0, 'L');
            } else {
                Fpdf::Cell(50, 10, 'Pergantian ' . $service->detail->count() . ' Spare Part', 0, 0, 'L');
            }

            foreach ($service_in->detail as $detail) {
                if ($detail->barang_id == $service->barang_id) {
                    Fpdf::SetFont('helvetica', 'B', 9);
                    Fpdf::Ln(4);
                    Fpdf::Cell(10, 10, '', 0, 0, 'R');
                    Fpdf::Cell(120, 10, 'S/N : ' . $detail->Serial_number, 0);
                    Fpdf::Cell(12, 10, '', 0, 0, 'L');
                    Fpdf::Cell(50, 10, '', 0, 0, 'L');
                }
            }



            foreach ($service->detail as $detail) {
                Fpdf::SetFont('helvetica', '', 9);
                Fpdf::Ln(5);
                Fpdf::Cell(15, 10, '', 0, 0, 'R');
                Fpdf::Cell(115, 10, $detail->barang->name, 0);
                Fpdf::Cell(12, 10, $detail->qty, 0, 0, 'L');
                Fpdf::Cell(50, 10, 'Spare Part Penggantian', 0, 0, 'L');
            }
        }


        Fpdf::Ln(10);
        Fpdf::SetFont('helvetica', '', 9);
        Fpdf::Cell(50, 8, "Penerima,", 0, 0, 'C');
        Fpdf::Cell(50, 8, "", 0, 0, 'C');
        Fpdf::Cell(70, 8, "Pembuat,", 0, 0, 'C');

        Fpdf::Ln(15);
        
        try {
            Fpdf::Cell(100, 10, Fpdf::Image($path, 20, Fpdf::GetY() - 5, 35), 0, 0, 'C');
        } catch (\Throwable $th) {
            //throw $th;
        }

        Fpdf::Cell(100, 10, Fpdf::Image($userSign, 132, Fpdf::GetY() - 5, 35), 0, 0, 'C');

        Fpdf::Ln(15);
        Fpdf::Cell(50, 8, $service->pic, 0, 0, 'C');
        
        
        Fpdf::Cell(50, 8, '', 0, 0, 'C');
        Fpdf::Cell(70, 8, $service->user->nik . ' | ' . $service->user->name, 0, 0, 'C');

        Fpdf::Output();

        $filename = "storage/surat jalan/service/" . str_replace('/', '_', $service->invoice) . '.pdf';
        Fpdf::Output($filename, 'F');

        // if ($service->send_telegram == 0) {

            // $remoteImage = $filename;
            // $filename = str_replace('/', '_', $service->invoice) . '.pdf';

            // Telegram::sendDocument([
                // 'chat_id'   => $ID_TELE_CHANNEL,
                // 'document'  => InputFile::create($remoteImage, $filename),
                // 'caption'   => 'SURAT JALAN SERVICE',
            // ]);

            // // $service->update([
                // // 'send_telegram' => 1
            // // ]);

            // //SEND EMAIL
            // Mail::to($service->department->email)->send(new StockMail($id, "storage/surat jalan/service/" . $filename, 'SURAT JALAN SERVICE'));
        // }
    }

    public function terima($id)
    {
        
        $inv = str_replace('_', '/', $id);
        
        //DATA SERVICE
        $DEPTs = TokoService::where('docno', $inv)->first();
        list($Kode, $nama) = explode('|', $DEPTs->dari);

        //$DEPT = substr($DEPTs->dari, 0, 4);
        $DEPT = str_replace(' ', '', $Kode);
        
        //CARI ID UNTUK USER_ID
        $USER = User::where('nik', session('nik'))->first();
        $USER_ID = $USER->id;

        //CARI ID DEPT
        $DEPART = Department::where('kdtk', $DEPT)->first();
        $DEPART_ID = $DEPART->id;

        //PORTAL_TOKO.SERVICE_MST TO NEW_STOCK.TRANSAKSIS
        $BAR_SERV = TokoService::where('docno', $inv)->first();

        //DD($BAR_SERV->dari);








        $transaksi = Transaksi::create([
            'invoice' => $BAR_SERV->docno,
            'department_id' => $DEPART_ID,
            'pic' => $BAR_SERV->dibuat,
            'user_id' => $USER_ID,
            'status' => 1,
            'note' => 'SERVICE ' . $DEPT
        ]);

        $BAR_SERV_ITEM = TokoService::where('docno', $inv)->get();
        foreach ($BAR_SERV_ITEM as  $item) {
            $BARANG = Barang::where('kode', $item->kode_barang)->first();
            $BARANG_ID = $BARANG->id;

            Transaksi_detail::create([
                'transaksi_id' => $transaksi->id,
                'barang_id' => $BARANG_ID,
                'cad_id' => 0,
                'qty' => $item->qty,
                'serial_number' => $item->sn,
                'cads' => $item->cad,
                'note' => $item->ket,
                'rtype' => 'S'
            ]);

            TokoService::where('docno', $inv)
                ->where('sn', $item->sn)
                ->update(['RECID1' => 1], ['timestamps' => false]);

            $error_telegram = array();
            $text = "Service Masuk\n\n"
                . "<b>Docno:</b> " . $inv . "\n"
                . "<b>Toko:</b> " .  $DEPTs->dari . " \n"
                . "<b>Barang: </b> " . $item->kode_barang . ' - ' . $item->nama_barang . "\n"
                . "<b>S/N: </b> " . $item->sn . "\n" //session('nik')
                . "<b>Masalah: </b> " . $item->ket . "\n"
                . "<b>Penerima: </b> " . session('nik') . " - " . session('nama')  . "\n";
            //. $request->message;

            try {
        //Telegram::sendMessage([
                        // 'chat_id' => 610280902,
                        //  'parse_mode' => 'HTML',
                        //  'text' => $text
                    //   ]);
                    } catch (\Exception $e) {
                        $error_telegram[] = 610280902;
                    }

                    try {

        //Telegram::sendMessage([
        //'chat_id' => -1001454519317.0, //CHANEL DISTRIC EDP -1001454519317.0 
                        // 'parse_mode' => 'HTML',
                        // 'text' => $text
                    // ]);
                    } catch (\Exception $e) {
                        $error_telegram = -1001454519317.0;
                    }
                }






        return redirect()->back()->with(['message_success' => 'Barang Service Diterima EDP', 'message_telegram' => implode(",", $error_telegram)]);
    }

    public function list()
    {
        if (!Session::get('login')) {
            return redirect('/login');
        }

        $LIST = Transaksi_detail::where([['rtype', 'S'], ['approve', '!=', 2], ['ho', '=', 0]])
            ->orderBy('updated_at', 'DESC')
            ->get();

        $brg_filter = array(44, 31, 33, 34, 35, 36, 64);
        #HISTORI PENGGANTIAN 44 (LX), 31 (TM), 33 34 35 36(UPS), 64 (WDCP)
        $rupiah = array();
        foreach ($LIST as $key => $value) {
            if (in_array($value->barang_id, $brg_filter)) {
                $id = $value->id;
                $brg = $value->barang->name;
                $sn = explode('/', str_replace(' ', '', $value->Serial_number));

                $dat = "-";
                foreach($sn as $d){
                    if(str_contains($d, 'C26')){
                        $dat = $d;
                        continue;
                    }
                    $sn = $d;
                }

                $rp['id'] = $id;
                $rp['barang'] = $brg;
                $rp['serial'] = $sn;
                $rp['DAT'] = $dat;

                $inv = array();
                // $his_by_sn = Transaksi_detail::where('Serial_number', 'like', '%' . $sn . '%')->get();
                // foreach ($his_by_sn as $h1) {
                //     $inv[] = $h1->transaksi->invoice;
                // }

                $his_inv_rel = Transaksi::whereIn('inv_relation', $inv)->get();
                $biaya = 0;
                $det = array();
                // foreach($his_inv_rel as $h2){
                //     foreach($h2->detail as $h3){
                //         $det[] = $h3->barang->name . '|' . $h3->barang->acost;
                //         $biaya = $biaya + $h3->barang->acost;
                //     }
                // }


                $rp['detail'] = $det;
                $rp['rupiah'] = $biaya;

                array_push($rupiah, $rp);
            }
        }

        //return response()->json($rupiah);
        
        return view('service.list',  compact('LIST', 'rupiah'));
    }

    public function ho_list()
    {
        if (!Session::get('login')) {
            return redirect('/login');
        }

        $LIST = Transaksi_detail::where([
                ['ho', 1],
                ['approve', '!=', 2]
            ])
            ->whereNull('docno_ho')
            ->get();

        $LIST_KIRIM = Transaksi_detail::where([
                ['ho', 1],
                ['approve', '!=', 2]
            ])
            ->selectRaw('*, count(*) item')
            ->whereNotNull('docno_ho')
            ->groupBy('docno_ho')
            ->get();

        $LIST_BARANG = Transaksi_detail::where([
            ['ho', 1],
            ['approve', '!=', 2]
        ])
            ->whereNotNull('docno_ho')
            ->orderBy('barang_id')
            ->orderBy('Serial_number')
            ->get();
    

        return view('service.ho_list',  compact('LIST', 'LIST_KIRIM', 'LIST_BARANG'));
    }

    public function save($id)
    {
        if (!Session::get('login')) {
            return redirect('/login');
        }

        //CARI ID UNTUK USER_ID
        $USER = User::where('nik', session('nik'))->first();
        $USER_ID = $USER->id;

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

        $faktur_serv_out = 'SERV/O/' . date('Ymd') . '/' . numberToRomanRepresentation(date('y')) . '/' . numberToRomanRepresentation(date('m')) . '/' . strtotime(date('his'));
        $barang = Transaksi_detail::find($id);

        $transaksi = Transaksi::create([
            'invoice' => $faktur_serv_out,
            'inv_relation' => $barang->transaksi->invoice,
            'barang_id' => $barang->barang->id,
            'department_id' => $barang->transaksi->department_id,
            'pic' => 'NIK - NAMA PERSONIL TOKO',
            'user_id' => $USER_ID,
            'status' => 0,
            'note' => 'SELESAI SERVICE',
            'sign' => ''
        ]);

        return redirect(route('service.add', ['id' => $transaksi->id, 'kode' => $id]));
    }

    public function service($id, $kode)
    {
        if (!Session::get('login')) {
            return redirect('/login');
        }

        $transaksi = Transaksi::with(['department', 'detail', 'detail.cad'])
            ->where([
                ['id', $id],
                ['status', 0]
            ])->first();

        $transaksi_ser_in = Transaksi::with(['department', 'detail', 'detail.cad'])
            ->where([
                ['invoice', $transaksi->inv_relation]
            ])->first();

        $barang_service = Transaksi_detail::find($kode);;
        $transaksi = Transaksi::find($id);

        return view('service.service',  compact('transaksi', 'transaksi_ser_in', 'barang_service'));
    }

    public function update(Request $request, $id)
    {
        //VALIDASI
        $this->validate($request, [
            'barang_id' => 'required|exists:barangs,id',
            'qty' => 'required|integer'
            //'note' => 'required|string'
        ]);

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
                    'cad_id' => 0,
                    'qty' => $request->qty,
                    'serial_number' => '',
                    'note' => 'Ganti Spare Part',
                    'rtype' => 'O'
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

    public function selesai(Request $request, $id, $kode)
    {
        $NOTE2 = $request->note_tambahan;
        $transaksi = Transaksi::find($id);
        $transaksi->update([
            'status' => 1,
            'note2' => $NOTE2
        ]);

        $transaksi_ser_in = Transaksi::where('invoice', $transaksi->inv_relation)->first();
        $transaksi_ser_detail = $transaksi_ser_in->detail()->where('id', $kode)->first();
        $transaksi_ser_detail->update([
            'ho' => $transaksi_ser_detail->ho + 1
        ]);

        if ($request->ada_pengg == 1) {
            $transaksi_detail = $transaksi->detail()->where('transaksi_id', $id)->first();
            $transaksi_detail->update([
                'approve' => 5
            ]);

            $transaksi_ser_in = Transaksi::where('invoice', $transaksi->inv_relation)->first();
            $transaksi_ser_detail = $transaksi_ser_in->detail()->where('id', $kode)->first();
            $transaksi_ser_detail->update([
                'approve' => 5
            ]);
        }

        return redirect('/service/list')->with(['message_success' => 'Transaksi Barang Keluar Berhasil Di Buat, Silahkan Hubungi Atasan Anda Untuk Di Setujui']);
    }

    public function ambil_selesai(Request $request)
    {

        $transaksi = Transaksi::find($request->invoice);
        $transaksi->update([
            'pic' => $request->personil
        ]);

        $STATUS_TOKO = TokoService::where([['docno', $transaksi->inv_relation], ['kode_barang', $transaksi->barang->kode]]);
        $STATUS_TOKO->update([
            'RECID3' => 1
        ]);

        return redirect('/service/ambil')->with(['message_success' => $transaksi->barang->name . ' Telah Di Ambil. Surat Jalan Bisa Di Cetak']);
    }

    public function ho($id)
    {
        $transaksi = Transaksi_detail::find($id);
        $transaksi->update([
            'ho' => 1
        ]);

        return redirect()->back()->with(['message_success' => $transaksi->barang->name . ' DI Kirim Ke HO.']);
    }

    public function sign($id)
    {
        if (!Session::get('login')) {
            return redirect('/login');
        }

        $transaksi = Transaksi::with(['department', 'detail', 'detail.barang'])
            ->where([
                ['id', $id]
            ])->first();

        return view('service.sign', compact('transaksi'));
    }

    public function signOK($id)
    {
        $transaksi = Transaksi::find($id);
        //$namasign = str_replace('/', '_', $transaksi->invoice);

        $data_uri = $_POST['imageData'];

        // $encoded_image = explode(",", $data_uri)[0];
        // $decoded_image = base64_decode($encoded_image);
        // file_put_contents("storage/sign/masuk/" . $namasign . ".png", $decoded_image);

        $transaksi->update([
            'sign' => $data_uri
        ]);

        return redirect('/report_service')->with(['message_success' => 'Tanda Tangan Berhasil']);
    }

    public function generateDocno()
    {
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

        $faktur = 'SER-HO/' . date('Ymd') . '/' . numberToRomanRepresentation(date('y')) . '/' . numberToRomanRepresentation(date('m')) . '/' . strtotime(date('his'));

        $transaksi = Transaksi_detail::where([
                ['ho', 1],
                ['approve', '!=', 2]
            ])
            ->whereNull('docno_ho')
            ->update(
                ['docno_ho' => $faktur]
            );
        
        return redirect()->back()->with(['message_success' => 'Generate Docno HO Berhasil.']);
    }

    public function docnoDetail()
    {
        if (!Session::get('login')) {
            return redirect('/login');
        }

        $DocnoHO = $_GET['docno'];

        $HO_DETAIL = Transaksi_detail::where([
                ['docno_ho', $DocnoHO],
                ['approve', 0]
            ])
            ->get();

        return view('service.ho_detail', compact('HO_DETAIL'));
    }

    public function docnoCetak()
    {
        if (!Session::get('login')) {
            return redirect('/login');
        }
        $DocnoHO = $_GET['docno'];

        $CONF = Configures::where('rtype', 'CHANNEL')->first();
        $ID_TELE_CHANNEL = $CONF->val;

        $service  = Transaksi_detail::where([
            ['docno_ho', $DocnoHO]
        ])->get();

        // $encoded_image = explode(",", $service->sign)[0];
        // $decoded_image = base64_decode($encoded_image);
        // file_put_contents("storage/sign/masuk/" . str_replace('/', '_', $service->invoice) . ".png", $decoded_image);
        // $path = 'storage/sign/masuk/' . str_replace('/', '_', $service->invoice) . ".png";

        // $encoded_image = explode(",", $service->user->sign)[0];
        // $decoded_image = base64_decode($encoded_image);
        // file_put_contents("storage/sign/user/" . str_replace('/', '_', $service->user->nik) . ".png", $decoded_image);
        // $userSign = 'storage/sign/user/' . str_replace('/', '_', $service->user->nik) . ".png";

        Fpdf::AddPage();

        Fpdf::Image('idm.png', 10, 10.5, 40);

        Fpdf::SetFont('helvetica', 'B', 12);
        Fpdf::SetTextColor(0, 0, 0);
        Fpdf::Cell(40, 8, '', 0, 0, 'L');
        Fpdf::Cell(55, 8, 'PT. INDOMARCO PRISMATAMA', 0, 0, 'L');
        Fpdf::Cell(95, 8, 'SURAT JALAN', 0, 0, 'R');
        Fpdf::Ln(4);
        Fpdf::SetFont('helvetica', '', 9);
        Fpdf::Cell(40, 8, '', 0, 0, 'L');
        Fpdf::Cell(55, 8, 'Komplek Ruko Trikarsa Ekualita Blok 1 No. 1A-1B', 0, 0, 'L');
        Fpdf::Cell(95, 8, 'Tanggal Cetak: ' . date('d-m-Y H:i:s'), 0, 0, 'R');
        Fpdf::Ln(4);
        Fpdf::SetFont('helvetica', '', 9);
        Fpdf::Cell(40, 8, '', 0, 0, 'L');
        Fpdf::Cell(55, 8, 'Kel. Sadai, Kec. Bengkong Kota Batam (29457) ', 0, 0, 'L');
        Fpdf::Cell(95, 8, 'No : ' . $DocnoHO, 0, 0, 'R');

        Fpdf::Line(10, 26, 200, 26);

        Fpdf::Ln(13);
        Fpdf::SetFont('helvetica', 'B', 9);
        Fpdf::Cell(40, 8, 'Kepada Yth :', 0, 0, 'L');
        Fpdf::Ln(5);
        Fpdf::Cell(40, 8, 'HO - HEAD OFFICE', 0, 0, 'L');


        Fpdf::Ln(10);
        Fpdf::SetFont('helvetica', '', 9);
        Fpdf::MultiCell(190, 5, "Bersama ini kami Kirimkan barang dengan keterangan SERVICE KE HO, dengan rincian sebagai berikut :", 0, 'L', 0);

        Fpdf::Ln(3);
        Fpdf::SetFont('helvetica', 'B', 9);
        Fpdf::Cell(10, 7, 'No.', 'TB', 0, 'C');
        Fpdf::Cell(30, 7, 'Nama Barang', 'TB', 0, 'L');
        Fpdf::Cell(30, 7, 'Serial Number', 'TB', 0, 'L');
        Fpdf::Cell(10, 7, 'Qty', 'TB', 0, 'R');
        Fpdf::Cell(65, 7, 'Toko', 'TB', 0, 'L');
        Fpdf::Cell(40, 7, 'Harga', 'TB', 0, 'L');

        $no = 0;
        
        

        foreach ($service as $detail) {
            Fpdf::Ln(5);
            Fpdf::SetFont('helvetica', '', 9);
            Fpdf::Cell(10, 10, ++$no . ".", 0, 0, 'R');
            Fpdf::Cell(30, 10, $detail->barang->name, 0);
            Fpdf::Cell(30, 10, $detail->Serial_number, 0);
            Fpdf::Cell(10, 10, $detail->qty, 0, 0, 'R');
            Fpdf::Cell(65, 10, $detail->transaksi->department->kdtk . ' :: ' . $detail->transaksi->department->name, 0);
            Fpdf::Cell(40, 10, 'Rp. 40.0000', 0);
        }

        Fpdf::Ln(25);
        Fpdf::SetFont('helvetica', '', 9);
        Fpdf::Cell(50, 8, "Mengetahui,", 0, 0, 'C');
        Fpdf::Cell(50, 8, "", 0, 0, 'C');
        Fpdf::Cell(70, 8, "Pembuat,", 0, 0, 'C');

        Fpdf::Ln(15);
        //Fpdf::Cell(100, 10, Fpdf::Image($path, 20, Fpdf::GetY() - 5, 35), 0, 0, 'C');
        //Fpdf::Cell(100, 10, Fpdf::Image($userSign, 132, Fpdf::GetY() - 5, 35), 0, 0, 'C');

        Fpdf::Ln(15);
        Fpdf::Cell(50, 8, '2013118488 | HERLAMBANG R P', 0, 0, 'C');
        Fpdf::Cell(50, 8, '', 0, 0, 'C');
        Fpdf::Cell(70, 8, Session('nik') . ' | ' . Session('nama') , 0, 0, 'C');

        Fpdf::Output();

        // $filename = "storage/surat jalan/service/" . str_replace('/', '_', $service->invoice) . '.pdf';
        // Fpdf::Output($filename, 'F');

        // if ($service->send_telegram == 0) {

        //     $remoteImage = $filename;
        //     $filename = str_replace('/', '_', $service->invoice) . '.pdf';

        //     Telegram::sendDocument([
        //         'chat_id'   => $ID_TELE_CHANNEL,
        //         'document'  => InputFile::create($remoteImage, $filename),
        //         'caption'   => 'SURAT JALAN SERVICE',
        //     ]);

        //     $service->update([
        //         'send_telegram' => 1
        //     ]);

        //     //SEND EMAIL
        //     Mail::to($service->department->email)->send(new StockMail($id, "storage/surat jalan/service/" . $filename, 'SURAT JALAN SERVICE'));
        // }
    }

    public function export_excel()
    {

        return Excel::download(new ReportExport, 'Service.xlsx');
    }

    public function serviceExport()
    {
        return Excel::download(new ServiceExport, 'ServiceExport.xlsx');
    }

    public function note(Request $request)
    {   
        $id = $request->invoice;
        $note = $request->note;
        
        $transaksi = Transaksi_detail::find($id);
        $transaksi->update([
            'note_servicer' => $note,
            'noter' => session('nik') . '|' . session('nama'),
            'notetime' => Carbon::now()
        ]);

        return redirect()->back()->with(['message_success' => 'Note Sudah Di Tambahkan']);
    }

    public function proses_upload(Request $request)
    {
        if (!Session::get('login')) {
            return redirect('/login');
        }

        $this->validate($request, [
            'attachmentName'    => 'required',
            'id_transaksi'      => 'required',
        ]);

        // menyimpan data file yang diupload ke variabel $file
        $file = $request->file('attachmentName');
        $id = $request->id_transaksi;

        $transaksi = Transaksi::find($id);
        $invoice =  str_replace('/', '_', $transaksi->invoice);

        // nama file
        echo 'File Name: ' . $file->getClientOriginalName();
        echo '<br>';

        // ekstensi file
        echo 'File Extension: ' . $file->getClientOriginalExtension();
        echo '<br>';

        // real path
        echo 'File Real Path: ' . $file->getRealPath();
        echo '<br>';

        // ukuran file
        echo 'File Size: ' . $file->getSize();
        echo '<br>';

        // tipe mime
        echo 'File Mime Type: ' . $file->getMimeType();

        // isi dengan nama folder tempat kemana file diupload
        $tujuan_upload = 'storage/photo_sj';

        // upload file
        $file->move($tujuan_upload, $invoice . '.' . $file->getClientOriginalExtension());

        //UPD DI DATABASE
        $transaksi->update([
            'lampiran' => $invoice . '.' . $file->getClientOriginalExtension()
        ]);

        return redirect()->back()->with(['message_success' => 'Lampiran Berhasil Di Tambahkan']);
    }
}