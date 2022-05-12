<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use App\Department;
use App\User;
use App\Transaksi;
use App\Barang;
use App\Transaksi_detail;
use App\Stock;

class TujuanTokoController extends Controller
{
    public function btbtoko()
    {
        if (!Session::get('login')) {
            return redirect('/login');
        }

        $departments = Department::orderBy('created_at', 'DESC')->get();
        $users = User::orderBy('created_at', 'DESC')->get();

        return view('btb.btbtoko', compact('users'));
    }

    public function btbtokosave(Request $request)
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

        //VALIDASI
        $this->validate($request, [
            'ke' => 'required',
            'pic' => 'required|string',
            'keterangan' => 'required|string',
            'pembuat' => 'required'
        ]);

        $toko = Department::where('id', $request->ke)->first();
        $faktur = 'BTB/' . $toko->kdtk . '/' . date('Ymd') . '/' . numberToRomanRepresentation(date('y')) . '/' . numberToRomanRepresentation(date('m')) . '/' . strtotime(date('his'));

        try {
            $transaksi = Transaksi::create([
                'invoice' => $faktur,
                'department_id' => $request->ke,
                'pic' => $request->pic,
                'user_id' => $request->pembuat,
                'status' => 0,
                'note' => $request->keterangan,
                'sign' => ''
            ]);

            return redirect(route('btbtoko.add', ['id' => $transaksi->id]));
        } catch (\Exception $e) {
            return redirect()->back()->with(['message_error' => $e->getMessage()]);
        }
    }

    public function btbtokoadd($id)
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
        return view('btb.btbtokoadd', compact('transaksi', 'barang'));
    }

    public function btbtokoupdate(Request $request, $id)
    {
        if (!Session::get('login')) {
            return redirect('/login');
        }

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
                        'rtype' => 'O'
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
                        'rtype' => 'O'
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

    public function btbopr()
    {
    }
}
