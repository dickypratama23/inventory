<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Mail\Stockmail;
use Illuminate\Support\Facades\Mail;

class TempController extends Controller
{
    public function index()
    {
        Mail::to("edp@btm.indomaret.co.id")->send(new StockMail(39));
        return "Email telah dikirim";
    }
}
