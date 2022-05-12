<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Transaksi;

class SignController extends Controller
{
    public function ListSign()
    {
       
        $transaksi  = Transaksi::where('sign', '')->get();
         
        return view('ListSign',  compact('transaksi'));
    }

    public function Sign($id)
    {
        $transaksi  = Transaksi::find($id);
        return view('sign_inv',  compact('transaksi'));
    }

    public function Signed($id)
    {
        $data_uri = $_POST['imageData'];

        $transaksi  = Transaksi::find($id);

        $transaksi->update([
            'sign' => $data_uri
        ]);

        return redirect('/ListSign');
    }
}
