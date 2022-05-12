<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ExcelPengeluaran implements WithMultipleSheets
{
  public function sheets(): array
  {

    return [
      new ExcelPengeluaranExport(),
    ];
  }
}
