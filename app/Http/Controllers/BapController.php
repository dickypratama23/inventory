<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use PDF;
use Fpdf;
use Telegram;
use Excel;
use Telegram\Bot\FileUpload\InputFile;

use App\Department;
use App\User;
use App\Transaksi;
use App\Barang;
use App\Transaksi_detail;
use App\Stock;
use App\Configures;

use App\Mail\Stockmail;
use Illuminate\Support\Facades\Mail;

class BapController extends Controller
{
    public function index()
    {
        if (!Session::get('login')) {
            return redirect('/login');
        }

        $bap  = Transaksi::with(['department', 'detail'])
            ->where([
                ['invoice', 'like', 'BAP%'],
                ['status', '=', 2]
            ])
            ->orderBy('updated_at', 'DESC')->get();

        $allbap = Transaksi_detail::where('rtype', 'b')->get();

        return view('bap.index',  compact('bap', 'allbap'));
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

        $faktur = 'BAP/' . date('Ymd') . '/' . numberToRomanRepresentation(date('y')) . '/' . numberToRomanRepresentation(date('m')) . '/' . strtotime(date('his'));
        $departments = Department::orderBy('created_at', 'DESC')->get();
        $users = User::orderBy('created_at', 'DESC')->get();

        return view('bap.create', compact('departments', 'users', 'faktur'));
    }

    public function save(Request $request)
    {
        if (!Session::get('login')) {
            return redirect('/login');
        }

        //VALIDASI
        $this->validate($request, [
            'invoice' => 'required',
            'dari' => 'required',
            'pic' => 'required|string',
            'keterangan' => 'required|string',
            'pembuat' => 'required'
        ]);

        try {
            //MENYIMPAN DATA KE TABLE TRANSAKSIS
            $transaksi = Transaksi::create([
                'invoice' => $request->invoice,
                'department_id' => $request->dari,
                'pic' => $request->pic,
                'user_id' => $request->pembuat,
                'status' => 0,
                'note' => $request->keterangan,
                'sign' => ''
            ]);

            return redirect(route('bap.add', ['id' => $transaksi->id]));
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
        return view('bap.add', compact('transaksi', 'barang'));
    }

    public function update(Request $request, $id)
    {

        //VALIDASI
        $this->validate($request, [
            'barang_id' => 'required|exists:barangs,id',
            'qty' => 'required|integer'
        ]);

        try {
            //SELECT DARI TABLE invoices BERDASARKAN ID
            $transaksi = Transaksi::find($id);

            //SELECT DARI TABLE products BERDASARKAN ID
            $barang = Barang::find($request->barang_id);

            //SELECT DARI TABLE invoice_details BERDASARKAN product_id & invoice_id

            if ($barang->mac == 1) {
                //JIKA DATANYA SAMA
                $transaksi_detail = $transaksi->detail()
                    ->where([
                        ['barang_id', $barang->id],
                        ['serial_number', $request->serial_number]
                    ])
                    ->first();
                if ($transaksi_detail) {
                    return redirect()->back()->with(['message_error' => 'Product Yang Ditambahkan Serial Number / MAC Sama']);
                } else {
                    //JIKA TIDAK SAMA MAKA DITAMBAHKAN RECORD BARU
                    Transaksi_detail::create([
                        'transaksi_id' => $transaksi->id,
                        'barang_id' => $request->barang_id,
                        'qty' => $request->qty,
                        'serial_number' => $request->serial_number,
                        'note' => $request->note,
                        'rtype' => 'B'
                    ]);
                }
            } else {

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
                        'rtype' => 'B'
                    ]);
                }
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

        return redirect('/bap/new')->with(['message_success' => 'Transaksi BAP Berhasil Di Buat, Silahkan Hubungi Atasan Anda Untuk Di Setujui']);
    }

    public function detail($id)
    {
        if (!Session::get('login')) {
            return redirect('/login');
        }

        $bap  = Transaksi::with(['department', 'detail'])
            ->where('id', $id)
            ->orderBy('updated_at', 'DESC')->first();

        return view('bap.detail',  compact('bap'));

    }

