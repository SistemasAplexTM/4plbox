<?php

namespace App\Http\Controllers;

use App\Agencia;
use App\Documento;
use App\DocumentoDetalle;
use App\MaestraMultiple;
use App\Servicios;
use App\TipoDocumento;
use Auth;
use Barryvdh\DomPDF\Facade as PDF;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use JavaScript;
use Redirect;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports/index');
    }
}
