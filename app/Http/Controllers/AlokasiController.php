<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use PDF;
use Fpdf;
use Telegram;
use Telegram\Bot\FileUpload\InputFile;
use Excel;

use App\Department;
use App\User;
use App\Transaksi;
use App\Barang;
use App\Transaksi_detail;
use App\Stock;
use App\GO;
use App\Configures;

use App\Exports\AlokasiExport;

class AlokasiController extends Controller
{
    public function index()
    {
        if (!Session::get('login')) {
            return redirect('/login');
        }

        $transaksi  = Transaksi::with(['department', 'detail'])
            ->where([
                ['invoice', 'like', 'A/%'],
                ['status', '!=', 0]
            ])
            ->orderBy('created_at', 'DESC')->get();
        return view('alokasi.index',  compact('transaksi'));
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

        $faktur = 'A/' . date('Ymd') . '/' . numberToRomanRepresentation(date('y')) . '/' . numberToRomanRepresentation(date('m')) . '/' . strtotime(date('his'));
        $departments = Department::orderBy('created_at', 'DESC')->get();
        $users = User::orderBy('created_at', 'DESC')->get();

        return view('alokasi.create', compact('departments', 'users', 'faktur'));
    }

    public function save(Request $request)
    {
        $DEPT = Department::where('kdtk', 'edp')->first();
        $DEPT_ID = $DEPT->id;
        
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
                'department_id' => $DEPT_ID,
                'pic' => $request->pic,
                'user_id' => $request->pembuat,
                'status' => 0,
                'note' => $request->keterangan,
                'sign' => ''
            ]);

            return redirect(route('alokasi.add', ['id' => $transaksi->id]));
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
        return view('alokasi.add', compact('transaksi', 'barang'));
    }

    public function update(Request $request, $id)
    {

        //VALIDASI
        $this->validate($request, [
            'barang_id' => 'required|exists:barangs,id',
            'qty' => 'required|integer'
            //'note' => 'required|string'
        ]);

        //VALIDASI STOCK
        $stock_barang = Stock::where('barang_id', $request->barang_id)->first();
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
                    'rtype' => 'AL'
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

        return redirect('/alokasi/new')->with(['message_success' => 'Transaksi Barang Keluar Berhasil Di Buat, Silahkan Hubungi Atasan Anda Untuk Di Setujui']);
    }

    public function export_excel()
    {
        return Excel::download(new AlokasiExport, 'alokasi.xlsx');
    }


}