    public function generateBap($id)
    {
        if (!Session::get('login')) {
            return redirect('/login');
        }

        $CONF = Configures::where('rtype', 'CHANNEL')->first();
        $ID_TELE_CHANNEL = $CONF->val;

        $transaksi = Transaksi::with(['department', 'user', 'detail', 'detail.barang'])->find($id);
        $encoded_image = explode(",", $transaksi->sign)[0];
        $decoded_image = base64_decode($encoded_image);
        file_put_contents("storage/sign/bap/" . str_replace('/', '_', $transaksi->invoice) . ".png", $decoded_image);
        $path = 'storage/sign/bap/' . str_replace('/', '_', $transaksi->invoice) . ".png";

        $encoded_image = explode(",", $transaksi->user->sign)[0];
        $decoded_image = base64_decode($encoded_image);
        file_put_contents("storage/sign/user/" . str_replace('/', '_', $transaksi->user->nik) . ".png", $decoded_image);
        $userSign = 'storage/sign/user/' . str_replace('/', '_', $transaksi->user->nik) . ".png";
        //dd($userSign);
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
        Fpdf::Cell(95, 8, 'Tanggal : ' . $transaksi->created_at, 0, 0, 'R');
        Fpdf::Ln(4);
        Fpdf::SetFont('helvetica', '', 9);
        Fpdf::Cell(40, 8, '', 0, 0, 'L');
        Fpdf::Cell(55, 8, 'Kel. Sadai, Kec. Bengkong Kota Batam (29457) ', 0, 0, 'L');
        Fpdf::Cell(95, 8, 'No : ' . $transaksi->invoice, 0, 0, 'R');

        Fpdf::Line(10, 26, 200, 26);

        Fpdf::Ln(13);
        Fpdf::SetFont('helvetica', '', 10);
        Fpdf::Cell(40, 8, 'Kepada Yth :', 0, 0, 'L');
        Fpdf::Ln(5);
        Fpdf::Cell(40, 8, $transaksi->department->kdtk . ' - ' . $transaksi->department->name, 0, 0, 'L');
        Fpdf::Ln(5);
        Fpdf::Cell(40, 8, $transaksi->pic, 0, 0, 'L');


        Fpdf::Ln(10);
        Fpdf::SetFont('helvetica', '', 11);
        Fpdf::MultiCell(190, 5, "Bersama ini kami Kirimkan barang dengan keterangan " . $transaksi->note . ", dengan rincian sebagai berikut :", 0, 'L', 0);

        Fpdf::Ln(3);
        Fpdf::SetFont('helvetica', '', 11);
        Fpdf::Cell(10, 7, 'NO.', 'TB', 0, 'C');
        Fpdf::Cell(116, 7, 'NAMA BARANG', 'TB', 0, 'L');
        Fpdf::Cell(12, 7, 'QTY', 'TB', 0, 'C');
        Fpdf::Cell(54, 7, 'SATUAN', 'TB', 0, 'L');

        $no = 0;
        foreach ($transaksi->detail as $detail) {
            Fpdf::Ln(6);
            $no = ++$no;

            if ($no == $transaksi->detail->count()) {
                Fpdf::SetFont('helvetica', '', 11);
                Fpdf::Cell(10, 10, $no . ".", '0', 0, 'R');
                Fpdf::Cell(120, 10, $detail->barang->name, '0');
                Fpdf::Cell(9, 10, $detail->qty, '0', 0, 'L');


                //$satuan = GO::where('barang_id', $detail->barang->id)->first();

                Fpdf::Cell(50, 10, $detail->barang->satuan, '0', 0, 'L');

                if ($detail->Serial_number == "") {
                } else {
                    Fpdf::SetFont('helvetica', 'BI', 10);
                    Fpdf::Ln(4);
                    Fpdf::Cell(10, 10, '', 0, 0, 'R');
                    Fpdf::Cell(50, 10, 'S/N : ' . $detail->Serial_number, 0, 0, 'L');
                }
            } else {
                Fpdf::SetFont('helvetica', '', 11);
                Fpdf::Cell(10, 10, $no . ".", 0, 0, 'R');
                Fpdf::Cell(120, 10, $detail->barang->name, 0);
                Fpdf::Cell(9, 10, $detail->qty, 0, 0, 'L');

                //$satuan = GO::where('barang_id', $detail->barang->id)->first();

                Fpdf::Cell(50, 10, $detail->barang->satuan, 0, 0, 'L');

                if ($detail->Serial_number == "") {
                } else {
                    Fpdf::SetFont('helvetica', 'BI', 10);
                    Fpdf::Ln(4);
                    Fpdf::Cell(10, 10, '', 0, 0, 'R');
                    Fpdf::Cell(50, 10, 'S/N : ' . $detail->Serial_number, 0, 0, 'L');
                }
            }
        }

        Fpdf::Ln(15);
        Fpdf::SetFont('helvetica', '', 10);
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

        $filename = "storage/surat jalan/bap/" . str_replace('/', '_', $transaksi->invoice) . '.pdf';
        Fpdf::Output($filename, 'F');

        if ($transaksi->send_telegram == 1) {

            $remoteImage = $filename;
            $filename = str_replace('/', '_', $transaksi->invoice) . '.pdf';

            Telegram::sendDocument([
                'chat_id'   => $ID_TELE_CHANNEL,
                'document'  => InputFile::create($remoteImage, $filename),
                'caption'   => 'SURAT JALAN',
            ]);

            $transaksi->update([
                'send_telegram' => 1
            ]);

            //SEND EMAIL
            Mail::to($transaksi->department->email)->send(new StockMail($id, "storage/surat jalan/keluar/" . $filename, 'SURAT JALAN'));
        }













        // $transaksi = Transaksi::with(['department', 'user', 'detail', 'detail.barang'])->find($id);

        // return view('transouts.print', compact('transaksi'));
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
