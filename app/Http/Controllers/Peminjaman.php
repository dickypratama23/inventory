<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use PDF;
use Fpdf;
use Excel;
use Telegram\Bot\FileUpload\InputFile;
use Telegram;

use App\Department;
use App\User;
use App\Transaksi;
use App\Barang;
use App\Transaksi_detail;
use App\Stock;
use App\Cad;
use App\TokoPinjam;
use App\Configures;

use App\Mail\Stockmail;
use Illuminate\Support\Facades\Mail;

use App\Exports\PeminjamanExport;

class Peminjaman extends Controller
{
    public function index()
    {
        if (!Session::get('login')) {
            return redirect('/login');
        }

        $transaksi  = Transaksi::with(['department', 'detail'])
            ->where([
                ['invoice', 'like', 'LEN%'],
                ['status', '!=', 0]
            ])
            ->orderBy('created_at', 'DESC')->get();
        return view('peminjaman.index',  compact('transaksi'));
    }

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

        $faktur = 'LEN/' . date('Ymd') . '/' . numberToRomanRepresentation(date('y')) . '/' . numberToRomanRepresentation(date('m')) . '/' . strtotime(date('his'));
        $departments = Department::orderBy('created_at', 'DESC')->get();
        $users = User::orderBy('created_at', 'DESC')->get();

        return view('peminjaman.create', compact('departments', 'users', 'faktur'));
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

