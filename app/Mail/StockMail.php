<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Transaksi;
use App\Configures;

class StockMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $id;
    public $atc;
    public $jenis;
    public function __construct($id, $atc, $jenis)
    {
        $this->id = $id;
        $this->atc = $atc;
        $this->jenis = $jenis;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $CONF = Configures::where('rtype', 'EMAIL')->first();
        $EMAIL = $CONF->val;

        $transaksi = Transaksi::find($this->id);

        if ($this->jenis == 'SURAT JALAN SERVICE') {
            $viewblade = 'emailss';
        } else {
            $viewblade = 'emailku';
        }

        return $this->from($EMAIL)
            ->view($viewblade)
            ->subject($this->jenis)
            ->with([
                'transaksi' => $transaksi,
                'jenis' => $this->jenis
            ])
            ->attach($this->atc, [
                'as' => str_replace('/', '_', $transaksi->invoice) . '.pdf',
                'mime' => 'pdf',
            ]);
    }
}
