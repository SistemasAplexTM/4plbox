<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ConsolidadoExport implements FromView, ShouldAutoSize
{
  public $view;
  public $data;

  public function __construct($view, $data = null)
  {
      $this->data = $data['datos'];
      $this->view = $view;
  }

  public function view(): View
  {
      return view($this->view, [
          'data' => $this->data
      ]);
  }
}
