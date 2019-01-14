<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AplexConfig;
use App\Agencia;
use DataTables;

class AplexConfigController extends Controller
{
    public function index()
    {
        return view('templates/aplexConfig/index');
    }

    public function get($key){
        return AplexConfig::where('key', $key)->first();
    }

    public function getDataAgencyById($id){
        return Agencia::select('agencia.*')
            ->where([['agencia.id', '=', $id], ['agencia.deleted_at', '=', null]])
            ->first();
    }
}
