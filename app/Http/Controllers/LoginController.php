<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use Hash;
use Telegram;

use App\User;
use App\Configures;




class LoginController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function login_new()
    {
        return view('first_login');
    }

    public function login(Request $request)
    {
        $user = User::where('nik', $request->nik)->first();
        if ($user) {

            if ($user->created_at == $user->updated_at) {
                if (Hash::check($_POST['password'], $user->password)) {
                    Session::put('nik', $user->nik);
                    return redirect('/login_new');
                } else {
                    return redirect('/login');
                }
            }

            if (Hash::check($_POST['password'], $user->password)) { } else {
                return redirect('/login');
            }

            $const = Configures::where('rtype','TUTUPAN')->first();

            Session::put('tutupan', $const->val);
            Session::put('nik', $user->nik);
            Session::put('nama', $user->name);
            Session::put('role', $user->role);
            Session::put('login', TRUE);

            if (!$user->sign) {
                Session::put('ttd', TRUE);
            }




            return redirect('/CAD')->with('TUTUP', $const->val);
        } else {
            return redirect('/login');
        }
    }

    public function logins()
    {
        $user = User::where('nik', session('nik'))->first();
        $user->update([
            'password' => bcrypt($_POST['password'])
        ]);

        return redirect('/login');
    }

    public function logout()
    {
        Session::flush();
        return redirect('/login');
    }

    public function browser_400()
    {
        return view('400_browser');
    }

    public function ttd()
    {
        if (!Session::get('login')) {
            return redirect('/login');
        }
        return view('ttd');
    }

    public function ttd_reg($id)
    {
        $data_uri = $_POST['imageData'];

        User::where('nik', $id)
            ->update([
                'sign' => $data_uri
            ]);


        Session::forget('ttd');
        return redirect('/lpp')->with(['message_success' => 'Tanda Tangan Berhasil']);
    }

    public function forgot()
    {
        //$user = User::where('nik', $request->nik)->first();
        return view('forgot');
    }

    public function otp(Request $request)
    {
        $user = User::where('nik', $request->nik)->first();
        if ($user->telegram) {

            $kode = rand(11111, 99999);
            $user->update([
                'otp' => $kode
            ]);

            $text = "Kode OTP Anda : <b>" . $kode . "</b>";

            Telegram::sendMessage([
                'chat_id' => 610280902,
                'parse_mode' => 'HTML',
                'text' => $text
            ]);

            return redirect()->back();
        }

        return redirect()->back()->with('error', 'Id Telegram Anda Belum Terdaftar');
    }

    public function otps(Request $request)
    {
        $nik = $request->nik;
        $otp = $request->otp;

        $user = User::where([
            ['nik', $nik],
            ['otp', $otp]
        ])->first();

        if ($user) {
            $user->update([
                'otp' => 0,
                'password' => bcrypt($nik),
                'updated_at' => $user->created_at
            ]);

            return redirect('/login');
        }

        return redirect()->back()->with('error', 'Kode OTP Salah');
    }
}
