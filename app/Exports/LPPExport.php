<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class LPPExport implements WithMultipleSheets
{
    public function sheets(): array
    {

      return [
        new LPPStockExport(),
        new LPP_PPCabangExport(),
      ];

    }
}