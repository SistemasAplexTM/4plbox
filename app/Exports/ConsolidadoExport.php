<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ConsolidadoExport implements FromView
{
  public $data;

  public function __construct($data = null)
  {
      $this->data = $data['datos'];
  }

  public function view(): View
  {
      return view('exports.excelLiquimp', [
          'data' => $this->data
      ]);
  }
}
