<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CasilleroApiController extends Controller
{
    public function getAllWarehouse($user)
    {
      $data = DB::table('status_detalle')->select(
        
        )
    }
}
