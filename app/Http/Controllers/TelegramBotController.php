<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram;
use Telegram\Bot\FileUpload\InputFile;

use App\Transaksi;

class TelegramBotController extends Controller
{
    public function updatedActivity()
    {
        $activity = Telegram::getUpdates();
        dd($activity);
    }

    public function sendMessage(Request $request)
    {
        //SEND MESSAGE 
        $text = "Tes Send Telegram 151";

        Telegram::sendMessage([
            'chat_id' => -1001454519317.0,
            'parse_mode' => 'HTML',
            'text' => $text
        ]);

        //SEND FILE
        $remoteImage = 'robots.txt';
        $filename = 'robots.txt';

        Telegram::sendDocument([
            'chat_id'   => -1001351500125.0,
            'document'  => InputFile::create($remoteImage, $filename),
            'caption'   => 'This is a caption',
        ]);

        //SEND PHOTO
        $remoteImage = 'idm.png';
        $filename = 'idm.png';

        // Telegram::sendPhoto([
        //     'chat_id'   => -1001351500125.0,
        //     'photo'  => InputFile::create($remoteImage, $filename),
        //     'caption'   => 'This is a caption',
        // ]);
    }

    public function telegram_ins($id)
    {
        $transaksi = Transaksi::find($id);
        $item = array();
        foreach ($transaksi->detail as $det) {
            $item[] = '[' . $det->barang->kode . '] ' . $det->barang->name . ' (' . $det->qty . ' item)';
        }

        $error_telegram = array();
        $text = "Barang Masuk\n\n"
            . "<b>Docno:</b> " . $transaksi->invoice . "\n"
            . "<b>Dari:</b> " .  $transaksi->department->kdtk . '-' . $transaksi->department->name . " \n"
            . "<b>Barang: </b> \n" . implode("\n", $item) . "\n"
            . "<b>Penerima: </b> " . session('nik') . " - " . session('nama')  . "\n";

        // Telegram::sendMessage([
        //     'chat_id' => 610280902,
        //     'parse_mode' => 'HTML',
        //     'text' => $text
        // ]);

        return redirect('/transin/new')->with(['message_success' => 'Transaksi Barang Masuk Berhasil']);
    }

    public function telegram_out($id)
    { }
}
