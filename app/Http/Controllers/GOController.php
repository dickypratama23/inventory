<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use App\Barang;
use App\GO;
use App\Stock;
use App\Transaksi;
use App\User;
use App\Transaksi_detail;

class GOController extends Controller
{
    public function index()
    {
        if (!Session::get('login')) {
            return redirect('/login');
        }

        $GOs = GO::orderBy('barang_id', 'ASC')->get();
        $Stocks = Stock::all();

        return view('GO.index', compact('GOs', 'Stocks'));
    }

    public function save(Request $request)
    {
        //VALIDASI
        $this->validate($request, [
            'ke' => 'required|integer',
            'pic' => 'required|string'
            //'note' => 'required|string'
        ]);

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

        //CARI ID UNTUK USER_ID
        $USER = User::where('nik', session('nik'))->first();
        $USER_ID = $USER->id;


        $faktur = 'OUT/GO/' . date('Ymd') . '/' . numberToRomanRepresentation(date('y')) . '/' . numberToRomanRepresentation(date('m')) . '/' . strtotime(date('his'));
        $barang = $_POST['barang'];
        $qty = $_POST['qty'];
        $sn = $_POST['sn'];

        $transaksi = Transaksi::create([
            'invoice' => $faktur,
            'department_id' => $_POST['ke'],
            'pic' => $_POST['pic'],
            'user_id' => $USER_ID,
            'status' => 0,
            'note' => 'GO TOKO BARU',
            'sign' => ''
        ]);

        foreach ($barang as $key => $n) {
            Transaksi_detail::create([
                'transaksi_id' => $transaksi->id,
                'barang_id' => $n,
                'qty' => $qty[$key],
                'serial_number' => $sn[$key],
                'note' => '',
                'rtype' => 'O'
            ]);
        }

        $transaksi->update([
            'status' => 1
        ]);
    }
}
