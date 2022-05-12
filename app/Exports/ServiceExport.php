<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ServiceExport implements WithMultipleSheets
{
    public function sheets(): array
    {

      return [
        new ServiceCabangExport(),
        new ServiceHOExport(),
        new ServiceAmbilExport(),
      ];

    }
}