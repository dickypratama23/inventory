<?php

namespace App\Http\Controllers;

use Excel;
use App\Exports\ExcelPengeluaran;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    // /**
    //  * Create a new controller instance.
    //  *
    //  * @return void
    //  */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    // /**
    //  * Show the application dashboard.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function index()
    // {
    //     return view('home');
    // }

    public function excel_pengeluaran()
    {
        return Excel::download(new ExcelPengeluaran, 'pengeluaran.xlsx');
    }
}
