<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ReportExport implements WithMultipleSheets
{
    public function sheets(): array
    {

      return [
        new ServiceMasukExport(),
        new ServiceMasukDetailExport(),
        new ServiceKeluarExport(),
        new ServiceKeluarDetailExport(),
      ];

    }
}