            return redirect(route('peminjaman.add', ['id' => $transaksi->id]));
        } catch (\Exception $e) {
            return redirect()->back()->with(['message_error' => $e->getMessage()]);
        }
    }

    public function add($id)
    {
        if (!Session::get('login')) {
            return redirect('/login');
        }

        $transaksi = Transaksi::with(['department', 'detail', 'detail.cad'])
            ->where([
                ['id', $id],
                ['status', 0]
            ])->first();


        $cads = Cad::where('department_id', $transaksi->department_id)->orderBy('kode', 'ASC')->get();

        return view('peminjaman.add', compact('transaksi', 'cads'));
    }

    public function update(Request $request, $id)
    {
		
        //VALIDASI
        $this->validate($request, [
            'barang_id' => 'required|integer',
            'qty' => 'required|integer'
            //'note' => 'required|string'
        ]);
	

        try {
            //SELECT DARI TABLE invoices BERDASARKAN ID
            $transaksi = Transaksi::find($id);

            //SELECT DARI TABLE products BERDASARKAN ID
            $barang = Cad::find($request->barang_id);

            //SELECT DARI TABLE invoice_details BERDASARKAN product_id & invoice_id
            $transaksi_detail = $transaksi->detail()->where('cad_id', $barang->id)->first();

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
                    'barang_id' => 0,
                    'cad_id' => $request->barang_id,
                    'qty' => $request->qty,
                    'serial_number' => $request->serial_number,
                    'note' => $request->note,
                    'rtype' => 'L'
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
        $transaksi_detail = $transaksi->detail()->get();

        $DOCNO = $transaksi->invoice;
        $TANGGAL = $transaksi->created_at;
        $PEMINJAM = $transaksi->pic;
        $KDTK = $transaksi->department->kdtk;
        $NAMATOKO = $transaksi->department->name;


        foreach ($transaksi_detail as  $cad_id) {
            $KODE_BARANG = substr($cad_id->cad->kode, 4, 7);
            $NAMA_BARANG = $cad_id->cad->name;
            $SN_BARANG = $cad_id->cad->mac;

            TokoPinjam::create([
                'docno' => $DOCNO,
                'tgl' => $TANGGAL,
                'kode_barang' => $KODE_BARANG,
                'nama_barang' => $NAMA_BARANG,
                'sn' => $SN_BARANG,
                'peminjam' => $PEMINJAM,
                'kdtk' => $KDTK
            ]);

            $error_telegram = array();
            $text = "Peminjaman Cadangan EDP\n\n"
                . "<b>Docno:</b> " . $DOCNO . "\n"
                . "<b>Toko:</b> " .  $KDTK . ' :: ' . $NAMATOKO . " \n"
                . "<b>Barang: </b> " . $KODE_BARANG . ' :: ' . $cad_id->cad->name . "\n"
                . "<b>S/N: </b> " . $SN_BARANG . "\n" //session('nik')
                . "<b>Peminjam: </b> " . $transaksi->pic . "\n";
            //. "<b>Penerima: </b> " . session('nik') . " - " . session('nama')  . "\n";

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

            //     // Telegram::sendMessage([
            //     //     'chat_id' => -300800445, //CHANEL DISTRIC EDP -300800445 
            //     //     'parse_mode' => 'HTML',
            //     //     'text' => $text
            //     // ]);
            // } catch (\Exception $e) {
            //     $error_telegram = -300800445;
            // }

            $user = User::where('nik', session('nik'))->first();
            $Barang_cad = Cad::where('id', $cad_id->cad_id)
                ->update([
                    'recid' => 1,
                    'department_id' => $transaksi->department_id,
                    'user_id' => $user->id
                ]);
        }

        $transaksi->update([
            'status' => 1
        ]);

        return redirect('/peminjaman/new')->with(['message_success' => 'Transaksi Peminjaman Berhasil Di Buat, Silahkan Hubungi Atasan Anda Untuk Di Setujui']);;
    }

    public function generatePeminjaman($id)
    {
        
        if (!Session::get('login')) {
            return redirect('/login');
        }

        $CONF = Configures::where('rtype', 'CHANNEL')->first();
        $ID_TELE_CHANNEL = $CONF->val;

        $transaksi = Transaksi::with(['department', 'user', 'detail', 'detail.barang'])->find($id);
        $encoded_image = explode(",", $transaksi->sign)[0];
        $decoded_image = base64_decode($encoded_image);
        file_put_contents("storage/sign/masuk/" . str_replace('/', '_', $transaksi->invoice) . ".png", $decoded_image);
        $path = 'storage/sign/masuk/' . str_replace('/', '_', $transaksi->invoice) . ".png";

        $encoded_image = explode(",", $transaksi->user->sign)[0];
        $decoded_image = base64_decode($encoded_image);
        file_put_contents("storage/sign/user/" . str_replace('/', '_', $transaksi->user->nik) . ".png", $decoded_image);
        $userSign = 'storage/sign/user/' . str_replace('/', '_', $transaksi->user->nik) . ".png";

        Fpdf::AddPage();

        Fpdf::Image('idm.png', 10, 10.5, 40);

        Fpdf::SetFont('helvetica', 'B', 12);
        Fpdf::SetTextColor(0, 0, 0);
        Fpdf::Cell(40, 8, '', 0, 0, 'L');
        Fpdf::Cell(55, 8, 'PT. INDOMARCO PRISMATAMA', 0, 0, 'L');
        Fpdf::Cell(95, 8, 'PEMINJAMAN', 0, 0, 'R');
        Fpdf::Ln(4);
        Fpdf::SetFont('helvetica', '', 9);
        Fpdf::Cell(40, 8, '', 0, 0, 'L');
        Fpdf::Cell(55, 8, 'Komplek Ruko Trikarsa Ekualita Blok 1 No. 1A-1B', 0, 0, 'L');
        Fpdf::Cell(95, 8, 'Tanggal : ' . $transaksi->created_at, 0, 0, 'R');
        Fpdf::Ln(4);
        Fpdf::SetFont('helvetica', '', 9);
        Fpdf::Cell(40, 8, '', 0, 0, 'L');
        Fpdf::Cell(55, 8, 'Kel. Sadai, Kec. Bengkong Kota Batam (29457) ', 0, 0, 'L');
        Fpdf::Cell(95, 8, 'No : ' . $transaksi->invoice, 0, 0, 'R');

        Fpdf::Line(10, 26, 200, 26);

        Fpdf::Ln(13);
        Fpdf::SetFont('helvetica', 'B', 9);
        Fpdf::Cell(40, 8, 'Kepada Yth :', 0, 0, 'L');
        Fpdf::Ln(5);
        Fpdf::Cell(40, 8, $transaksi->department->kdtk . ' - ' . $transaksi->department->name, 0, 0, 'L');
        Fpdf::Ln(5);
        Fpdf::Cell(40, 8, $transaksi->pic, 0, 0, 'L');


        Fpdf::Ln(10);
        Fpdf::SetFont('helvetica', '', 9);
        Fpdf::MultiCell(190, 5, "Bersama ini kami Kirimkan barang dengan keterangan " . $transaksi->note . ", dengan rincian sebagai berikut :", 0, 'L', 0);

        Fpdf::Ln(3);
        Fpdf::SetFont('helvetica', 'B', 9);
        Fpdf::Cell(10, 7, 'No.', 'TB', 0, 'C');
        Fpdf::Cell(116, 7, 'Nama Barang', 'TB', 0, 'L');
        Fpdf::Cell(12, 7, 'Qty', 'TB', 0, 'C');
        Fpdf::Cell(54, 7, 'Keterangan', 'TB', 0, 'L');

        $no = 0;
        foreach ($transaksi->detail as $detail) {
            Fpdf::Ln(5);
            $no = ++$no;

            if ($no == $transaksi->detail->count()) {
                Fpdf::SetFont('helvetica', 'B', 9);
                Fpdf::Cell(10, 10, $no . ".", 0, 0, 'R');
                Fpdf::Cell(120, 10, $detail->cad->name, 0);
                Fpdf::Cell(12, 10, $detail->qty, 0, 0, 'L');
                Fpdf::Cell(50, 10, '', 0, 0, 'L');

                Fpdf::Ln(4);

                Fpdf::SetFont('helvetica', '', 9);
                Fpdf::Cell(10, 10, '', '0', 0, 'R');
                Fpdf::Cell(120, 10, 'S/N : ' . $detail->Serial_number, '0');
                Fpdf::Cell(12, 10, '', '0', 0, 'L');
                Fpdf::Cell(50, 10, '', '0', 0, 'L');
            } else {
                
                Fpdf::SetFont('helvetica', 'B', 9);
                Fpdf::Cell(10, 10, $no . ".", 0, 0, 'R');
                Fpdf::Cell(120, 10, $detail->cad->name, 0);
                Fpdf::Cell(12, 10, $detail->qty, 0, 0, 'L');
                Fpdf::Cell(50, 10, '', 0, 0, 'L');

                Fpdf::Ln(4);

                Fpdf::SetFont('helvetica', '', 9);
                Fpdf::Cell(10, 10, '', 0, 0, 'R');
                Fpdf::Cell(120, 10, 'S/N : ' . $detail->Serial_number, 0);
                Fpdf::Cell(12, 10, '', 0, 0, 'L');
                Fpdf::Cell(50, 10, '', 0, 0, 'L');
            }
        }

        Fpdf::Ln(15);
        Fpdf::SetFont('helvetica', '', 9);
        Fpdf::Cell(50, 8, "Penerima,", 0, 0, 'C');
        Fpdf::Cell(50, 8, "", 0, 0, 'C');
        Fpdf::Cell(70, 8, "Pembuat,", 0, 0, 'C');

        Fpdf::Ln(15);
        Fpdf::Cell(100, 10, Fpdf::Image($path, 20, Fpdf::GetY() - 5, 35), 0, 0, 'C');
        Fpdf::Cell(100, 10, Fpdf::Image($userSign, 132, Fpdf::GetY() - 5, 35), 0, 0, 'C');

        Fpdf::Ln(15);
        Fpdf::Cell(50, 8, $transaksi->pic, 0, 0, 'C');
        Fpdf::Cell(50, 8, '', 0, 0, 'C');
        Fpdf::Cell(70, 8, $transaksi->user->nik . ' | ' . $transaksi->user->name, 0, 0, 'C');

        Fpdf::Output();

        $filename = "storage/surat jalan/peminjaman/" . str_replace('/', '_', $transaksi->invoice) . '.pdf';
        Fpdf::Output($filename, 'F');

        if ($transaksi->send_telegram == 0) {

            $remoteImage = $filename;
            $filename = str_replace('/', '_', $transaksi->invoice) . '.pdf';

            Telegram::sendDocument([
                'chat_id'   => $ID_TELE_CHANNEL,
                'document'  => InputFile::create($remoteImage, $filename),
                'caption'   => 'SURAT JALAN PEMINJAMAN',
            ]);

            $transaksi->update([
                'send_telegram' => 1
            ]);

            //SEND EMAIL
            Mail::to($transaksi->department->email)->send(new StockMail($id, "storage/surat jalan/peminjaman/" . $filename, 'PEMINJAMAN'));
        }
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

        return view('peminjaman.sign', compact('transaksi'));
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

        return redirect('/peminjaman')->with(['message_success' => 'Tanda Tangan Berhasil']);
    }

    public function export_excel()
    {
        return Excel::download(new PeminjamanExport, 'Peminjaman.xlsx');
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

    public function bigData(Request $request)
    {
        #GENERAL PARAMETER
        $draw = $request->draw; //HALAMAN PAGING
        $row = $request->start; //DATA KE DATABASE INFO DARI MANA DATA YANG HARUS DI AMBIL
        $rowperpage = $request->length; //JUMLAH BARIS YANG AKAN DI TAMPILKAN
        $columnIndex = $request->order[0]['column']; //KOLOM INDEX YANG DI INISIALIKAN UNTUK AMBIL NAMA KOLOMNYA
        $columnName = $request->columns[2]['data']; //KOLOM YANG DIGUNAKAN UNTUK ORDER DI DATABASE
        $columnSortOrder = $request->order[0]['dir']; //TIPE SORT KOLOM DI DATABASE
        $searchValue = $request->search['value']; //PENCARIAN YANG DILAKUKAN PADA FIELD SEARCH DATATABLESNYA

        ## Search 
        $searchQueryToko = null;
        if ($searchValue != '') {
            $searchQueryToko = [
                ['kdtk', 'like', '%' . $searchValue . '%']
            ];
        }
        $searchQueryToko = [
            ['invoice', 'like', '%' . $searchValue . '%']
        ];
        

        ## Total number of records without filtering
        $sel = Transaksi::with(['department', 'detail'])
            ->where([
                ['invoice', 'like', 'LEN%'],
                ['status', '!=', 0]
            ])
            ->orderBy('created_at', 'DESC')->get();

        $totalRecords = $sel->count();

        ## Total number of record with filtering
        $sel = Transaksi::with(['department', 'detail'])->where($searchQueryToko)
            ->where([
                ['invoice', 'like', 'LEN%'],
                ['status', '!=', 0]
            ])
            ->orderBy('created_at', 'DESC')->first();
        $totalRecordwithFilter = $sel->count();

        dd($sel);

        ## Fetch records
        $empQuery = Transaksi::with(['department', 'detail'])->where($searchQueryToko)
        ->where([
            ['invoice', 'like', 'LEN%'],
            ['status', '!=', 0]
        ])
        ->orderBy($columnName, $columnSortOrder)
        ->skip($row)
        ->take($rowperpage)
        ->get();
        $data = array();

        foreach ($empQuery as $key => $row) {
            $data[] = array(
                "kdtk" => $row['kdtk'],
                "tanggal" => $row['tanggal'],
                "shift" => $row['shift'],
                "nik" => $row['addid'],
            );
        }

        ## Response WAJIB SEPERTI INI
        $response = array(
            "draw" => intval($draw),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalRecordwithFilter,
            "data" => $data
        );

        echo json_encode($response);


    }
}
