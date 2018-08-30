<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BillLading;
use Illuminate\Support\Facades\DB;

class BillLadingController extends Controller
{
    public function index()
    {
        return view('templates/bill/index');
    }

    public function create()
    {
        // $this->assignPermissionsJavascript('documento');
        $bill = true;
        return view('templates/bill/create', compact('bill'));
    }

    public function getAll()
    {
        $sql = BillLading::select(
        	'bill_lading.*',
        	DB::raw('(SELECT z.gross_weight FROM bill_lading_detail AS z WHERE z.bill_lading_id = bill_lading.id AND z.deleted_at IS NULL) AS peso_kl')
    	)
            ->where('bill_lading.deleted_at', NULL);
        return \DataTables::of($sql)->make(true);
    }
}
