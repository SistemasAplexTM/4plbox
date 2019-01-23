<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SetupController extends Controller
{
    public function init()
    {
      return view('templates.setup');
    }
}
