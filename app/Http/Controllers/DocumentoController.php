<?php

namespace App\Http\Controllers;

use Neodynamic\SDK\Web\WebClientPrint;
use Neodynamic\SDK\Web\Utils;
use Neodynamic\SDK\Web\DefaultPrinter;
use Neodynamic\SDK\Web\InstalledPrinter;
use Neodynamic\SDK\Web\PrintFile;
use Neodynamic\SDK\Web\PrintFilePDF;
use Neodynamic\SDK\Web\PrintFileTXT;
use Neodynamic\SDK\Web\PrintRotation;
use Neodynamic\SDK\Web\PrintOrientation;
use Neodynamic\SDK\Web\TextAlignment;
use Neodynamic\SDK\Web\ClientPrintJob;
use Session;

use Auth;
use DataTables;
use JavaScript;
use Redirect;
use Excel;
use App\Agencia;
use App\Documento;
use App\DocumentoDetalle;
use App\MaestraMultiple;
use App\Servicios;
use App\Status;
use App\TipoDocumento;
use App\Invoice;
use App\InvoiceDetail;
use App\Shipper;
use App\Consignee;
use App\Transportador;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Traits\DocumentTrait;
use App\Exports\ConsolidadoExport;
use App\Exports\InternalManifest;
use Rap2hpoutre\FastExcel\FastExcel;
use \Maatwebsite\Excel\Sheet;

class DocumentoController extends Controller
{
    use DocumentTrait;

    public function __construct()
    {
        $this->middleware('permission:documento.index')->only('index');
        $this->middleware('permission:documento.create')->only('store', 'create');
        $this->middleware('permission:documento.update')->only('update', 'edit');
        $this->middleware('permission:documento.delete')->only('delete');
        $this->middleware('permission:documento.ajaxCreate')->only('ajaxCreate');
        $this->middleware('permission:documento.deleteDetailConsolidado')->only('deleteDetailConsolidado');
        $this->middleware('permission:documento.insertDetail')->only('insertDetail');
        $this->middleware('permission:documento.editDetail')->only('editDetail');
        $this->middleware('permission:documento.additionalChargues')->only('additionalChargues');
        $this->middleware('permission:documento.additionalCharguesDelete')->only('additionalCharguesDelete');
        $this->middleware('permission:documento.pdf')->only('pdf');
        $this->middleware('permission:documento.pdfLabel')->only('pdfLabel');
        $this->middleware('permission:documento.pdfContrato')->only('pdfContrato');
        $this->middleware('permission:documento.pdfTsa')->only('pdfTsa');
        $this->middleware('permission:documento.ajaxCreateNota')->only('ajaxCreateNota');
        $this->middleware('permission:documento.deleteNota')->only('deleteNota');
        $this->middleware('permission:documento.removerGuiaAgrupada')->only('removerGuiaAgrupada');
    }

    public function printFile(Request $request)
    {
        $consolidado = null;
        $id_detalle = null;
        $id_detail_consol = null;
        if ($request->input('consolidado')) {
            $consolidado = 'consolidado';
        }
        if ($request->input('id_detail')) {
            $id_detalle = $request->input('id_detail');
        }
        if ($request->input('id_detail_consol')) {
            $id_detail_consol = $request->input('id_detail_consol');
        }

        // VALIDAR SI ES UN LABEL O UN DOCUMENTO A IMPRIMIR
        if ($request->input('label')) {
            $this->pdfLabel($request->input('id'), $request->input('document'), $id_detalle, $consolidado, $id_detail_consol);
            $print = $request->input('printerName');
        } else {
            $this->pdf($request->input('id'), $request->input('document'), $id_detalle, false);
            $print = $request->input('printerName');
        }
        if ($request->exists(WebClientPrint::CLIENT_PRINT_JOB)) {
            $useDefaultPrinter = ($request->input('useDefaultPrinter') === 'checked');
            // $printerName = urldecode($request->input('printerName'));
            $printerName = urldecode($print);
            $filetype = $request->input('filetype');
            $fileName = uniqid() . '.' . $filetype;

            $filePath = '';
            $filePath = public_path() . '/files/File.pdf';

            if (!Utils::isNullOrEmptyString($filePath)) {
                //Create a ClientPrintJob obj that will be processed at the client side by the WCPP
                $cpj = new ClientPrintJob();

                $myfile = new PrintFilePDF($filePath, $fileName, null);
                $myfile->printRotation = PrintRotation::None;
                //$myfile->pagesRange = '1,2,3,10-15';
                //$myfile->printAnnotations = true;
                //$myfile->printAsGrayscale = true;
                //$myfile->printInReverseOrder = true;
                $cpj->printFile = $myfile;

                if ($useDefaultPrinter || $printerName === 'null') {
                    $cpj->clientPrinter = new DefaultPrinter();
                } else {
                    $cpj->clientPrinter = new InstalledPrinter($printerName);
                }

                //Send ClientPrintJob back to the client
                return response($cpj->sendToClient())
                    ->header('Content-Type', 'application/octet-stream');
            }
        }
    }

    public function index()
    {
        try {
          $this->assignPermissionsJavascript('documento');
          $status_list = Status::select('id', 'descripcion', 'color', 'icon')
              ->where([['deleted_at', null]])
              ->get();

          $fFin = strtotime('+5 day' , strtotime(date('Y-m-d')));
          $fFin = date('Y-m-d' , $fFin);
          $nuevafecha = strtotime('-6 day' , strtotime($fFin));
          $fIni = date('Y-m-d' , $nuevafecha);
          $pendientes = DB::table('documento AS a')
              ->leftJoin('documento_detalle AS b', 'a.id', 'b.documento_id')
              ->select(DB::raw('Count(a.num_warehouse) AS cantidad'))
              ->where([
                  ['a.deleted_at', null],
                  ['a.tipo_documento_id', 1],
                  ['b.num_warehouse', null],
                  ['b.deleted_at', null]
              ])
              ->whereBetween('a.created_at', [$fIni,$fFin])
              ->whereNotNull('a.num_warehouse')
              ->first();
          // OBTENER LA CONFIGURACION DE LA IMPRESORA
          $printers = Session::get('printer');
          //   print_r($printers);
          //   exit();
          JavaScript::put([
              'print_labels' => (($printers) ? $printers->label : ''),
              'print_documents'  => (($printers) ? $printers->default : ''),
              'print_format'  => 'PDF',
          ]);
          $wcpScript = WebClientPrint::createScript(action('WebClientPrintController@processRequest'), action('DocumentoController@printFile'), Session::getId());
          return view('templates.documento.index', compact('status_list', 'pendientes', 'wcpScript'));
        } catch (\Exception  $e) {
            \Log::debug('Test var fails: ' . $e->getMessage());
        }
        
    }

    public function create($tipo_documento_id)
    {
        $this->assignPermissionsJavascript('documento');
        $tipo      = TipoDocumento::findOrFail($tipo_documento_id);
        $agencias  = Agencia::all();
        $servicios = Servicios::all();
        $embarques = MaestraMultiple::select('id', 'nombre')
            ->where([['modulo', 'Tipo_Embarque'], ['deleted_at', null]])
            ->get();
        $empaques = MaestraMultiple::select('id', 'nombre')
            ->where([['modulo', 'Tipo_Empaque'], ['deleted_at', null]])
            ->get();
        $tipoPagos = MaestraMultiple::select('id', 'descripcion')
            ->where([['modulo', 'Tipos_de_Pago'], ['deleted_at', null]])
            ->get();
        $formaPagos = MaestraMultiple::select('id', 'nombre')
            ->where([['modulo', 'Forma_de_Pago'], ['deleted_at', null]])
            ->get();
        $grupos = MaestraMultiple::select('id', 'nombre')
            ->where([['modulo', 'Grupos'], ['deleted_at', null]])
            ->get();
        $this->AddToLog('Crear documento');
        return view('templates/documento/documento', compact(
            'tipo',
            'agencias',
            'servicios',
            'embarques',
            'empaques',
            'tipoPagos',
            'formaPagos',
            'grupos'
        ));
    }

    public function ajaxCreate(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $data                    = new Documento;
                $data->agencia_id        = ($request->agencia_id) ? $request->agencia_id : Auth::user()->agencia_id;
                $data->tipo_documento_id = $request->tipo_documento_id;
                $data->usuario_id        = ($request->usuario_id) ? $request->usuario_id : Auth::user()->id;
                $data->carga_courier     = (isset($request->type_id) and $request->type_id != '') ? $request->type_id : 0;
                $data->created_at        = $request->created_at;
                $data->tipo_consolidado  = 'COURIER';
                $tipo                    = TipoDocumento::findOrFail($request->tipo_documento_id);

                $getShipper = $this->getConfig('shipperDefault');
                $getConsignee = $this->getConfig('consigneeDefault');
                if (isset($request->shipper_id)) {
                    $data->shipper_id = $request->shipper_id;
                } else {
                    if ($getShipper) {
                        $data->shipper_id = $getShipper->value;
                    }
                }
                if (isset($request->consignee_id)) {
                    $data->consignee_id = $request->consignee_id;
                } else {
                    if ($getConsignee) {
                        $data->consignee_id = $getConsignee->value;
                    }
                }

                if ($data->save()) {
                    $id_documento = $data->id;

                    /* INSERCION DE TABLA AUXILIAR CONSECUTIVO */
                    $consecutive = DB::select("CALL getConsecutivoByTipoDocumento(?,?,?)", array($request->tipo_documento_id, $id_documento, date('Y-m-d H:i:s')));
                    $consecutivo = $consecutive[0]->consecutivo;

                    if ($request->tipo_documento_id == 1 || $request->tipo_documento_id == 2) {
                        /* INSERCION DE TABLA PIVOT GUIA_WRH_PIVOT */
                        DB::table('guia_wrh_pivot')->insert([
                            [
                                /* VALORES POR DEFECTO AL CREAR EL DOCUMENTO INICIAL */
                                'documento_id'     => $id_documento,
                                'servicios_id'     => 4,// 4 PARA COMEXCO 1 GENERAL
                                'forma_pago_id'    => null,
                                'tipo_pago_id'     => 4, //collect
                                'tipo_embarque_id' => (isset($request->tipo_embarque_id)) ? $request->tipo_embarque_id : 1, //aereo
                                'grupo_id'         => 9, //general
                                // 'estado_id'        => ($request->tipo_documento_id == 2) ? 15 : 16, //maestra multiple
                                'created_at'       => $request->created_at,
                            ],
                        ]);

                        /* GENERAR NUMERO DE GUIA */
                        $caracteres      = strlen($consecutivo);
                        $sumarCaracteres = 8 - $caracteres;
                        $carcater        = '0';
                        $prefijo         = $tipo->prefijo;
                        // $prefijo2        = 'CLO';
                        for ($i = 1; $i <= $sumarCaracteres; $i++) {
                            $prefijo = $prefijo . $carcater;
                            // $prefijo2 = $prefijo2 . $carcater;
                        }
                    }

                    /*ACTUALIZACION DE NUMERO DE GUIA O NUMERO DE WAREHOUSE*/
                    $data2 = Documento::findOrFail($id_documento);
                    if ($request->tipo_documento_id == 1 || $request->tipo_documento_id == 2) {
                        // if ($request->tipo_documento_id == 2) {
                        $data2->num_warehouse = $prefijo . $consecutivo;
                        // }
                        // if ($request->tipo_documento_id == 1) {
                        // $data2->num_guia = $prefijo2 . $consecutivo;
                        // }
                    }
                    $data2->consecutivo = $consecutivo;
                    $data2->save();
                    $this->AddToLog('Documento creado (' . $id_documento . ') consecutivo (' . $consecutivo . ')');
                    $answer = array(
                        "datos"  => $data2,
                        "code"   => 200,
                        "status" => 200,
                    );

                    // INSERCION DE LA FACTURACION
                    if ($request->self_service) {
                        $invoice = Invoice::where([['shipper_id', $data->shipper_id], ['consignee_id', $data->consignee_id]])->first();
                        if (!$invoice) {
                            $invoice = Invoice::create([
                                'agency_id'     => ($request->agencia_id) ? $request->agencia_id : Auth::user()->agencia_id,
                                'consecutive'   => $data2->num_warehouse,
                                'date_document' => date('Y-m-d'),
                                'shipper_id'    => $data->shipper_id,
                                'consignee_id'  => $data->consignee_id
                            ]);
                        }

                        InvoiceDetail::create([
                            'invoice_id'   => $invoice->id,
                            'document_id'  => $data->id
                        ]);
                    }
                } else {
                    $answer = array(
                        "error"  => 'Error al intentar Eliminar el registro.',
                        "code"   => 600,
                        "status" => 500,
                    );
                }
                return $answer;
            });
        } catch (Exception $e) {

            $answer = array(
                "error"  => $e,
                "code"   => 600,
                "status" => 500,
            );
            return $answer;
        }
    }

    public function liquidar($id)
    {
        return $this->edit($id, true);
    }

    public function edit($id, $liquidar = false)
    {
        // OBTENEMOS EL ID DEL PAIS QUE ESTA REGISTRADO EN LA CONFIGURACION DE APLEX_CONFIG
        // PARA UTILIZARLO EN EL CONSOLIDADO

        $this->assignPermissionsJavascript('documento');
        $data = Documento::findOrFail($id);

        $id_pais = $this->getConfig('idColombia');
        $puntos = $this->getConfig('puntos_' . $data->agencia_id);

        JavaScript::put(['pais_id_config' => ($id_pais) ? $id_pais->value : null, 'puntos_config' => ($puntos) ? $puntos->value : null]);

        $tipo     = TipoDocumento::findOrFail($data->tipo_documento_id);
        $agencias = Agencia::select('id', 'descripcion')
            ->where([['deleted_at', null]])
            ->get();
        $embarques = MaestraMultiple::select('id', 'nombre')
            ->where([['modulo', 'Tipo_Embarque'], ['deleted_at', null]])
            ->get();
        $empaques = MaestraMultiple::select('id', 'nombre')
            ->where([['modulo', 'Tipo_Empaque'], ['deleted_at', null]])
            ->get();
        $tipoPagos = MaestraMultiple::select('id', 'descripcion')
            ->where([['modulo', 'Tipos_de_Pago'], ['deleted_at', null]])
            ->get();
        $formaPagos = MaestraMultiple::select('id', 'nombre')
            ->where([['modulo', 'Forma_de_Pago'], ['deleted_at', null]])
            ->get();
        $grupos = MaestraMultiple::select('id', 'nombre')
            ->where([['modulo', 'Grupos'], ['deleted_at', null]])
            ->get();

        $documento = Documento::leftJoin('shipper', 'documento.shipper_id', '=', 'shipper.id')
            ->leftJoin('consignee', 'documento.consignee_id', '=', 'consignee.id')
            ->join('agencia', 'documento.agencia_id', '=', 'agencia.id')
            ->leftJoin('guia_wrh_pivot', 'documento.id', '=', 'guia_wrh_pivot.documento_id')
            ->join('tipo_documento', 'documento.tipo_documento_id', '=', 'tipo_documento.id')
            ->leftJoin('maestra_multiple', 'documento.transporte_id', 'maestra_multiple.id')
            ->leftJoin('localizacion', 'documento.ciudad_id', '=', 'localizacion.id')
            ->leftJoin('deptos', 'localizacion.deptos_id', '=', 'deptos.id')
            ->leftJoin('pais', 'deptos.pais_id', '=', 'pais.id')
            ->leftJoin('transportador as central_destino', 'documento.central_destino_id', '=', 'central_destino.id')
            ->select(
                'documento.*',
                'guia_wrh_pivot.id as guia_wrh_pivot_id',
                'guia_wrh_pivot.servicios_id',
                'guia_wrh_pivot.forma_pago_id',
                'guia_wrh_pivot.tipo_pago_id',
                'guia_wrh_pivot.tipo_embarque_id',
                'guia_wrh_pivot.grupo_id',
                'tipo_documento.funcionalidades',
                'tipo_documento.nombre as tipo_nombre',
                'localizacion.nombre as ciudad',
                'pais.descripcion as pais',
                'pais.id as pais_id',
                'central_destino.nombre as central_destino',
                'maestra_multiple.nombre as transporte',
                'maestra_multiple.id as transporte_id',
                'consignee.po_box'
            )
            ->where([
                ['documento.deleted_at', null],
                ['documento.id', $id],
            ])
            ->first();
        $detalle = DocumentoDetalle::join('documento', 'documento_detalle.documento_id', '=', 'documento.id')
            ->leftJoin('shipper', 'documento_detalle.shipper_id', '=', 'shipper.id')
            ->leftJoin('consignee', 'documento_detalle.consignee_id', '=', 'consignee.id')
            ->join('agencia', 'documento.agencia_id', '=', 'agencia.id')
            ->leftJoin('posicion_arancelaria', 'documento_detalle.posicion_arancelaria_id', '=', 'posicion_arancelaria.id')
            ->join('maestra_multiple', 'documento_detalle.tipo_empaque_id', '=', 'maestra_multiple.id')
            ->select(
                'documento_detalle.*',
                'agencia.descripcion AS nom_agencia',
                'posicion_arancelaria.pa AS nom_pa',
                'posicion_arancelaria.id AS id_pa',
                'shipper.nombre_full AS ship_nomfull',
                'consignee.nombre_full AS cons_nomfull',
                'maestra_multiple.nombre AS empaque',
                DB::raw("(SELECT Count(a.id) FROM tracking AS a WHERE a.documento_detalle_id = documento_detalle.id AND a.deleted_at IS NULL) as cantidad")
            )
            ->where([['documento_detalle.deleted_at', null], ['documento_detalle.documento_id', $id]])
            ->get();

        $shipper =  DB::table('shipper as a')
            ->join('localizacion as b', 'a.localizacion_id', 'b.id')
            ->join('deptos as c', 'b.deptos_id', 'c.id')
            ->join('pais as d', 'c.pais_id', 'd.id')
            ->select(
                'a.nombre_full',
                'a.direccion',
                'a.telefono',
                'a.whatsapp',
                'a.correo',
                'a.zip',
                'a.localizacion_id AS ciudad_id',
                'b.nombre AS ciudad',
                'c.pais_id'
            )
            ->where([
                ['a.deleted_at', null],
                ['a.id', $documento->shipper_id],
            ])
            ->first();
        $consignee =  DB::table('consignee as a')
            ->leftJoin('clientes', 'a.cliente_id', 'clientes.id')
            ->join('localizacion as b', 'a.localizacion_id', 'b.id')
            ->join('deptos as c', 'b.deptos_id', 'c.id')
            ->join('pais as d', 'c.pais_id', 'd.id')
            ->select(
                'a.nombre_full',
                'a.po_box',
                'clientes.nombre as cliente',
                'a.direccion',
                'a.telefono',
                'a.whatsapp',
                'a.correo',
                'a.zip',
                'a.localizacion_id AS ciudad_id',
                'b.nombre AS ciudad',
                'c.pais_id'
            )
            ->where([
                ['a.deleted_at', null],
                ['a.id', $documento->consignee_id],
            ])
            ->first();

        // $funcionalidades_doc = MaestraMultiple::select('id', 'nombre')
        //     ->where([['modulo', 7], ['deleted_at', null]])
        //     ->get();

        $funcionalidades = json_decode($documento->funcionalidades);
        if ($liquidar) {
            /* SI EXISTE LIQUIDAR ENTONCES TOMAMOS LAS FUNCIONALIDADES DE LA GUIA */
            $tipoGuia        = TipoDocumento::findOrFail(1); //el 1 es el tipo de documento guia hija
            $funcionalidades = json_decode($tipoGuia->funcionalidades);
        }
        $citys = [];
        JavaScript::put([
            'functionalities_doc' => $funcionalidades,
            // 'functionalities_db'  => json_decode(json_encode($funcionalidades_doc)),
            'shipper_data'  => json_decode(json_encode($shipper)),
            'consignee_data'  => json_decode(json_encode($consignee)),
            'citys_data'  => json_decode(json_encode($citys)),
            'data_agencia' => $this->getNameAgencia(),
        ]);
        $this->AddToLog('Documento ver (' . $id . ') consecutivo (' . $documento->consecutivo . ')');
        $role_admin = (Auth::user()->isRole('admin')) ? 1 : 0;
        return view('templates/documento/documento', compact(
            'documento',
            'detalle',
            'tipo',
            'agencias',
            'role_admin',
            'embarques',
            'empaques',
            'tipoPagos',
            'formaPagos',
            'grupos',
            'func'
        ));
    }

    public function update(Request $request, $id)
    {

        if ($request->document_type === 'consolidado') {
            try {
                $data                     = Documento::findOrFail($id);
                $data->ciudad_id          = $request->ciudad_id;
                $data->central_destino_id = $request->central_destino_id;
                $data->transporte_id      = $request->transporte_id;
                $data->observaciones      = $request->observacion;
                $data->updated_at         = $request->date;
                $data->tipo_consolidado   = $request->tipo_consolidado;
                if ($data->save()) {
                    $this->AddToLog('Documento Consolidado actualizado (' . $id . ')');
                    $answer = array(
                        "data"   => $data,
                        "code"   => 200,
                        "status" => 200,
                    );
                } else {
                    $answer = array(
                        "error"  => 'Error al intentar Eliminar el registro.',
                        "code"   => 600,
                        "status" => 500,
                    );
                }
                return $answer;
            } catch (Exception $e) {
                $error = '';
                if ($e->errorInfo != null) {
                    foreach ($e->errorInfo as $key => $value) {
                        $error .= $key . ' - ' . $value . ' <br> ';
                    }
                } else {
                    $error = $e;
                }
                $answer = array(
                    "error"        => $error,
                    "error_consol" => $e,
                    "code"         => 600,
                    "status"       => 500,
                );
                return $answer;
            }
        } else {
            DB::transaction(function () use ($request, $id) {
                $data             = Documento::findOrFail($id);
                $data->updated_at = $request->date;
                $data->agencia_id = $request->agencia_id;
                $shipper_old = $data->shipper_id;
                $consignee_old = $data->consignee_id;
                if ($request->opEditarShip) {
                    //CREACION O ACTUALIZACION DEL SHIPPER O CONSIGNEE
                    $idsShipCons      = $this->createOrUpdateShipperConsignee($request->all());
                    $data->shipper_id = $idsShipCons['shipper_id'];
                } else {
                    if ($request->shipper_id == '') {
                        $idsShipCons        = $this->createOrUpdateShipperConsignee($request->all());
                        $data->shipper_id   = $idsShipCons['shipper_id'];
                        $data->consignee_id = $idsShipCons['consig_id'];
                    } else {
                        $data->shipper_id = $request->shipper_id;
                    }
                }
                if ($request->opEditarCons) {
                    //CREACION O ACTUALIZACION DEL SHIPPER O CONSIGNEE
                    $idsShipCons        = $this->createOrUpdateShipperConsignee($request->all());
                    $data->consignee_id = $idsShipCons['consig_id'];
                } else {
                    if ($request->consignee_id == '' and  $data->consignee_id == '' and $data->shipper_id != '') {
                        $idsShipCons        = $this->createOrUpdateShipperConsignee($request->all());
                        $data->consignee_id = $idsShipCons['consig_id'];
                    } else {
                        if ($request->consignee_id != '') {
                            $data->consignee_id = $request->consignee_id;
                        } else {
                            // if ($data->consignee_id != '') {
                            $data->consignee_id = $data->consignee_id;
                            // }
                        }
                    }
                }
                /* OBTENER EL PREFIJO DE LA CIUDAD DEL CONSIGNEE PARA HACER EL NUMERO DE GUIA */
                $prefijoGuia = DB::table('consignee as a')
                    ->join('localizacion as b', 'a.localizacion_id', 'b.id')
                    ->join('deptos as c', 'b.deptos_id', 'c.id')
                    ->join('pais as d', 'c.pais_id', 'd.id')
                    ->select('b.prefijo', 'd.iso2')
                    ->where([
                        ['a.deleted_at', null],
                        ['a.id', $data->consignee_id],
                    ])
                    ->first();

                $data->user_update = Auth::user()->id;

                if ($request->document_type === 'warehouse') {
                    $data->piezas       = $request->piezas;
                    $data->volumen      = $request->volumen;
                    $data->peso         = $request->pesoDim;
                    $data->peso_cobrado = $request->pesoDim;
                } else {
                    if ($request->document_type === 'guia') {
                        if (!$request->liquidar) {
                            $data->liquidado       = 0;
                            $data->peso            = 0;
                            $data->peso_cobrado    = 0;
                            $data->flete           = 0;
                            $data->seguro          = 0;
                            $data->seguro_cobrado  = 0;
                            $data->cargos_add      = 0;
                            $data->descuento       = 0;
                            $data->total           = 0;
                            $data->valor_declarado = 0;
                            $data->pa_aduana       = 0;
                            $data->impuesto        = 0;
                            DocumentoDetalle::where('documento_id', $id)->update([
                                'liquidado' => 0,
                            ]);
                            $request->session()->flash('print_document', array('id' => $id, 'document' => ($request->option == 'print' || $request->option == 'all') ? 'warehouse' : ''));
                        } else {
                            $data->liquidado       = 1;
                            $data->peso            = $request->peso_total;
                            $data->peso_cobrado    = $request->peso_cobrado;
                            $data->valor           = ($request->valor_libra != '') ? $request->valor_libra : 0;
                            $data->valor_libra     = ($request->valor_libra2 != '') ? $request->valor_libra2 : 0;
                            $data->impuesto        = $request->impuesto;
                            $data->flete           = $request->flete;
                            $data->tarifa_minima   = $request->tarifa_minima;
                            $data->seguro          = $request->seguro_valor;
                            $data->seguro_cobrado  = $request->seguro;
                            $data->cargos_add      = $request->cargos_add;
                            $data->descuento       = $request->descuento;
                            $data->total           = $request->total;
                            $data->valor_declarado = $request->valor_declarado;
                            $data->pa_aduana       = $request->pa_aduana;
                            DocumentoDetalle::where('documento_id', $id)->update([
                                'liquidado' => 1,
                            ]);
                            $request->session()->flash('print_document', array('id' => $id, 'document' => ($request->option == 'print' || $request->option == 'all') ? 'guia' : ''));
                        }
                        $data->piezas            = $request->piezas;
                        $data->volumen           = $request->volumen;
                        $data->observaciones     = $request->observaciones;
                        $data->tipo_documento_id = 1;

                        /* GENERAR NUMERO DE GUIA */
                        $caracteres      = strlen($data->consecutivo);
                        $sumarCaracteres = 8 - $caracteres;
                        $carcater        = '0';
                        $prefijo         = (isset($prefijoGuia->prefijo) and $prefijoGuia->prefijo != '') ? $prefijoGuia->prefijo : '';
                        $prefijoPais     = (isset($prefijoGuia->iso2) and $prefijoGuia->iso2 != '') ? $prefijoGuia->iso2 : '';
                        for ($i = 1; $i <= $sumarCaracteres; $i++) {
                            $prefijo = $prefijo . $carcater;
                        }
                        // $data->num_guia = $prefijo . $data->consecutivo . $prefijoPais;
                    }
                }

                if ($request->factura) {
                    $data->factura = $request->factura;
                } else {
                    $data->factura = 0;
                }

                if ($request->carga_peligrosa) {
                    $data->carga_peligrosa = $request->carga_peligrosa;
                } else {
                    $data->carga_peligrosa = 0;
                }

                if ($request->re_empacado) {
                    $data->re_empacado = $request->re_empacado;
                } else {
                    $data->re_empacado = 0;
                }

                if ($request->mal_empacado) {
                    $data->mal_empacado = $request->mal_empacado;
                } else {
                    $data->mal_empacado = 0;
                }

                if ($request->rota) {
                    $data->rota = $request->rota;
                } else {
                    $data->rota = 0;
                }

                $data->save();

                $detalle = DocumentoDetalle::where('documento_id', $id)->get();

                /* GENERAR NUMERO DE GUIA A LOS DETALLES DEL DOCUMENTO */
                foreach ($detalle as $val) {
                    $paquete = '';
                    if (env('APP_CLIENT') != 'jyg') {
                        $paquete = 'P' . $val->paquete;
                    }
                    $num_guia = trim($prefijo . $data->consecutivo . $paquete);
                    // $num_guia = trim($prefijo . $data->consecutivo . $paquete . $prefijoPais);
                    // $num_guia = trim($prefijo . $data->consecutivo);

                    $datos = array('num_guia' => $num_guia);
                    // if ($shipper_old != $request->shipper_id) {
                    $datos['shipper_id'] = $data->shipper_id;
                    // }
                    // if ($consignee_old != $request->consignee_id) {
                    $datos['consignee_id'] = $data->consignee_id;
                    // }
                    DocumentoDetalle::where('id', $val->id)->update($datos);
                    /* ACTUALIZAR CONSIGNEE EN EL TRACKING */
                    DB::table('tracking')->where([['documento_detalle_id', $val->id]])->update(['consignee_id' => $data->consignee_id]);
                }

                /* INSERCION EN LA TABLA PIVOT DE GUIA_WRH */
                if ($request->document_type === 'warehouse') {
                    DB::table('guia_wrh_pivot')
                        ->where('documento_id', $id)
                        ->update([
                            'servicios_id'     => ($request->servicios_id) ? $request->servicios_id : 1,
                            'tipo_embarque_id' => (isset($request->tipo_embarque_id)) ? $request->tipo_embarque_id : 1,
                        ]);
                } else {
                    if ($request->document_type === 'guia') {
                        DB::table('guia_wrh_pivot')
                            ->where('documento_id', $id)
                            ->update([
                                'servicios_id'     => ($request->servicios_id) ? $request->servicios_id : 1,
                                'tipo_embarque_id' => ($request->tipo_embarque_id) ? $request->tipo_embarque_id : 1,
                                'tipo_pago_id'     => $request->tipo_pago_id,
                                'forma_pago_id'    => $request->forma_pago_id,
                                'grupo_id'         => $request->grupo_id,
                            ]);
                    }
                }
                /*VALIDO SI EXISTE EN LA TABLA PIBOT 'shipper_consignee' LA RELACION, SI NO EXISTE LA CREAMOS*/
                $this->validateRelationShipperConsignee($data->shipper_id, $data->consignee_id);
            });
            $msn = false;
            if ($request->option == 'email' || $request->option == 'all') {
                // if ($request->enviarEmailRemitente) {
                //     $this->sendEmailDocument($id);
                //     $msn = ' Email remitente enviado!';
                // }
                if ($request->enviarEmailDestinatario) {
                    $this->sendEmailDocument($id);
                    $msn .= ' Email destinatario enviado!';
                }
                if ($msn) {
                    $request->session()->put('sendemail', $msn);
                }
            }

            $this->AddToLog('Documento WRH/Guia actualizado (' . $id . ')');
        }

        return redirect()->route('documento.index');
    }

    public function deleteDetailConsolidado($id, $id_detalle, $logical)
    {
        $obj = DB::table('consolidado_detalle')->where('id', $id_detalle)->first();
        DB::table('consolidado_detalle')->where([['id', $id_detalle]])->delete();

        $data              = DocumentoDetalle::findOrFail($obj->documento_detalle_id);
        $data->consolidado = 0;
        $data->save();
        $piv = DB::table('status_detalle')->where([['documento_detalle_id', $obj->documento_detalle_id], ['status_id', 5]])->delete();
        $this->AddToLog('Consolidado detalle eliminado (' . $id_detalle . ')');
        $answer = array(
            "code" => 200,
        );

        return $answer;
    }

    public function destroy($id, $table = null)
    {
        if ($table) {
            $data = DocumentoDetalle::findOrFail($id);
            $data->delete();
            $piv = DB::table('status_detalle')->where([['documento_detalle_id', $id]])->delete();
            $this->AddToLog('Documento detalle eliminado (' . $id . ') WRH (' . $data->num_warehouse . ')');
        } else {
            $data = Documento::findOrFail($id);
            $piv = DB::table('guia_wrh_pivot')->where([['documento_id', $id]])->delete();
            $detail = DB::table('documento_detalle')->where([['documento_id', $id]])->get();

            if (count($detail) > 0) {
                foreach ($detail as $key) {
                    $this->destroy($key->id, 'detalle');
                    $piv = DB::table('status_detalle')->where([['documento_detalle_id', $key->id]])->delete();
                }
            }
            $data->delete();
            $this->AddToLog('Documento eliminado (' . $id . ') consecutivo (' . $data->consecutivo . ')');
            $answer = array(
                "datos" => 'Eliminación exitosa.',
                "code"  => 200,
            );
            return $answer;
        }
    }

    public function delete($id, $logical, $table = null)
    {

        if (isset($logical) and $logical == 'true') {
            if ($table) {
                $data = DocumentoDetalle::findOrFail($id);
            } else {
                $data = Documento::findOrFail($id);
            }
            $now              = new \DateTime();
            $data->deleted_at = $now->format('Y-m-d H:i:s');
            if ($data->save()) {
                $this->AddToLog('Documento detalle eliminado (' . $id . ')');
                $answer = array(
                    "datos" => 'Eliminación exitosa.',
                    "code"  => 200,
                );
            } else {
                $answer = array(
                    "error" => 'Error al intentar Eliminar el registro.',
                    "code"  => 600,
                );
            }

            return $answer;
        } else {
            if ($table) {
                $this->destroy($id, $table);
            } else {
                $this->destroy($id);
            }
        }
    }

    public function restaurar($id, $table)
    {
        if ($table) {
            $data  = DocumentoDetalle::findOrFail($id);
            $datos = DocumentoDetalle::join('documento', 'documento_detalle.documento_id', '=', 'documento.id')
                ->leftJoin('shipper', 'documento_detalle.shipper_id', '=', 'shipper.id')
                ->leftJoin('consignee', 'documento_detalle.consignee_id', '=', 'consignee.id')
                ->join('agencia', 'documento.agencia_id', '=', 'agencia.id')
                ->join('posicion_arancelaria', 'documento_detalle.posicion_arancelaria_id', '=', 'posicion_arancelaria.id')
                ->join('maestra_multiple', 'documento_detalle.tipo_empaque_id', '=', 'maestra_multiple.id')
                ->select('documento_detalle.*', 'agencia.descripcion AS nom_agencia', 'posicion_arancelaria.pa AS nom_pa', 'shipper.nombre_full AS ship_nomfull', 'consignee.nombre_full AS cons_nomfull', 'maestra_multiple.nombre AS empaque')
                ->where([['documento_detalle.id', $id]])
                ->first();
        } else {
            $data  = Documento::findOrFail($id);
            $datos = null;
        }
        $data->deleted_at = null;
        if ($data->save()) {
            $answer = array(
                "datos" => $datos,
                "code"  => 200,
            );
        } else {
            $answer = array(
                "error" => 'Error al intentar Eliminar el registro.',
                "code"  => 600,
            );
        }

        return $answer;
    }

    public function getAll(Request $request)
    {
        $data = $this->getConfig('show_agency_' . Auth::user()->agencia_id);
        if ($data and $data->value != '') {
            $show = $data->value;
        } else {
            $show = null;
        }
        JavaScript::put(['show_agency' => $show]);
        $filter = false;
        $where = [
            ['b.deleted_at', null],
            ['e.deleted_at', null],
            ['b.tipo_documento_id', $request->id_tipo_doc]
        ];
        if (!Auth::user()->isRole('admin')) {
            $where[] = ['b.agencia_id', Auth::user()->agencia_id];
        }
        /* GRILLA */
        if ($request->id_tipo_doc == 3 || $request->id_tipo_doc == 4) {
            $sql = $this->getAllConsolidated($where);
        } else {
            // if(env('APP_TYPE') == 'courier'){
            if ($request->type == 3) {
                if ($request->filter) {
                    $filter = $request->filter;
                }
                $sql = $this->getAllLoad($where, $filter);
            } else {
                $where = [
                    ['a.deleted_at', null],
                    ['b.deleted_at', null], ['b.tipo_documento_id', $request->id_tipo_doc]
                ];
                if (!Auth::user()->isRole('admin')) {
                    $where[] = ['b.agencia_id', Auth::user()->agencia_id];
                }
                if ($request->type == 2) {
                    $where[] = ['a.num_warehouse', '<>', NULL];
                    if ($request->filter) {
                        $filter = $request->filter;
                    }
                } else {
                    if ($request->type == 4) {
                        $where[] = ['a.num_warehouse', NULL];
                    }
                }
                $sql = $this->getAllCourier($where, $filter, $request->type);
            }
        }
        // DB::connection()->enableQueryLog();
        // Datatables::of($sql)->make(true);
        // return DB::getQueryLog();
        return Datatables::of($sql)->make(true);
    }

    public function selectInput(Request $request, $tableName)
    {
        $term = $request->term ?: '';
        if ($request->idSelect == 'localizacion_id') {
            $prefijo = 'prefijoR';
        } else {
            $prefijo = 'prefijoD';
        }

        if ($tableName === 'localizacion') {
            $tags = DB::table($tableName)
                ->join('deptos', 'localizacion.deptos_id', '=', 'deptos.id')
                ->join('pais', 'deptos.pais_id', '=', 'pais.id')
                ->select(['localizacion.id', 'localizacion.nombre as text', 'localizacion.prefijo as ' . $prefijo, 'deptos.descripcion as deptos', 'deptos.id as deptos_id', 'pais.descripcion as pais', 'pais.id as pais_id'])
                ->where([
                    ['localizacion.nombre', 'like', $term . '%'],
                    ['localizacion.deleted_at', null],
                ])->get();
        } else {
            if ($tableName === 'agencia') {
                $tags = DB::table($tableName)->select(['id', 'descripcion as text'])->where([
                    ['descripcion', 'like', $term . '%'],
                    [$tableName . '.deleted_at', '=', null],
                ])->get();
            } else {
                $tags = DB::table($tableName)->select(['id', 'nombre as text'])->where([
                    ['nombre', 'like', $term . '%'],
                    [$tableName . '.deleted_at', '=', null],
                ])->get();
            }
        }
        $answer = array(
            'code'  => 200,
            'items' => $tags,
        );
        return \Response::json($answer);
    }

    public function vueSelectGeneral($table, $data)
    {
        $term   = $data;
        $column = 'descripcion';
        if ($table == 'localizacion') {
            $column = 'nombre';
        }
        $tags = DB::table($table)->select(['id', $column . ' as name'])->where([
            [$column, 'like', '%' . $term . '%'],
            [$table . '.deleted_at', '=', null],
        ])->get();
        $answer = array(
            'code'  => 200,
            'items' => $tags,
        );
        return $answer;
    }

    public function vueSelect($data)
    {
        $term = $data;

        $tags = DB::table('pais')->select(['id', 'descripcion as name'])->where([
            ['descripcion', 'like', '%' . $term . '%'],
            ['pais.deleted_at', '=', null],
        ])->get();
        $answer = array(
            'code'  => 200,
            'items' => $tags,
        );
        return $answer;
    }

    public function vueSelectSucursales($data)
    {
        $term = $data;

        $tags = DB::table('agencia')->select(['id', 'descripcion as name'])->where([
            ['descripcion', 'like', '%' . $term . '%'],
            ['agencia.deleted_at', '=', null],
        ])->get();
        $answer = array(
            'code'  => 200,
            'items' => $tags,
        );
        return $answer;
    }

    public function vueSelectTransportadorMaster($data)
    {
        $term = $data;

        $tags = DB::table('transportador')->select(['id', 'nombre as name'])->where([
            ['nombre', 'like', '%' . $term . '%'],
            ['consignee', 1],
            ['deleted_at', null],
        ])->get();
        $answer = array(
            'code'  => 200,
            'items' => $tags,
        );
        return $answer;
    }

    /* REGISTROS DEL DETALLE */

    public function insertDetail(Request $request)
    {
        try {
            $pa = null;
            $config = $this->getConfig('posicion_arancelaria');
            if ($config) {
                $pa = ($config->value != '') ? $config->value : null;
            }
            for ($z = 1; $z <= $request->contador; $z++) {
                $data = (new DocumentoDetalle)->fill($request->all());

                $data->status_id = 2;
                if ($request->valor == '') {
                    $data->valor = 0;
                }
                if ($request->piezas == '') {
                    $data->piezas = 1;
                }
                if ($request->declarado2 == '') {
                    $data->declarado2 = 0;
                }
                if ($request->created_at) {
                    $data->created_at = $request->created_at;
                }
                if ($data->tracking == '') {
                    $data->tracking = null;
                }

                if ($data->arancel_id2 == '') {
                    $data->posicion_arancelaria_id = $pa;
                    $data->arancel_id2 = $pa;
                }

                /* OBTENER EL PREFIJO DE LA CIUDAD DEL CONSIGNEE PARA HACER EL NÚMERO DE GUIA */
                $prefijoGuia = DB::table('consignee as a')
                    ->join('localizacion as b', 'a.localizacion_id', 'b.id')
                    ->join('deptos as c', 'b.deptos_id', 'c.id')
                    ->join('pais as d', 'c.pais_id', 'd.id')
                    ->select('b.prefijo', 'd.iso2')
                    ->where([
                        ['a.deleted_at', null],
                        ['a.id', $request->consignee_id],
                    ])
                    ->first();
                /* OBTENER PREFIJO DE GUIA DESDE LA TABLA DE CONFIG, SI EXISTE SE REEMPLAZA POR EL PREFIJO DE LA CIUDAD */
                $config_pefix = $this->getConfig('prefix_guia');

                if ($config_pefix) {
                    $prefijoGuia->prefijo = $config_pefix->value;
                }

                $documento = Documento::findOrFail($data->documento_id);

                $documentoD = DocumentoDetalle::select('documento_detalle.id', 'documento_detalle.paquete')
                    ->where([
                        ['documento_detalle.documento_id', $data->documento_id],
                    ])
                    ->orderBy('id', 'DESC')
                    ->first();

                // $data->num_guia      = $documento->num_guia . '' . (count($documentoD) + 1);
                // PARA JYG QUITARLE EL UNO AL NUMERO DE GUIA Y WRH
                $paquete = '';
                if (env('APP_CLIENT') != 'jyg') {
                    $paquete = 'P' . (($documentoD) ? $documentoD->paquete + 1 : 1);
                }

                /* GENERAR NUMERO DE GUIA */
                $caracteres      = strlen($documento->consecutivo);
                $sumarCaracteres = 8 - $caracteres;
                $carcater        = '0';
                $prefijo         = (isset($prefijoGuia->prefijo) and $prefijoGuia->prefijo != '') ? $prefijoGuia->prefijo : 'CLO';
                $prefijoPais     = (isset($prefijoGuia->iso2) and $prefijoGuia->iso2 != '') ? $prefijoGuia->iso2 : 'CO';
                for ($i = 1; $i <= $sumarCaracteres; $i++) {
                    $prefijo = $prefijo . $carcater;
                }
                // $data->num_guia = trim($prefijo . $documento->consecutivo . $paquete . $prefijoPais);
                $data->num_guia = trim($prefijo . $documento->consecutivo . $paquete);
                $data->paquete  = (($documentoD) ? $documentoD->paquete + 1 : 1);

                /* GENERAR NUMERO DE WAREHOUSE */
                $data->num_warehouse = trim($documento->num_warehouse . '' . $paquete);
                if ($documento->liquidado === 1) {
                    $data->liquidado = 1;
                }
                if ($data->save()) {
                    /* INSERTAR TRAKCING*/
                    if ($request->ids_tracking != '') {
                        $this->addTrackingsToDocument($request->ids_tracking, $data->id, $request->consignee_id);
                    }
                    /* INSERTAR EN STATUS_DETALLE*/
                    DB::table('status_detalle')->insert([
                        [
                            'status_id'            => $data->status_id,
                            'usuario_id'           => Auth::user()->id,
                            'documento_detalle_id' => $data->id,
                            'codigo'               => $data->num_warehouse,
                            'fecha_status'         => date('Y-m-d H:i:s'),
                            'created_at'           => date('Y-m-d H:i:s'),
                        ],
                    ]);

                    /* INSERTAR CAMPO AGRUPADO */
                    $data->agrupado = $data->id;
                    $data->save();

                    $detalle = DocumentoDetalle::join('documento', 'documento_detalle.documento_id', '=', 'documento.id')
                        ->leftJoin('shipper', 'documento_detalle.shipper_id', '=', 'shipper.id')
                        ->leftJoin('consignee', 'documento_detalle.consignee_id', '=', 'consignee.id')
                        ->join('agencia', 'documento.agencia_id', '=', 'agencia.id')
                        ->leftJoin('posicion_arancelaria', 'documento_detalle.posicion_arancelaria_id', '=', 'posicion_arancelaria.id')
                        ->join('maestra_multiple', 'documento_detalle.tipo_empaque_id', '=', 'maestra_multiple.id')
                        ->select('documento_detalle.*', 'agencia.descripcion AS nom_agencia', 'posicion_arancelaria.pa AS nom_pa', 'posicion_arancelaria.id AS id_pa', 'shipper.nombre_full AS ship_nomfull', 'consignee.nombre_full AS cons_nomfull', 'maestra_multiple.nombre AS empaque', DB::raw("(SELECT Count(a.id) FROM tracking AS a WHERE a.documento_detalle_id = documento_detalle.id AND a.deleted_at IS NULL) as cantidad"))
                        ->where([['documento_detalle.deleted_at', null], ['documento_detalle.id', $data->id]])
                        ->first();
                    $this->AddToLog('Documento detalle insertado (' . $data->id . ')');
                    $answer = array(
                        "datos"  => $detalle,
                        "code"   => 200,
                        "status" => 200,
                    );

                    //insercion de puntos para CUBA
                    if ($request->points) {
                        foreach ($request->points as $key => $value) {
                            $id = DB::table('pivot_puntos_detalle')->insertGetId([
                                'puntos_id' => $value['producto_id'],
                                'documento_detalle_id' => $data->id,
                                'cantidad' => $value['cantidad'],
                                'total_puntos' => $value['cantidad'] * $value['puntos']
                            ]);
                        }
                    }
                } else {
                    $answer = array(
                        "error"  => 'Error al intentar Eliminar el registro.',
                        "code"   => 600,
                        "status" => 500,
                    );
                }
            }
            return $answer;
        } catch (\Exception $e) {
            return $e;
            $error = '';
            // foreach ($e->errorInfo as $key => $value) {
            //     $error .= $key . ' - ' . $value . ' <br> ';
            // }
            $answer = array(
                "error"  => $e,
                "code"   => 600,
                "status" => 500,
            );
            return $answer;
        }
    }

    public function addTrackingsToDocument($ids, $id_detalle, $id_consignee)
    {
        DB::table('tracking')
            ->whereIn('id', $ids)
            ->update(['documento_detalle_id' => $id_detalle], ['consignee_id' => $id_consignee]);
        return true;
    }

    public function editDetail(Request $request)
    {
        try {
            $data = DocumentoDetalle::findOrFail($request->id);

            $array = explode(" ", $request->dimensiones);

            $data->dimensiones  = $request->peso . ' ' . $array[1];
            $data->shipper_id   = $request->shipper_id;
            $data->consignee_id = $request->consignee_id;
            $data->arancel_id2  = $request->arancel_id2;
            $data->contenido    = $request->contenido;
            $data->contenido2   = $request->contenido2;
            $data->tracking     = $request->tracking;
            $data->valor        = $request->valor;
            $data->declarado2   = $request->declarado2;
            $data->peso         = $request->peso;
            $data->peso2        = $request->peso2;
            if ($request->liquidado) {
                $data->liquidado = 1;
            }

            if ($data->save()) {
                $this->AddToLog('Documento detalle editado (' . $data->id . ')');
                $answer = array(
                    "datos"  => $data,
                    "code"   => 200,
                    "status" => 200,
                );
            } else {
                $answer = array(
                    "error"  => 'Error al intentar Eliminar el registro.',
                    "code"   => 600,
                    "status" => 500,
                );
            }
            return $answer;
        } catch (\Exception $e) {
            $error = '';
            foreach ($e->errorInfo as $key => $value) {
                $error .= $key . ' - ' . $value . ' <br> ';
            }
            $answer = array(
                "error"  => $error,
                "code"   => 600,
                "status" => 500,
            );
            return $answer;
        }
    }

    public function additionalChargues(Request $request)
    {
        try {
            $id = DB::table('cargos_adicionales_detalle')->insertGetId(
                [
                    'documento_id' => $request->documento_id,
                    'concepto'     => $request->concepto,
                    'precio'       => $request->precio,
                    'cantidad'     => $request->cantidad,
                    'total'        => $request->total,
                    'created_at'   => date('Y-m-d H:i:s'),
                ]
            );
            $this->AddToLog('Cargos adicionales insertado al documento (' . $request->documento_id . ')');
            $data = DB::table('cargos_adicionales_detalle AS a')
                ->select(
                    '*'
                )
                ->where('a.documento_id', $request->documento_id)
                ->get();
            if ($data) {
                $answer = array(
                    "data"   => $data,
                    "code"   => 200,
                    "status" => 200,
                );
            } else {
                $answer = array(
                    "error"  => 'Error al intentar Eliminar el registro.',
                    "code"   => 600,
                    "status" => 500,
                );
            }
            return $answer;
        } catch (\Exception $e) {
            $error = '';
            if ($e->errorInfo != null) {
                foreach ($e->errorInfo as $key => $value) {
                    $error .= $key . ' - ' . $value . ' <br> ';
                }
            } else {
                $error = $e;
            }
            $answer = array(
                "error"  => $error,
                "code"   => 600,
                "status" => 500,
            );
            return $answer;
        }
    }

    public function additionalCharguesDelete($documento_id, $id_chargue)
    {
        DB::table('cargos_adicionales_detalle')->where('id', $id_chargue)->delete();
        $answer = array(
            "code" => 200,
        );
        $this->AddToLog('Cargos adicionales eliminado del documento (' . $documento_id . ') cargo adicional id (' . $id_chargue . ')');
        return $answer;
    }

    public function additionalCharguesGetAll($documento_id)
    {
        $datos  = DB::table('cargos_adicionales_detalle')->where('documento_id', $documento_id)->get();
        $answer = array(
            "code" => 200,
            "data" => $datos,
        );
        return $answer;
    }

    public function pdfContrato()
    {
        $pdf = PDF::loadView('pdf.contratoPdf');
        $this->AddToLog('Impresion Contrato');
        return $pdf->stream('contrato.pdf');
    }

    public function pdfTsa($carrier_id)
    {
        // $agencia2 = $this->getDataAgenciaById(1);
        $data = Transportador::findOrFail($carrier_id);
        $pdf     = PDF::loadView('pdf.tsaPdf', compact('data'));
        $this->AddToLog('Impresion TSA');
        return $pdf->stream('TSA.pdf');
    }

    public function pdf($id, $document, $id_detalle = null, $view = true)
    {

        $documento = DB::table('documento')
            ->leftJoin('localizacion AS ciudad_document', 'documento.ciudad_id', '=', 'ciudad_document.id')
            ->leftJoin('deptos AS deptos_documento', 'ciudad_document.deptos_id', '=', 'deptos_documento.id')
            ->leftJoin('master AS m', 'documento.master_id', '=', 'm.id')
            ->leftJoin('aerolineas_aeropuertos AS aerolinea', 'm.aerolineas_id', '=', 'aerolinea.id')
            ->leftJoin('aerolineas_aeropuertos AS aeropuerto', 'm.aeropuertos_id', '=', 'aeropuerto.id')
            ->leftJoin('transportador', 'transportador.id', '=', 'm.consignee_id')
            ->leftJoin('shipper', 'documento.shipper_id', '=', 'shipper.id')
            ->leftJoin('consignee', 'documento.consignee_id', '=', 'consignee.id')
            ->leftJoin('clientes', 'consignee.cliente_id', '=', 'clientes.id')
            ->leftJoin('localizacion AS ciudad_cliente', 'clientes.localizacion_id', '=', 'ciudad_cliente.id')
            ->leftJoin('deptos AS deptos_cliente', 'ciudad_cliente.deptos_id', '=', 'deptos_cliente.id')
            ->leftJoin('pais AS pais_cliente', 'deptos_cliente.pais_id', '=', 'pais_cliente.id')
            ->leftJoin('localizacion AS ciudad_consignee', 'consignee.localizacion_id', '=', 'ciudad_consignee.id')
            ->leftJoin('localizacion AS ciudad_shipper', 'shipper.localizacion_id', '=', 'ciudad_shipper.id')
            ->leftJoin('deptos AS deptos_consignee', 'ciudad_consignee.deptos_id', '=', 'deptos_consignee.id')
            ->leftJoin('deptos AS deptos_shipper', 'ciudad_shipper.deptos_id', '=', 'deptos_shipper.id')
            ->leftJoin('guia_wrh_pivot', 'guia_wrh_pivot.documento_id', '=', 'documento.id')
            ->leftJoin('servicios', 'guia_wrh_pivot.servicios_id', '=', 'servicios.id')
            ->leftJoin('maestra_multiple as embarque', 'guia_wrh_pivot.tipo_embarque_id', '=', 'embarque.id')
            ->leftJoin('maestra_multiple as forma_pago', 'guia_wrh_pivot.forma_pago_id', '=', 'forma_pago.id')
            ->leftJoin('maestra_multiple as tipo_pago', 'guia_wrh_pivot.tipo_pago_id', '=', 'tipo_pago.id')
            ->leftJoin('maestra_multiple as grupo', 'guia_wrh_pivot.grupo_id', '=', 'grupo.id')
            ->join('agencia', 'documento.agencia_id', '=', 'agencia.id')
            ->leftJoin('localizacion AS ciudad_agencia', 'agencia.localizacion_id', '=', 'ciudad_agencia.id')
            ->leftJoin('deptos AS deptos_agencia', 'ciudad_agencia.deptos_id', '=', 'deptos_agencia.id')
            ->leftJoin('pais AS pais_agencia', 'deptos_agencia.pais_id', '=', 'pais_agencia.id')
            ->join('users', 'documento.usuario_id', '=', 'users.id')
            ->join('tipo_documento', 'documento.tipo_documento_id', '=', 'tipo_documento.id')
            ->leftJoin('transportador AS tra', 'documento.central_destino_id', '=', 'tra.id')
            ->select(
                'documento.*',
                'users.name as usuario',
                'deptos_documento.pais_id AS pais_id_document',
                'shipper.nombre_full as ship_nomfull',
                'shipper.direccion as ship_dir',
                'shipper.telefono as ship_tel',
                'shipper.correo as ship_email',
                'shipper.zip as ship_zip',
                'ciudad_shipper.nombre AS ship_ciudad',
                'deptos_shipper.descripcion AS ship_depto',
                'consignee.nombre_full as cons_nomfull',
                'consignee.direccion as cons_dir',
                'consignee.telefono as cons_tel',
                'consignee.documento as cons_documento',
                'consignee.correo as cons_email',
                'consignee.zip as cons_zip',
                'consignee.po_box as cons_pobox',
                'clientes.nombre as cliente',
                'clientes.zona as cliente_zona',
                'ciudad_cliente.nombre as cliente_ciudad',
                'pais_cliente.descripcion as cliente_pais',
                'ciudad_consignee.nombre AS cons_ciudad',
                'deptos_consignee.descripcion AS cons_depto',
                'agencia.descripcion as agencia',
                'agencia.telefono as agencia_tel',
                'agencia.direccion as agencia_dir',
                'agencia.zip as agencia_zip',
                'agencia.email as agencia_email',
                'agencia.logo as agencia_logo',
                'ciudad_agencia.nombre AS agencia_ciudad',
                'ciudad_agencia.prefijo AS agencia_ciudad_prefijo',
                'deptos_agencia.descripcion AS agencia_depto',
                'deptos_agencia.abreviatura AS agencia_depto_prefijo',
                'pais_agencia.descripcion AS agencia_pais',
                'embarque.nombre as tipo_embarque',
                'embarque.id as tipo_embarque_id',
                'forma_pago.nombre as forma_pago',
                'tipo_pago.nombre as tipo_pago',
                'grupo.nombre as grupo',
                'servicios.nombre AS servicio',
                'tipo_documento.nombre AS tipo_documento',
                'm.num_master',
                'm.fecha_vuelo1 AS fecha_vuelo',
                'aerolinea.nombre AS aerolinea',
                'aeropuerto.nombre AS aeropuerto',
                'transportador.nombre AS consignee_master',
                'transportador.ciudad AS ciudad_destino',
                'tra.nombre AS trans_nom',
                'tra.direccion AS trans_dir',
                'tra.telefono AS trans_tel'
            )
            ->where([
                ['documento.deleted_at', null],
                ['documento.id', $id],
            ])
            ->first();

        $puntos = $this->getConfig('puntos_' . $documento->agencia_id);
        $config_copies = $this->getConfig('copies_invoice');
        $pais_id_puntos = 0;
        if ($puntos) {
            $puntos_value = json_decode($puntos->value);
            $pais_id_puntos = $puntos_value->pais_id;
        }

        $where = [['documento_detalle.deleted_at', null], ['documento_detalle.documento_id', $id]];
        if ($id_detalle != null) {
            $where[] = array('documento_detalle.id', $id_detalle);
        }

        $detalle = DocumentoDetalle::join('documento', 'documento_detalle.documento_id', '=', 'documento.id')
            ->leftJoin('shipper', 'documento_detalle.shipper_id', '=', 'shipper.id')
            ->leftJoin('consignee', 'documento_detalle.consignee_id', '=', 'consignee.id')
            ->leftJoin('posicion_arancelaria', 'documento_detalle.posicion_arancelaria_id', '=', 'posicion_arancelaria.id')
            ->leftJoin('localizacion AS ciudad_consignee', 'consignee.localizacion_id', '=', 'ciudad_consignee.id')
            ->leftJoin('localizacion AS ciudad_shipper', 'shipper.localizacion_id', '=', 'ciudad_shipper.id')
            ->leftJoin('deptos AS deptos_consignee', 'ciudad_consignee.deptos_id', '=', 'deptos_consignee.id')
            ->leftJoin('deptos AS deptos_shipper', 'ciudad_shipper.deptos_id', '=', 'deptos_shipper.id')
            ->join('maestra_multiple', 'documento_detalle.tipo_empaque_id', '=', 'maestra_multiple.id')
            ->select(
                'documento_detalle.*',
                'posicion_arancelaria.pa AS nom_pa',
                'maestra_multiple.nombre AS empaque',
                'shipper.nombre_full as ship_nomfull',
                'shipper.direccion as ship_dir',
                'shipper.telefono as ship_tel',
                'shipper.correo as ship_email',
                'shipper.zip as ship_zip',
                'ciudad_shipper.nombre AS ship_ciudad',
                'deptos_shipper.descripcion AS ship_depto',
                'consignee.nombre_full as cons_nomfull',
                'consignee.direccion as cons_dir',
                'consignee.telefono as cons_tel',
                'consignee.documento as cons_documento',
                'consignee.correo as cons_email',
                'consignee.zip as cons_zip',
                'consignee.po_box as cons_pobox',
                'ciudad_consignee.nombre AS cons_ciudad',
                'deptos_consignee.descripcion AS cons_depto',
                DB::raw('(SELECT
                group_concat(a.codigo) AS trackings
                FROM
                tracking AS a
                WHERE
                a.documento_detalle_id = documento_detalle.id) AS trackings')
            )
            ->where($where)
            ->get();
        if ($document === 'invoice_guia') {
            $this->AddToLog('Impresion Invoice (' . $documento->id . ')');
            if (env('APP_CLIENT') === 'colombiana') {
                /* para colombiana se saca en carta la impresion */
                $pdf          = PDF::loadView('pdf.invoicePdf', compact('config_copies', 'documento', 'detalle'));
            } else {
                $pdf          = PDF::loadView('pdf.invoicePdf', compact('config_copies', 'documento', 'detalle'));
            }
            $nameDocument = 'comercial invoice -' . $documento->id;
        } else {
            if ($document === 'guia') {
                $this->AddToLog('Impresion Guia (' . $documento->id . ')');
                if (env('APP_TYPE') === 'courier') {
                    if (env('APP_CLIENT') === 'colombiana') {
                        $pdf = PDF::loadView('pdf.invoice_guia', compact('documento', 'detalle'));
                    } else {
                        $pdf = PDF::loadView('pdf.guiaPdf', compact('documento', 'detalle'));
                    }
                } else {
                    if (env('APP_CLIENT') === 'jexpress') {
                        $pdf = PDF::loadView('pdf.warehousePdfJexpress', compact('documento', 'detalle'));
                    } else {
                        $pdf = PDF::loadView('pdf.invoice_guia', compact('documento', 'detalle'));
                    }
                }
                $nameDocument = $documento->num_warehouse;
            } else {
                if ($document === 'warehouse') {
                    $this->AddToLog('Impresion warehouse (' . $documento->id . ')');
                    if (env('APP_TYPE') === 'courier') {
                        if (env('APP_CLIENT') === 'colombiana') {
                            //     $pdf = PDF::loadView('pdf.invoice_guia', compact('documento', 'detalle'));
                            // }else{
                            $pdf = PDF::loadView('pdf.warehousePdf', compact('documento', 'detalle'));
                        } else {
                            $pdf = PDF::loadView('pdf.warehousePdf', compact('documento', 'detalle'));
                        }
                    } else {
                        if (env('APP_CLIENT') === 'jexpress') {
                            $pdf = PDF::loadView('pdf.warehousePdfJexpress', compact('documento', 'detalle'));
                        } else {
                            $pdf = PDF::loadView('pdf.invoice_guia', compact('documento', 'detalle'));
                        }
                    }
                    $nameDocument = $documento->num_warehouse;
                } else {
                    if ($document === 'invoice') {
                        $detalleConsolidado = DB::table('consolidado_detalle as a')
                            ->leftJoin('documento_detalle as b', 'a.documento_detalle_id', '=', 'b.id')
                            ->leftJoin('shipper as c', 'b.shipper_id', '=', 'c.id')
                            ->leftJoin('consignee as d', 'b.consignee_id', '=', 'd.id')
                            ->leftJoin('localizacion as e', 'c.localizacion_id', '=', 'e.id')
                            ->leftJoin('deptos as f', 'e.deptos_id', '=', 'f.id')
                            ->leftJoin('pais', 'f.pais_id', '=', 'pais.id')
                            ->leftJoin('localizacion as g', 'd.localizacion_id', '=', 'g.id')
                            ->leftJoin('deptos as h', 'e.deptos_id', '=', 'h.id')
                            ->leftJoin('pais as i', 'h.pais_id', '=', 'i.id')
                            ->leftJoin(DB::raw("(SELECT
                                        s.id,
                                        s.nombre_full,
                                        s.direccion,
                                        s.telefono,
                                        s.correo,
                                        s.zip,
                                        l.nombre AS ciudad,
                                        de.descripcion AS depto,
                                        pa.descripcion AS pais
                                        FROM
                                        shipper AS s
                                        INNER JOIN localizacion AS l ON l.id = s.localizacion_id
                                        INNER JOIN deptos AS de ON l.deptos_id = de.id
                                        INNER JOIN pais AS pa ON de.pais_id = pa.id
                                        ) AS ss"), 'a.shipper', 'ss.id')
                            ->leftJoin(DB::raw("(SELECT
                                        cc.id,
                                        cc.nombre_full,
                                        cc.direccion,
                                        cc.telefono,
                                        cc.correo,
                                        cc.zip,
                                        ll.nombre AS ciudad,
                                        dep.descripcion AS depto,
                                        pai.descripcion AS pais
                                        FROM
                                        consignee AS cc
                                        INNER JOIN localizacion AS ll ON ll.id = cc.localizacion_id
                                        INNER JOIN deptos AS dep ON ll.deptos_id = dep.id
                                        INNER JOIN pais AS pai ON dep.pais_id = pai.id
                                        ) AS cc"), 'a.consignee', 'cc.id')
                            ->leftJoin(DB::raw("(SELECT
                                          z.agrupado,
                                          SUM(x.peso) AS peso,
                                          SUM(x.peso2) AS peso2,
                                          GROUP_CONCAT(

                                              IF (
                                                  z.flag = 1,
                                                  CONCAT(

                                                      IF (
                                                          x.liquidado = 1,
                                                          CONCAT('<label>- ', x.num_guia, \"</label><a style='float: right;cursor:pointer;color:red' title='Quitar' data-toggle='tooltip' onclick='removerGuiaAgrupada(\",z.id,\")'><i class='fa fa-times' style='font-size: 15px;'></i></a>\"),
                                                          CONCAT('<label>- ', x.num_warehouse, \"</label><a style='float: right;cursor:pointer;color:red' title='Quitar' data-toggle='tooltip' onclick='removerGuiaAgrupada(\",z.id,\")'><i class='fa fa-times' style='font-size: 15px;'></i></a>\")
                                                      )
                                                  ),
                                                  NULL
                                              )
                                          ) AS guias_agrupadas
                                      FROM
                                          consolidado_detalle AS z
                                      INNER JOIN documento_detalle AS x ON z.documento_detalle_id = x.id
                                      WHERE
                                          z.deleted_at IS NULL
                                      AND x.deleted_at IS NULL
                                      GROUP BY
                                          z.agrupado
                                  ) AS j"), 'a.agrupado', 'j.agrupado')
                            ->select(
                                'a.num_bolsa',
                                'a.shipper AS shipper_json',
                                'a.consignee AS consignee_json',
                                'b.num_warehouse',
                                'b.num_guia',
                                'c.nombre_full as ship_nomfull',
                                'c.direccion as ship_dir',
                                'c.telefono as ship_tel',
                                'c.zip as ship_zip',
                                'e.nombre as ship_ciudad',
                                'f.descripcion as ship_depto',
                                'pais.descripcion as ship_pais',
                                'd.nombre_full as cons_nomfull',
                                'd.zip as cons_zip',
                                'g.nombre as cons_ciudad',
                                'h.descripcion as cons_depto',
                                'd.direccion as cons_dir',
                                'd.telefono as cons_tel',
                                'i.descripcion as cons_pais',
                                'b.declarado2',
                                'j.peso2',
                                'b.contenido2',
                                'b.liquidado',
                                'ss.nombre_full AS ship_nomfull2',
                                'ss.direccion as ship_dir2',
                                'ss.telefono as ship_tel2',
                                'ss.zip as ship_zip2',
                                'ss.ciudad as ship_ciudad2',
                                'ss.depto as ship_depto2',
                                'ss.pais as ship_pais2',
                                'cc.nombre_full as cons_nomfull2',
                                'cc.zip as cons_zip2',
                                'cc.ciudad as cons_ciudad2',
                                'cc.depto as cons_depto2',
                                'cc.direccion as cons_dir2',
                                'cc.telefono as cons_tel2',
                                'cc.pais as cons_pais2'
                            )
                            ->where([['a.deleted_at', null], ['a.documento_detalle_id', $id_detalle], ['a.flag', 0]])
                            ->get();
                        $this->AddToLog('Impresion Invoice (' . $documento->id . ')');

                        $pdf          = PDF::loadView('pdf.invoicePdf', compact('config_copies', 'documento', 'detalle', 'detalleConsolidado'));

                        $nameDocument = 'comercial invoice -' . $documento->id;
                    } else {
                        if ($document === 'consolidado_guias') {
                            $detalleConsolidado = DB::table('consolidado_detalle as a')
                                ->leftJoin('documento_detalle as b', 'a.documento_detalle_id', '=', 'b.id')
                                ->leftJoin('posicion_arancelaria as pa', 'b.arancel_id2', 'pa.id')
                                ->leftJoin('shipper as c', 'b.shipper_id', '=', 'c.id')
                                ->leftJoin('consignee as d', 'b.consignee_id', '=', 'd.id')
                                ->leftJoin('localizacion as e', 'c.localizacion_id', '=', 'e.id')
                                ->leftJoin('deptos as f', 'e.deptos_id', '=', 'f.id')
                                ->leftJoin('pais', 'f.pais_id', '=', 'pais.id')
                                ->leftJoin('localizacion as g', 'd.localizacion_id', '=', 'g.id')
                                ->leftJoin('deptos as h', 'e.deptos_id', '=', 'h.id')
                                ->leftJoin('pais as i', 'h.pais_id', '=', 'i.id')
                                ->leftJoin(DB::raw("(SELECT
                                        s.id,
                                        s.nombre_full,
                                        s.direccion,
                                        s.telefono,
                                        s.correo,
                                        s.zip,
                                        l.nombre AS ciudad,
                                        de.descripcion AS depto,
                                        pa.descripcion AS pais
                                        FROM
                                        shipper AS s
                                        INNER JOIN localizacion AS l ON l.id = s.localizacion_id
                                        INNER JOIN deptos AS de ON l.deptos_id = de.id
                                        INNER JOIN pais AS pa ON de.pais_id = pa.id
                                        ) AS ss"), 'a.shipper', 'ss.id')
                                ->leftJoin(DB::raw("(SELECT
                                            cc.id,
                                            cc.nombre_full,
                                            cc.direccion,
                                            cc.telefono,
                                            cc.correo,
                                            cc.zip,
                                            ll.nombre AS ciudad,
                                            dep.descripcion AS depto,
                                            pai.descripcion AS pais
                                            FROM
                                            consignee AS cc
                                            INNER JOIN localizacion AS ll ON ll.id = cc.localizacion_id
                                            INNER JOIN deptos AS dep ON ll.deptos_id = dep.id
                                            INNER JOIN pais AS pai ON dep.pais_id = pai.id
                                            ) AS cc"), 'a.consignee', 'cc.id')
                                ->leftJoin(DB::raw("(SELECT
                                            z.agrupado,
                                            SUM(x.peso) AS peso,
                                            SUM(x.peso2) AS peso2,
                                            GROUP_CONCAT(

                                                IF (
                                                    z.flag = 1,
                                                    CONCAT(

                                                        IF (
                                                            x.liquidado = 1,
                                                            CONCAT('<label>- ', x.num_guia, \"</label><a style='float: right;cursor:pointer;color:red' title='Quitar' data-toggle='tooltip' onclick='removerGuiaAgrupada(\",z.id,\")'><i class='fa fa-times' style='font-size: 15px;'></i></a>\"),
                                                            CONCAT('<label>- ', x.num_warehouse, \"</label><a style='float: right;cursor:pointer;color:red' title='Quitar' data-toggle='tooltip' onclick='removerGuiaAgrupada(\",z.id,\")'><i class='fa fa-times' style='font-size: 15px;'></i></a>\")
                                                        )
                                                    ),
                                                    NULL
                                                )
                                            ) AS guias_agrupadas
                                        FROM
                                            consolidado_detalle AS z
                                        INNER JOIN documento_detalle AS x ON z.documento_detalle_id = x.id
                                        WHERE
                                            z.deleted_at IS NULL
                                        AND x.deleted_at IS NULL
                                        GROUP BY
                                            z.agrupado
                                    ) AS j"), 'a.agrupado', 'j.agrupado')
                                ->select(
                                    'a.num_bolsa',
                                    'a.shipper AS shipper_json',
                                    'a.consignee AS consignee_json',
                                    'b.num_warehouse',
                                    'b.num_guia',
                                    'b.piezas',
                                    'b.volumen',
                                    'c.nombre_full as ship_nomfull',
                                    'c.direccion as ship_dir',
                                    'c.telefono as ship_tel',
                                    'c.zip as ship_zip',
                                    'e.nombre as ship_ciudad',
                                    'f.descripcion as ship_depto',
                                    'f.abreviatura as ship_depto_ab',
                                    'pais.descripcion as ship_pais',
                                    'd.nombre_full as cons_nomfull',
                                    'd.zip as cons_zip',
                                    'g.nombre as cons_ciudad',
                                    'h.descripcion as cons_depto',
                                    'h.abreviatura as cons_depto_ab',
                                    'd.direccion as cons_dir',
                                    'd.telefono as cons_tel',
                                    'i.descripcion as cons_pais',
                                    'b.declarado2',
                                    'j.peso2',
                                    'b.contenido2',
                                    'b.liquidado',
                                    'pa.pa',
                                    DB::raw('(SELECT
                                      ROUND(Sum(b.peso2) * 0.453592) AS peso_total
                                    FROM
                                      consolidado_detalle AS a
                                    INNER JOIN documento_detalle AS b ON a.documento_detalle_id = b.id
                                    WHERE
                                      a.deleted_at IS NULL
                                    AND b.deleted_at IS NULL
                                    AND b.consignee_id = d.id
                                  ) AS peso_total'),
                                    DB::raw('(SELECT
                                      Sum(b.declarado2) AS declarado_total
                                    FROM
                                      consolidado_detalle AS a
                                    INNER JOIN documento_detalle AS b ON a.documento_detalle_id = b.id
                                    WHERE
                                      a.deleted_at IS NULL
                                    AND b.deleted_at IS NULL
                                    AND b.consignee_id = d.id
                                  ) AS declarado_total'),
                                    'ss.nombre_full AS ship_nomfull2',
                                    'ss.direccion as ship_dir2',
                                    'ss.telefono as ship_tel2',
                                    'ss.zip as ship_zip2',
                                    'ss.ciudad as ship_ciudad2',
                                    'ss.depto as ship_depto2',
                                    'ss.pais as ship_pais2',
                                    'cc.nombre_full as cons_nomfull2',
                                    'cc.zip as cons_zip2',
                                    'cc.ciudad as cons_ciudad2',
                                    'cc.depto as cons_depto2',
                                    'cc.direccion as cons_dir2',
                                    'cc.telefono as cons_tel2',
                                    'cc.pais as cons_pais2'
                                )
                                ->where([['a.deleted_at', null], ['a.consolidado_id', $id], ['a.flag', 0]])
                                ->orderBy('b.num_warehouse', 'ASC')
                                ->get();
                            // VALIDAR QUE EL PESO Y EL DECLARADO NO SUPEREN LO MAXIMO ESTABLECIDO
                            $peso_t = 0;
                            $decla_t = 0;
                            if ($peso_t === 0 and $decla_t === 0) {
                                $this->AddToLog('Impresion Consolidado guias (' . $id . ')');
                                if ($documento->transporte_id == 1) {
                                    if (env('APP_TYPE') === 'courier') {
                                        if (env('APP_CLIENT') === 'colombiana') {
                                            return view('pdf/consolidadoGuiasPdf2', compact('documento', 'detalle', 'detalleConsolidado'));
                                            // $pdf = PDF::loadView('pdf.consolidadoGuiasPdf2', compact('documento', 'detalle', 'detalleConsolidado'));
                                        } else {
                                            $pdf = PDF::loadView('pdf.consolidadoGuiasPdf', compact('documento', 'detalle', 'detalleConsolidado'));
                                        }
                                    } else {
                                        $pdf = PDF::loadView('pdf.consolidadoGuiasPdf2', compact('documento', 'detalle', 'detalleConsolidado'));
                                    }
                                } else {
                                    return view('pdf.manifiesto.guiasCuba', compact('documento', 'detalle', 'detalleConsolidado'));
                                }
                                $nameDocument = 'Guias -' . $documento->id;
                            } else {
                                $error = 'El peso o valor declarado supera lo permitido por cliente. Por favor revisar.';
                                return view('errors/generalError', compact('error'));
                            }
                        } else {
                            if ($document === 'consolidado') {
                                $detalleConsolidado = DB::table('consolidado_detalle as a')
                                    ->leftJoin('documento_detalle as b', 'a.documento_detalle_id', '=', 'b.id')
                                    ->leftJoin('shipper as c', 'b.shipper_id', '=', 'c.id')
                                    ->leftJoin('consignee as d', 'b.consignee_id', '=', 'd.id')
                                    ->leftJoin('localizacion as e', 'c.localizacion_id', '=', 'e.id')
                                    ->leftJoin('deptos as f', 'e.deptos_id', '=', 'f.id')
                                    ->leftJoin('pais', 'f.pais_id', '=', 'pais.id')
                                    ->leftJoin('localizacion as g', 'd.localizacion_id', '=', 'g.id')
                                    ->leftJoin('deptos as h', 'e.deptos_id', '=', 'h.id')
                                    ->leftJoin('pais as i', 'h.pais_id', '=', 'i.id')
                                    ->leftJoin(DB::raw("(SELECT
                                        s.id,
                                        s.nombre_full,
                                        s.direccion,
                                        s.telefono,
                                        s.correo,
                                        s.zip,
                                        l.nombre AS ciudad,
                                        de.descripcion AS depto,
                                        pa.descripcion AS pais
                                        FROM
                                        shipper AS s
                                        INNER JOIN localizacion AS l ON l.id = s.localizacion_id
                                        INNER JOIN deptos AS de ON l.deptos_id = de.id
                                        INNER JOIN pais AS pa ON de.pais_id = pa.id
                                        ) AS ss"), 'a.shipper', 'ss.id')
                                    ->leftJoin(DB::raw("(SELECT
                                        cc.id,
                                        cc.nombre_full,
                                        cc.direccion,
                                        cc.telefono,
                                        cc.correo,
                                        cc.zip,
                                        ll.nombre AS ciudad,
                                        dep.descripcion AS depto,
                                        pai.descripcion AS pais
                                        FROM
                                        consignee AS cc
                                        INNER JOIN localizacion AS ll ON ll.id = cc.localizacion_id
                                        INNER JOIN deptos AS dep ON ll.deptos_id = dep.id
                                        INNER JOIN pais AS pai ON dep.pais_id = pai.id
                                        ) AS cc"), 'a.consignee', 'cc.id')
                                    ->leftJoin(DB::raw("(SELECT
                                          z.agrupado,
                                          SUM(x.peso) AS peso,
                                          SUM(x.peso2) AS peso2,
                                          GROUP_CONCAT(

                                              IF (
                                                  z.flag = 1,
                                                  CONCAT(

                                                      IF (
                                                          x.liquidado = 1,
                                                          CONCAT('<label>- ', x.num_guia, ' (', x.peso2, ' lbs) ', ' ($ ', x.declarado2, '.00) ', \"</label><a style='float: right;cursor:pointer;color:red' title='Quitar' data-toggle='tooltip' onclick='removerGuiaAgrupada(\",z.id,\")'><i class='fa fa-times' style='font-size: 15px;'></i></a>\"),
                                                          CONCAT('<label>- ', x.num_warehouse, ' (', x.peso2, ' lbs) ', ' ($ ', x.declarado2, '.00) ', \"</label><a style='float: right;cursor:pointer;color:red' title='Quitar' data-toggle='tooltip' onclick='removerGuiaAgrupada(\",z.id,\")'><i class='fa fa-times' style='font-size: 15px;'></i></a>\")
                                                      )
                                                  ),
                                                  NULL
                                              )
                                          ) AS guias_agrupadas
                                      FROM
                                          consolidado_detalle AS z
                                      INNER JOIN documento_detalle AS x ON z.documento_detalle_id = x.id
                                      WHERE
                                          z.deleted_at IS NULL
                                      AND x.deleted_at IS NULL
                                      GROUP BY
                                          z.agrupado
                                  ) AS j"), 'a.agrupado', 'j.agrupado')
                                    ->select(
                                        'a.num_bolsa',
                                        'a.shipper AS shipper_json',
                                        'a.consignee AS consignee_json',
                                        'b.num_warehouse',
                                        'b.num_guia',
                                        'b.volumen',
                                        'c.nombre_full as ship_nomfull',
                                        DB::raw('CONCAT_WS(" ", c . primer_nombre, c . segundo_nombre, c . primer_apellido, c . segundo_apellido) as nom_ship'),
                                        'c.direccion as dir_ship',
                                        'c.telefono as tel_ship',
                                        'c.zip as zip_ship',
                                        'e.nombre as ciu_ship',
                                        'f.descripcion as depto_ship',
                                        'pais.descripcion as pais_ship',
                                        'd.nombre_full as cons_nomfull',
                                        DB::raw('CONCAT_WS(" ", d . primer_nombre, d . segundo_nombre, d . primer_apellido, d . segundo_apellido) as nom_cons'),
                                        'd.zip as zip_cons',
                                        'g.nombre as ciu_cons',
                                        'h.descripcion as depto_cons',
                                        'd.direccion as dir_cons',
                                        'd.telefono as tel_cons',
                                        'i.descripcion as pais_cons',
                                        'b.declarado2',
                                        'j.peso2',
                                        'b.contenido2',
                                        'b.liquidado',
                                        'b.piezas',
                                        DB::raw('(SELECT
                                    			ROUND(Sum(b.peso2) * 0.453592) AS peso_total
                                    		FROM
                                    			consolidado_detalle AS a
                                    		INNER JOIN documento_detalle AS b ON a.documento_detalle_id = b.id
                                    		WHERE
                                    			a.deleted_at IS NULL
                                    		AND b.deleted_at IS NULL
                                    		AND b.consignee_id = d.id
                                    	) AS peso_total'),
                                        DB::raw('(SELECT
                                    			Sum(b.declarado2) AS declarado_total
                                    		FROM
                                    			consolidado_detalle AS a
                                    		INNER JOIN documento_detalle AS b ON a.documento_detalle_id = b.id
                                    		WHERE
                                    			a.deleted_at IS NULL
                                    		AND b.deleted_at IS NULL
                                    		AND b.consignee_id = d.id
                                        ) AS declarado_total'),

                                        'ss.nombre_full AS ship_nomfull2',
                                        'ss.direccion as ship_dir2',
                                        'ss.telefono as ship_tel2',
                                        'ss.zip as ship_zip2',
                                        'ss.ciudad as ship_ciudad2',
                                        'ss.depto as ship_depto2',
                                        'ss.pais as ship_pais2',
                                        'cc.nombre_full as cons_nomfull2',
                                        'cc.zip as cons_zip2',
                                        'cc.ciudad as cons_ciudad2',
                                        'cc.depto as cons_depto2',
                                        'cc.direccion as cons_dir2',
                                        'cc.telefono as cons_tel2',
                                        'cc.pais as cons_pais2'
                                    )
                                    ->where([['a.deleted_at', null], ['a.consolidado_id', $id], ['a.flag', 0]])
                                    ->orderBy('b.num_warehouse', 'ASC')
                                    ->get();

                                // VALIDAR QUE EL PESO Y EL DECLARADO NO SUPEREN LO MAXIMO ESTABLECIDO
                                $peso_t = 0;
                                $decla_t = 0;
                                if ($peso_t === 0 and $decla_t === 0) {
                                    $this->AddToLog('Impresion Consolidado (' . $id . ')');
                                    if (env('APP_TYPE') === 'courier') {
                                        if (env('APP_CLIENT') === 'colombiana') {
                                            $pdf          = PDF::loadView('pdf.consolidadoPdfColombiana', compact('documento', 'detalle', 'detalleConsolidado'));
                                        } else {
                                            if ($documento->pais_id_document === $pais_id_puntos) {
                                                //FORMATO PARA CUBA
                                                $pdf          = PDF::loadView('pdf.manifiesto.formatoCuba', compact('documento', 'detalle', 'detalleConsolidado'));
                                            } else {
                                                $pdf          = PDF::loadView('pdf.consolidadoPdf', compact('documento', 'detalle', 'detalleConsolidado'));
                                            }
                                        }
                                    } else {
                                        // return view('pdf/consolidadoPdfColombiana', compact('documento', 'detalle', 'detalleConsolidado'));
                                        // ESTE FORMATO ES PARA WORDCARGO
                                        if ($documento->transporte_id == 1) {
                                            $pdf          = PDF::loadView('pdf.consolidadoPdf2', compact('documento', 'detalle', 'detalleConsolidado'));
                                        }
                                        if ($documento->transporte_id == 2) {
                                            $pdf          = PDF::loadView('pdf.consolidadoPdf2Maritimo', compact('documento', 'detalle', 'detalleConsolidado'));
                                        }
                                    }
                                    $nameDocument = $documento->tipo_documento . '-' . $documento->id;
                                } else {
                                    $error = 'El peso o valor declarado supera lo permitido por cliente. Por favor revisar.';
                                    return view('errors/generalError', compact('error'));
                                }
                            }
                        }
                    }
                }
            }
        }
        if ($view) {
            return $pdf->stream($nameDocument . '.pdf'); //visualizar en el navegador
        } else {
            $pdf->save(public_path() . '/files/File.pdf'); //GUARDAR PARA IMPRIMIR POR DEFECTO
        }
        // return $pdf->download($nameDocument . ' . pdf');// DESCARGAR ARCHIVO
    }

    public function pdfLabel($id, $document, $id_detalle = null, $consolidado = null, $id_detail_consol = null)
    {
        if ($document === 'guia') {
            $codigo = 'num_guia';
        } else {
            if ($document === 'warehouse') {
                $codigo = 'num_warehouse';
            }
        }
        $documento = DB::table('documento')
            ->leftJoin('shipper', 'documento.shipper_id', 'shipper.id')
            ->leftJoin('consignee', 'documento.consignee_id', 'consignee.id')
            ->leftJoin('localizacion as ciudad_consignee', 'consignee.localizacion_id', 'ciudad_consignee.id')
            ->leftJoin('localizacion as ciudad_shipper', 'shipper.localizacion_id', 'ciudad_shipper.id')
            ->leftJoin('deptos as deptos_consignee', 'ciudad_consignee.deptos_id', 'deptos_consignee.id')
            ->leftJoin('deptos as deptos_shipper', 'ciudad_shipper.deptos_id', 'deptos_shipper.id')
            ->leftJoin('guia_wrh_pivot', 'guia_wrh_pivot.documento_id', 'documento.id')
            ->join('agencia', 'documento.agencia_id', 'agencia.id')
            ->leftJoin('localizacion AS ciudad_agencia', 'agencia.localizacion_id', '=', 'ciudad_agencia.id')
            ->leftJoin('deptos AS deptos_agencia', 'ciudad_agencia.deptos_id', '=', 'deptos_agencia.id')
            ->leftJoin('pais AS pais_agencia', 'deptos_agencia.pais_id', '=', 'pais_agencia.id')
            ->select(
                'documento.*',
                'shipper.nombre_full as ship_nomfull',
                'shipper.direccion as ship_dir',
                'shipper.telefono as ship_tel',
                'shipper.correo as ship_email',
                'shipper.zip as ship_zip',
                'ciudad_shipper.nombre as ship_ciudad',
                'deptos_shipper.descripcion as ship_depto',
                'consignee.nombre_full as cons_nomfull',
                'consignee.direccion as cons_dir',
                'consignee.telefono as cons_tel',
                'consignee.documento as cons_documento',
                'consignee.correo as cons_email',
                'consignee.zip as cons_zip',
                'consignee.po_box as cons_pobox',
                'ciudad_consignee.nombre as cons_ciudad',
                'deptos_consignee.descripcion as cons_depto',
                'agencia.descripcion as agencia',
                'agencia.logo as agencia_logo',
                'agencia.telefono as agencia_tel',
                'agencia.direccion as agencia_dir',
                'agencia.email as agencia_email',
                'agencia.zip as agencia_zip',
                'ciudad_agencia.nombre AS agencia_ciudad',
                'ciudad_agencia.prefijo AS agencia_ciudad_prefijo',
                'deptos_agencia.descripcion AS agencia_depto',
                'deptos_agencia.abreviatura AS agencia_depto_prefijo'
            )
            ->where([
                ['documento.deleted_at', null],
                ['documento.id', $id],
            ])
            ->first();

        $where = [['documento_detalle.deleted_at', null], ['documento_detalle.documento_id', $id]];
        if ($id_detalle != null) {
            $where[] = array('documento_detalle.id', $id_detalle);
        }
        $shipper = false;
        $consignee = false;
        if ($id_detail_consol != null) {
            $dato_consolidado = DB::table('consolidado_detalle AS a')
                ->select('a.consignee', 'a.shipper')
                ->where([['a.deleted_at', NULL], ['a.id', $id_detail_consol]])
                ->first();
            if ($dato_consolidado->shipper) {
                $shipper = DB::table("shipper AS s")
                    ->leftJoin('localizacion as l', 'l.id', 's.localizacion_id')
                    ->leftJoin('deptos as de', 'l.deptos_id', 'de.id')
                    ->leftJoin('pais AS pa', 'de.pais_id', 'pa.id')
                    ->select(
                        's.id',
                        's.nombre_full',
                        's.direccion',
                        's.telefono',
                        's.correo',
                        's.zip',
                        'l.nombre AS ciudad',
                        'de.descripcion AS depto',
                        'pa.descripcion AS pais'
                    )
                    ->where([['s.id', $dato_consolidado->shipper]])
                    ->first();
            }
            if ($dato_consolidado->consignee) {
                $consignee = DB::table("consignee AS cc")
                    ->leftJoin('localizacion as l', 'l.id', 'cc.localizacion_id')
                    ->leftJoin('deptos as de', 'l.deptos_id', 'de.id')
                    ->leftJoin('pais AS pa', 'de.pais_id', 'pa.id')
                    ->select(
                        'cc.id',
                        'cc.nombre_full',
                        'cc.direccion',
                        'cc.telefono',
                        'cc.correo',
                        'cc.zip',
                        'l.nombre AS ciudad',
                        'de.descripcion AS depto',
                        'pa.descripcion AS pais'
                    )
                    ->where([['cc.id', $dato_consolidado->consignee]])
                    ->first();
            }
        }
        $detalle = $this->pdfLabelDetail($where, $codigo, $consolidado, $id_detail_consol);

        $this->AddToLog('Impresion labels (' . $documento->id . ')');

        if (env('APP_CLIENT') === 'colombiana') {
            $pdf = PDF::loadView('pdf.labelWGJyg', compact('documento', 'detalle', 'document', 'dato_consolidado', 'shipper', 'consignee'))
                ->setPaper(array(25, -25, 260, 360), 'landscape');
            // $pdf = PDF::loadView('pdf.labelWGcolombiana', compact('documento', 'detalle', 'document', 'dato_consolidado', 'shipper', 'consignee' ))
            //     ->setPaper(array(25, -25, 300, 300), 'landscape');

            $nameDocument = 'Label' . $document . '-' . $documento->id;
            $pdf->save(public_path() . '/files/File.pdf');
        } else {
            if (env('APP_LABEL') === '4x4') {
                if (env('APP_CLIENT') === 'jyg') {
                    $pdf = PDF::loadView('pdf.labelWGJyg', compact('documento', 'detalle', 'document', 'consolidado', 'dato_consolidado', 'shipper', 'consignee'))
                        ->setPaper(array(25, -25, 260, 360), 'landscape');
                    $nameDocument = 'Label' . $document . '-' . $documento->id;
                    $pdf->save(public_path() . '/files/File.pdf');
                } else {
                    $pdf = PDF::loadView('pdf.labelWG', compact('documento', 'detalle', 'document', 'consolidado', 'dato_consolidado', 'shipper', 'consignee'))
                        ->setPaper(array(25, -25, 260, 360), 'landscape');
                }

                $nameDocument = 'Label' . $document . '-' . $documento->id;
            } else {
                return view('pdf/labelWG_2', compact('documento', 'detalle', 'document', 'consolidado', 'dato_consolidado', 'shipper', 'consignee'));
            }
        }
        return $pdf->stream($nameDocument . '.pdf');
    }

    public function pdfConsolidadoGroup($id, $document, $num_bolsa)
    {
        if ($document === 'guia') {
            $codigo = 'num_guia';
        } else {
            if ($document === 'warehouse') {
                $codigo = 'num_warehouse';
            }
        }
        $consolidado = null;
        $dato_consolidado = null;
        $documento = DB::table('documento')
            ->leftJoin('shipper', 'documento.shipper_id', 'shipper.id')
            ->leftJoin('consignee', 'documento.consignee_id', 'consignee.id')
            ->leftJoin('localizacion as ciudad_consignee', 'consignee.localizacion_id', 'ciudad_consignee.id')
            ->leftJoin('localizacion as ciudad_shipper', 'shipper.localizacion_id', 'ciudad_shipper.id')
            ->leftJoin('deptos as deptos_consignee', 'ciudad_consignee.deptos_id', 'deptos_consignee.id')
            ->leftJoin('deptos as deptos_shipper', 'ciudad_shipper.deptos_id', 'deptos_shipper.id')
            ->leftJoin('guia_wrh_pivot', 'guia_wrh_pivot.documento_id', 'documento.id')
            ->join('agencia', 'documento.agencia_id', 'agencia.id')
            ->leftJoin('localizacion AS ciudad_agencia', 'agencia.localizacion_id', '=', 'ciudad_agencia.id')
            ->leftJoin('deptos AS deptos_agencia', 'ciudad_agencia.deptos_id', '=', 'deptos_agencia.id')
            ->leftJoin('pais AS pais_agencia', 'deptos_agencia.pais_id', '=', 'pais_agencia.id')
            ->select(
                'documento.*',
                'shipper.nombre_full as ship_nomfull',
                'shipper.direccion as ship_dir',
                'shipper.telefono as ship_tel',
                'shipper.correo as ship_email',
                'shipper.zip as ship_zip',
                'ciudad_shipper.nombre as ship_ciudad',
                'deptos_shipper.descripcion as ship_depto',
                'consignee.nombre_full as cons_nomfull',
                'consignee.direccion as cons_dir',
                'consignee.telefono as cons_tel',
                'consignee.documento as cons_documento',
                'consignee.correo as cons_email',
                'consignee.zip as cons_zip',
                'consignee.po_box as cons_pobox',
                'ciudad_consignee.nombre as cons_ciudad',
                'deptos_consignee.descripcion as cons_depto',
                'agencia.descripcion as agencia',
                'agencia.logo as agencia_logo',
                'agencia.telefono as agencia_tel',
                'agencia.direccion as agencia_dir',
                'agencia.email as agencia_email',
                'agencia.zip as agencia_zip',
                'ciudad_agencia.nombre AS agencia_ciudad',
                'ciudad_agencia.prefijo AS agencia_ciudad_prefijo',
                'deptos_agencia.descripcion AS agencia_depto',
                'deptos_agencia.abreviatura AS agencia_depto_prefijo'
            )
            ->where([
                ['documento.deleted_at', null],
                ['documento.id', $id],
            ])
            ->first();
        $where = [['d.deleted_at', null], ['c.consolidado_id', $id]];
        if ($num_bolsa != 0) {
            $where[] = array('c.num_bolsa', $num_bolsa);
        }
        $detalle = $this->pdfLabelDetailConsolidado($where, $codigo);
        // return $detalle;
        $pdf = PDF::loadView('pdf.labelWGJyg', compact('documento', 'detalle', 'document', 'consolidado', 'dato_consolidado'))
            ->setPaper(array(25, -25, 260, 360), 'landscape');
        return $pdf->stream('labelsGroup.pdf');
    }

    public function buscarGuias($id, $num_guia, $num_bolsa, $pais_id, $range_value = false)
    {

        $detalle = DocumentoDetalle::join('documento as b', 'documento_detalle.documento_id', 'b.id')
            ->select('documento_detalle.id', 'documento_detalle.agrupado', 'documento_detalle.flag', 'documento_detalle.declarado2', 'b.consignee_id')
            ->where([
                ['documento_detalle.deleted_at', null],
            ])
            ->whereRaw('(documento_detalle.num_warehouse = "' . $num_guia . '" or documento_detalle.num_guia = "' . $num_guia . '")')
            ->first();
        if ($detalle) {
            // VERIFICAR SI EL NUMERO INGRESADO NO ESTE DENTRO DE UNA MINTIC
            if ($detalle->id == $detalle->agrupado and $detalle->flag == 0) {
                /* VERIFICAR QUE EL NUMERO INGRESADO NO ESTE EN OTRO CONSOLIDADO O YA ESTE INGRESADO */
                $cons_detail = DB::table('consolidado_detalle as a')
                    ->join('documento as b', 'a.consolidado_id', 'b.id')
                    ->select('a.consolidado_id', 'b.consecutivo')
                    ->where([['a.deleted_at', null], ['a.documento_detalle_id', $detalle->id]])
                    ->first();

                if (!$cons_detail) {
                    /* VERIFICAR SI LA GUIA O WAREHOUSE INGRESADO PERTENECE AL PAIS DEL CONSOLIDADO */
                    $cons = DB::table('consignee as a')
                        ->join('localizacion as b', 'a.localizacion_id', 'b.id')
                        ->join('deptos as c', 'b.deptos_id', 'c.id')
                        ->select(
                            'c.pais_id'
                        )
                        ->where([
                            ['a.id', $detalle->consignee_id],
                        ])
                        ->first();
                    if ($cons->pais_id == $pais_id) {
                        /* VALIDAR QUE EL DECLARADO NO ESTE EN CERO */
                        if ($detalle->declarado2 == 0) {
                            /* SI ESTA EN CERO SE ASIGNA UN VALOR ALEATORIO DEACUERDO AL RANGO DADO */
                            $range = explode(',', $range_value);
                            $r1 = rand($range[0], $range[1]) . '.' . rand($range[0], $range[1]);
                            $detalle->declarado2 = $r1;
                            $detalle->save();
                        }

                        /* INSERTAR EN TABLA CONSOLIDADO DETALLE */
                        $id_detail = DB::table('consolidado_detalle')->insertGetId(
                            [
                                'consolidado_id'       => $id,
                                'documento_detalle_id' => $detalle->id,
                                'agrupado'             => $detalle->id,
                                'num_bolsa'            => $num_bolsa,
                                'created_at'           => date('Y-m-d H:i:s'),
                            ]
                        );
                        /* ACTUALIZAR CAMPO consolidado EN DETALLE DOCUMENTO */
                        $datad              = DocumentoDetalle::findOrFail($detalle->id);
                        $datad->consolidado = 1;
                        $datad->save();
                        /* AGREGAR ESTATUS AL DETALLE */
                        DB::table('status_detalle')->insert([
                            [
                                'status_id'            => 5, // 5 ES CONSOLIDADO
                                'usuario_id'           => Auth::user()->id,
                                'documento_detalle_id' => $detalle->id,
                                'codigo'               => $datad->num_guia,
                                'fecha_status'         => date('Y-m-d H:i:s'),
                            ],
                        ]);
                        $answer = array(
                            "code" => 200,
                            "data" => $detalle,
                        );
                    } else {
                        $answer = array(
                            "code" => 600,
                            "data" => 'El país de destino de el documento ingresado no coincide con el país de este consolidado',
                        );
                    }
                } else {
                    $answer = array(
                        "code" => 600,
                        "data" => 'El número de Guía / WRH ingresado, ya se encuentra registrado en el consolidado # ' . $cons_detail->consecutivo,
                    );
                }
            } else {
                $det = DB::table('documento_detalle as a')
                    ->select('a.mintic')
                    ->where([['a.id', $detalle->agrupado]])
                    ->first();
                $answer = array(
                    "code" => 600,
                    "data" => 'El documento ingresado esta dentro de la ' . $det->mintic
                );
            }
        } else {
            $answer = array(
                "code" => 600,
                "data" => 'No existen registros con el número de Guía/WRH ingresado.',
            );
        }
        return $answer;
    }

    public function getAllConsolidadoDetalle($id, $num_bolsa = null)
    {
        // OBTENEMOS LA CONFIGURACION DEL CONSOLIDADO
        $config = $this->getConfig('consolidado');
        if ($config and $config->value != '') {
            $config = json_decode($config->value);
        } else {
            //VALOR POR DEFECTO
            $config = json_decode('{"peso_max": 110, "declarado_max": 2000}');
        }

        $where = [['a.deleted_at', null], ['a.consolidado_id', $id], ['a.flag', 0]];
        if ($num_bolsa != null) {
            $where[] = ['a.num_bolsa', $num_bolsa];
        }

        $label_1 = '</label><a style="float:right;cursor:pointer;color:red" title="Quitar" data-toggle="tooltip" onclick="removerGuiaAgrupada(';
        $label_2 = ')"><i class="fa fa-times" style="font-size: 15px;"></i></a>';

        $detalle = DB::table('consolidado_detalle AS a')
            ->join('documento AS b', 'a.consolidado_id', 'b.id')
            ->join('documento_detalle AS c', 'a.documento_detalle_id', 'c.id')
            ->leftJoin('shipper as d', 'c.shipper_id', 'd.id')
            ->leftJoin('consignee as e', 'c.consignee_id', 'e.id')
            ->leftJoin('posicion_arancelaria as f', 'c.arancel_id2', 'f.id')
            ->leftJoin(DB::raw('(SELECT
                                    z.agrupado,
                                    SUM(x.peso) AS peso,
                                    SUM(x.peso2) AS peso2,
                                    GROUP_CONCAT(

                                        IF (
                                            z.flag = 1,
                                            CONCAT(

                                                    CONCAT(
                                                        "<label>- ",
                                                        x.num_warehouse,
                                                        " (",
                                                        x.peso2,
                                                        " lbs) ",
                                                        " ($ ",
                                                        x.declarado2,
                                                        ".00) </label>",
                                                        \'' . $label_1 . '\',
                                                        z.id,
                                                        \'@\',
                                                        x.id,
                                                        \'' . $label_2 . '\'
                                                    )
                                            ),
                                            NULL
                                        )
                                    ) AS guias_agrupadas
                                FROM
                                    consolidado_detalle AS z
                                INNER JOIN documento_detalle AS x ON z.documento_detalle_id = x.id
                                WHERE
                                    z.deleted_at IS NULL
                                AND x.deleted_at IS NULL
                                GROUP BY
                                    z.agrupado
                            ) AS g'), 'a.agrupado', 'g.agrupado')
            ->leftJoin(DB::raw('(SELECT
                b.consignee_id,
                Sum(b.declarado2) AS declarado,
                Sum(b.peso2) AS peso,
                (CASE
                    WHEN (Sum(b.peso2) >= ' . $config->peso_max . '  or Sum(b.peso2) <= 0) THEN 1 ELSE 0
                END) AS flag_peso,
                (CASE
                    WHEN (Sum(b.declarado2) >= ' . $config->declarado_max . '  or Sum(b.declarado2) <= 0) THEN 2 ELSE 0
                END) AS flag_declarado
                FROM
                consolidado_detalle AS a
                INNER JOIN documento_detalle AS b ON a.documento_detalle_id = b.id
                WHERE
                a.consolidado_id = ' . $id . ' AND
                b.deleted_at IS NULL
                GROUP BY
                b.consignee_id
                HAVING
                peso <= 0 OR peso >= ' . $config->peso_max . ' OR
                declarado <= 0 OR declarado >= ' . $config->declarado_max . ') AS h'), 'c.consignee_id', 'h.consignee_id')
            ->select(
                'a.id',
                'a.consolidado_id',
                'c.documento_id',
                'a.documento_detalle_id',
                'b.estado_id',
                'b.agencia_id',
                'a.num_bolsa',
                'c.num_warehouse',
                'c.num_guia',
                // 'a.shipper AS shipper_json',
                // 'a.consignee AS consignee_json',
                'd.id as shipper_id',
                'd.nombre_full as shipper',
                'd.contactos_json as shipper_contactos',
                'c.consignee_id as consignee_id',
                'e.nombre_full as consignee',
                'e.contactos_json as consignee_contactos',
                'c.contenido2',
                'c.piezas',
                'f.id AS pa_id',
                'f.pa',
                'c.declarado2',
                DB::raw('ROUND(g.peso2,1) as peso2'),
                DB::raw('ROUND(g.peso,1) as peso'),
                'g.guias_agrupadas',
                'c.liquidado',
                DB::raw('(SELECT Count(z.id) FROM consolidado_detalle AS z WHERE z.agrupado = a.documento_detalle_id AND z.deleted_at IS NULL AND z.flag = 1) AS agrupadas'),
                DB::raw('(SELECT
              			ROUND(Sum(b.peso2) * 0.453592) AS peso_total
              		FROM
              			consolidado_detalle AS z
              		INNER JOIN documento_detalle AS b ON z.documento_detalle_id = b.id
              		WHERE
              			z.deleted_at IS NULL
              		AND b.deleted_at IS NULL
              		AND b.consignee_id = e.id
                  AND z.consolidado_id = a.consolidado_id
              	) AS peso_total'),
                DB::raw('(SELECT
              			Sum(b.declarado2) AS declarado_total
              		FROM
              			consolidado_detalle AS z
              		INNER JOIN documento_detalle AS b ON z.documento_detalle_id = b.id
              		WHERE
              			z.deleted_at IS NULL
              		AND b.deleted_at IS NULL
              		AND b.consignee_id = e.id
                  AND z.consolidado_id = a.consolidado_id
              	) AS declarado_total'),
                'h.flag_peso',
                'h.flag_declarado',
                DB::raw('(SELECT
                    sum(z.total_puntos)
                    FROM
                    pivot_puntos_detalle AS z
                    WHERE
                    z.documento_detalle_id = a.documento_detalle_id AND
                    z.deleted_at IS NULL) AS total_puntos'),
                DB::raw('(SELECT
              			cc.nombre_full AS consignee
              		FROM
              			consignee as cc
              		WHERE cc.id = a.consignee
                  ) AS consignee_json'),
                DB::raw('(SELECT
              			ss.nombre_full AS shipper
              		FROM
              			shipper as ss
              		WHERE ss.id = a.shipper
              	) AS shipper_json')
            )
            ->where($where)
            ->get();
        return \DataTables::of($detalle)->make(true);
    }

    public function updateDetailConsolidado(Request $request)
    {
        try {
            // $data = DocumentoDetalle::findOrFail($request->rowData['documento_detalle_id']);
            $data = DocumentoDetalle::findOrFail($request->pk);

            if (isset($request->rowData['option']) and $request->rowData['option'] == 'shipper') {
                $data->shipper_id = $request->rowData['id'];
            } else {
                if (isset($request->rowData['option']) and $request->rowData['option'] == 'consignee') {
                    $data->consignee_id = $request->rowData['id'];
                } else {
                    if (isset($request->value) and $request->name === 'peso2') {
                        $data->peso2 = $request->value;
                    }
                    if (isset($request->value) and $request->name === 'contenido2') {
                        $data->contenido2 = $request->value;
                    }
                    if (isset($request->value) and $request->name === 'declarado2') {
                        $data->declarado2 = $request->value;
                    }
                }
            }

            if ($data->save()) {
                $this->AddToLog('Consolidado detalle editado (' . $data->id . ')');
                $answer = array(
                    "datos"  => $data,
                    "code"   => 200,
                    "status" => 200,
                );
            } else {
                $answer = array(
                    "error"  => 'Error al intentar Eliminar el registro.',
                    "code"   => 600,
                    "status" => 500,
                );
            }
            return $answer;
        } catch (\Exception $e) {
            $error = '';
            if (isset($e->errorInfo) and $e->errorInfo) {
                foreach ($e->errorInfo as $key => $value) {
                    $error .= $key . ' - ' . $value . ' <br> ';
                }
            } else {
                $error = $e;
            }
            $answer = array(
                "error"  => $error,
                "code"   => 600,
                "status" => 500,
            );
            return $answer;
        }
    }

    public function getAllGuiasDisponibles($id, $pais_id = null, $transporte_id = null)
    {
        $filter = [
            ['a.deleted_at', null],
            ['b.deleted_at', null],
            ['a.consolidado', 0],
            ['a.flag', 0],
            ['f.tipo_embarque_id', $transporte_id],
            ['e.pais_id', $pais_id],
        ];
        // if(!Auth::user()->isRole('admin')){
        // $filter[] = ['b.agencia_id', Auth::user()->agencia_id];
        // }
        if (env('APP_CLIENT') == 'jyg') {
            $filter[] = ['a.liquidado', 1];
        }

        $detalle = DB::table('documento_detalle AS a')
            ->join('documento as b', 'a.documento_id', 'b.id')
            ->join('agencia', 'b.agencia_id', 'agencia.id')
            ->join('consignee as c', 'b.consignee_id', 'c.id')
            ->join('localizacion as d', 'c.localizacion_id', 'd.id')
            ->join('deptos as e', 'd.deptos_id', 'e.id')
            ->leftJoin('guia_wrh_pivot AS f', 'b.id', '=', 'f.documento_id')
            ->select(
                'a.id',
                'a.created_at',
                'a.num_guia',
                'a.num_warehouse',
                'a.liquidado',
                'a.peso2',
                'e.pais_id',
                'c.nombre_full AS consignee',
                'agencia.descripcion AS agencia',
                DB::raw('IFNULL(ROUND(a.declarado2, 2),0) as declarado2')
            )
            ->groupBy(
                'a.id',
                'a.created_at',
                'a.num_guia',
                'a.num_warehouse',
                'a.liquidado',
                'a.peso2',
                'e.pais_id',
                'a.declarado2',
                'consignee',
                'agencia'
            )
            ->where($filter)
            ->get();
        return \DataTables::of($detalle)->make(true);
    }

    public function getDataSelectWarehousesModalTagGuia($id)
    {
        // if(env('APP_CLIENT') == 'worldcargo'){
        $codigo = 'documento_detalle.num_warehouse AS name';
        // }else{
        //     $codigo = DB::raw('(CASE WHEN b.liquidado = 1 THEN documento_detalle.num_guia ELSE documento_detalle.num_warehouse END) AS name');
        // }
        $data = DocumentoDetalle::join('documento as b', 'documento_detalle.documento_id', 'b.id')
            ->select('documento_detalle.id', $codigo)
            ->where([
                ['documento_detalle.deleted_at', null],
                ['documento_detalle.documento_id', $id],
            ])->get();
        return \DataTables::of($data)->make(true);
    }

    public function ajaxCreateNota(Request $request, $id)
    {
        try {
            $idInsert = DB::table('documento_notas')->insertGetId(
                [
                    'documento_id' => $id,
                    'user_id'      => Auth::user()->id,
                    'nota'         => $request->nota,
                    'created_at'   => date('Y-m-d H:i:s'),
                ]
            );
            $this->AddToLog('Nota creada (' . $idInsert . ')');
            $answer = array(
                "data"   => $idInsert,
                "code"   => 200,
                "status" => 200,
            );
            return $answer;
        } catch (\Exception $e) {
            $error = $e;
            // foreach ($e->errorInfo as $key => $value) {
            //     $error .= $key . ' - ' . $value . ' <br> ';
            // }
            $answer = array(
                "error"  => $error,
                "code"   => 600,
                "status" => 500,
            );
            return $answer;
        }
    }

    public function getAllGridNotas($id_documento)
    {
        $sql = DB::table('documento_notas AS a')
            ->join('users AS b', 'b.id', 'a.user_id')
            ->select(
                'a.*',
                'b.id as usuario_id',
                'b.name'
            )
            ->where([
                ['a.deleted_at', null],
                ['a.documento_id', $id_documento],
            ])
            ->orderBy('a.created_at', 'DESC');
        return \DataTables::of($sql)->make(true);
    }

    public function deleteNota($id, $logical)
    {
        DB::table('documento_notas')->where('id', $id)->delete();
        $this->AddToLog('Nota Eliminada (' . $id . ')');
    }

    public function sendEmailDocument($id_documet)
    {
        $objDocumento = DB::table('documento')
            ->leftJoin('shipper', 'documento.shipper_id', 'shipper.id')
            ->leftJoin('consignee', 'documento.consignee_id', 'consignee.id')
            ->leftJoin('localizacion as ciudad_consignee', 'consignee.localizacion_id', 'ciudad_consignee.id')
            ->leftJoin('localizacion as ciudad_shipper', 'shipper.localizacion_id', 'ciudad_shipper.id')
            ->leftJoin('deptos as deptos_consignee', 'ciudad_consignee.deptos_id', 'deptos_consignee.id')
            ->leftJoin('deptos as deptos_shipper', 'ciudad_shipper.deptos_id', 'deptos_shipper.id')
            ->leftJoin('guia_wrh_pivot', 'guia_wrh_pivot.documento_id', 'documento.id')
            ->join('agencia', 'documento.agencia_id', 'agencia.id')
            ->select(
                'documento.*',
                'shipper.nombre_full as ship_nomfull',
                'shipper.direccion as ship_dir',
                'shipper.telefono as ship_tel',
                'shipper.correo as ship_email',
                'shipper.zip as ship_zip',
                'ciudad_shipper.nombre as ship_ciudad',
                'deptos_shipper.descripcion as ship_depto',
                'consignee.nombre_full as cons_nomfull',
                'consignee.direccion as cons_dir',
                'consignee.telefono as cons_tel',
                'consignee.documento as cons_documento',
                'consignee.correo as cons_email',
                'consignee.zip as cons_zip',
                'consignee.po_box as cons_pobox',
                'ciudad_consignee.nombre as cons_ciudad',
                'deptos_consignee.descripcion as cons_depto',
                'agencia.descripcion as agencia',
                'agencia.telefono as agencia_tel',
                'agencia.direccion as agencia_dir'
            )
            ->where([
                ['documento.deleted_at', null],
                ['documento.id', $id_documet],
            ])
            ->first();

        $detalle = DocumentoDetalle::join('documento', 'documento_detalle.documento_id', '=', 'documento.id')
            ->leftJoin('posicion_arancelaria', 'documento_detalle.posicion_arancelaria_id', '=', 'posicion_arancelaria.id')
            ->join('maestra_multiple', 'documento_detalle.tipo_empaque_id', '=', 'maestra_multiple.id')
            ->select('documento_detalle.*', 'posicion_arancelaria.pa AS nom_pa', 'maestra_multiple.nombre AS empaque')
            ->where([['documento_detalle.deleted_at', null], ['documento_detalle.documento_id', $id_documet]])
            ->get();

        $cont       = 0;
        $datosEnvio = '';
        $tracks = '<strong style="font-size:15px;">Trackings:</strong><br>';
        $trackings  = array();
        foreach ($detalle as $objD) {
            $trackings[] = DB::table('tracking as a')
                ->select('a.codigo', 'a.contenido')
                ->where([['a.deleted_at', null], ['a.documento_detalle_id', $objD->id]])
                ->get();

            $datosEnvio .= $cont + 1 . '. <strong>Peso:</strong>' . $objD->peso . " Lbs | <strong>Contenido:</strong> " . $objD->contenido . "<br>";
            $datosEnvio .= '<strong>Trackings:</strong><br>';
            foreach ($trackings[$cont] as $t) {
                $tracks .= '🚚  <span style="font-size:15px;">- ' . $t->codigo . '<span><br>';
                $datosEnvio .= '🚚 ' . $t->codigo . ' / ' . $t->contenido . '<br>';
            }
            $datosEnvio .= '<br><br>';
            $cont++;
        }

        $id_ship      = $objDocumento->shipper_id;
        $id_cons      = $objDocumento->consignee_id;
        $id_agencia   = $objDocumento->agencia_id;
        $id_plantilla = 2;

        $objShipper   = $this->getDataConsigneeOrShipperById($id_ship, 'shipper');
        $objConsignee = $this->getDataConsigneeOrShipperById($id_cons, 'consignee');

        /* DATOS DE LA AGENCIA */
        $objAgencia = $this->getDataAgenciaById($id_agencia);
        /* DATOS DE LA PLANTILLA */
        $plantilla = $this->getDataEmailPlantillaById($id_plantilla);

        if (isset($objConsignee->correo) and $objConsignee->correo != '') {
            if (filter_var(trim($objConsignee->correo), FILTER_VALIDATE_EMAIL)) {
                /* ENVIO DE EMAIL REPLACEMENT($id_documento, $objAgencia, $objDocumento, $objShipper, $objConsignee, $datosEnvio)*/
                $replacements = $this->replacements($id_documet, $objAgencia, $objDocumento, $objShipper, $objConsignee, $datosEnvio, $tracks);

                $cuerpo_correo = preg_replace(array_keys($replacements), array_values($replacements), $plantilla->mensaje);
                $asunto_correo = preg_replace(array_keys($replacements), array_values($replacements), $plantilla->subject);

                $from_self = array(
                    'address' => $objAgencia->email,
                    'name'    => $objAgencia->descripcion,
                );

                // $moreUsers     = $objConsignee->correo;
                // $evenMoreUsers = $objConsignee->correo;

                $this->AddToLog('Email enviado (' . $id_documet . ')');
                $pdf = false;
                if ($plantilla->enviar_archivo != 0) {
                    $pdf = array(
                        'pdf' => $this->pdf($id_documet, 'warehouse'), 'pdf_name' => $objDocumento->num_warehouse
                    );
                }

                return Mail::to(trim($objConsignee->correo))
                    // ->cc($moreUsers)
                    // ->bcc($evenMoreUsers)
                    ->send(new \App\Mail\WarehouseEmail($cuerpo_correo, $from_self, $asunto_correo, $pdf));
            } else {
                return 'No es una direccion de email valida';
            }
        } else {
            return 'No tiene direccion de email';
        }
    }

    public function searchDataByNavbar($data, $element)
    {
        $term = $data;

        $tags = DB::table('documento_detalle as a')
            ->leftJoin('documento', 'a.documento_id', '=', 'documento.id')
            ->leftJoin('shipper as ship1', 'documento.shipper_id', '=', 'ship1.id')
            ->leftJoin('consignee as cons1', 'documento.consignee_id', '=', 'cons1.id')
            ->leftJoin('shipper as ship2', 'documento.shipper_id', '=', 'ship2.id')
            ->leftJoin('consignee as cons2', 'documento.consignee_id', '=', 'cons2.id')
            ->leftJoin('tracking', 'tracking.documento_detalle_id', '=', 'a.id')
            ->select(
                'a.id',
                'a.contenido',
                'a.contenido2',
                'a.volumen',
                'a.valor',
                'a.declarado2',
                'a.peso',
                'a.peso2',
                'a.num_warehouse',
                'a.num_guia',
                'a.created_at',
                'ship1.id as shipper_id',
                'cons1.id as consignee_id',
                'ship1.nombre_full as ship_nomfull',
                'cons1.nombre_full as cons_nomfull',
                'ship2.nombre_full as ship_nomfull2',
                'cons2.nombre_full as cons_nomfull2',
                DB::raw("IFNULL(tracking.codigo, a.num_warehouse) as name")
            )
            ->where([
                ['a.deleted_at', null],
            ])
            ->whereRaw(
                "(tracking.codigo LIKE '%" . $data . "%' OR
                a.contenido LIKE '%" . $data . "%' OR
                a.num_guia LIKE '%" . $data . "%' OR
                a.num_warehouse LIKE '%" . $data . "%'
                OR ship1.nombre_full LIKE '%" . $data . "%'
                OR cons2.nombre_full LIKE '%" . $data . "%')"
            )
            ->get();
        $answer = array(
            'code'  => 200,
            'items' => $tags,
        );
        return $answer;
    }

    public function getHistoryConsignee($id)
    {
        $data = DB::table('documento_detalle as a')
            ->leftJoin('documento', 'a.documento_id', '=', 'documento.id')
            ->leftJoin('shipper as ship1', 'documento.shipper_id', '=', 'ship1.id')
            ->leftJoin('consignee as cons1', 'documento.consignee_id', '=', 'cons1.id')
            ->leftJoin('shipper as ship2', 'documento.shipper_id', '=', 'ship2.id')
            ->leftJoin('consignee as cons2', 'documento.consignee_id', '=', 'cons2.id')
            ->select(
                'a.id',
                'a.contenido',
                'a.contenido2',
                'a.volumen',
                'a.valor',
                'a.declarado2',
                'a.peso',
                'a.peso2',
                'a.num_warehouse',
                'a.num_guia',
                'a.created_at',
                'ship1.id as shipper_id',
                'cons1.id as consignee_id',
                'ship1.nombre_full as ship_nomfull',
                'cons1.nombre_full as cons_nomfull',
                'ship2.nombre_full as ship_nomfull2',
                'cons2.nombre_full as cons_nomfull2',
                DB::raw("(SELECT GROUP_CONCAT(tracking.codigo) FROM tracking WHERE tracking.documento_detalle_id = a.id) as tracking")
            )
            ->where([
                ['a.deleted_at', null],
                ['documento.consignee_id', $id],
            ])
            ->get();
        $answer = array(
            'code' => 200,
            'data' => $data,
        );
        return $answer;
    }

    public function addStatusToGuias(Request $request, $id)
    {
        $detalle = DB::table('consolidado_detalle AS a')
            ->join('documento_detalle AS b', 'a.documento_detalle_id', '=', 'b.id')
            ->select(
                'a.documento_detalle_id',
                'b.num_warehouse',
                'b.num_guia'
            )
            ->where([
                ['a.deleted_at', null],
                ['a.consolidado_id', $id],
            ])
            ->get();
        /* INSERCION DE ESTATUS PARA LAS GUIAS DEL CONSOLIDADO */
        foreach ($detalle as $key) {
            DB::table('status_detalle')->insert([
                [
                    'status_id'            => $request->status_id,
                    'usuario_id'           => Auth::user()->id,
                    'documento_detalle_id' => $key->documento_detalle_id,
                    'codigo'               => $key->num_guia,
                    'fecha_status'         => date('Y-m-d H:i:s'),
                    'observacion'          => 'Se agrego desde el consolidado',
                ],
            ]);
            DocumentoDetalle::where('id', $key->documento_detalle_id)->update(['status_id' => $request->status_id]);
        }
        Documento::where('id', $id)->update(['estado_id' => $request->status_id]);
        $this->AddToLog('Estatus agregado a guias. Conolidado id (' . $id . ')');

        $answer = array(
            'code' => 200,
        );
        return $answer;
    }

    public function createContactsConsolidadoDetalle(Request $request, $id)
    {
        $id_change = $request->all()['id_change'];
        $data   = $request->all()['data'];
        $this->updateIdConsigneeContactConsolidate(false, $data['option'], $data['id'], $id_change);
        DB::table('consolidado_detalle')
            ->where('id', $data['id'])
            ->update([$data['option'] => $id_change]);
        $answer = array(
            'code' => 200,
        );
        return $answer;
    }

    public function restoreShipperConsignee($id, $idD, $table)
    {
        $this->updateIdConsigneeContactConsolidate(true, $table, $idD);
        DB::table('consolidado_detalle')
            ->where('id', $idD)
            ->update([$table => null]);
        $answer = array(
            'code' => 200,
        );
        return $answer;
    }

    public function updateIdConsigneeContactConsolidate($restore, $table, $id_consol_detail, $id_change = null)
    {
        $id_detail_document = DB::table('consolidado_detalle AS a')
            ->select('a.documento_detalle_id')
            ->where('a.id', $id_consol_detail)
            ->first();
        $data = DB::table('documento_detalle AS a')
            ->select('a.' . $table . '_id')
            ->where('a.id', $id_detail_document->documento_detalle_id)
            ->first();
        if ($restore) {
            $dat = explode("-", (($table === 'consignee') ? $data->consignee_id : $data->shipper_id));
            $id_dat = $dat[0];
        } else {
            $id_dat = (($table === 'consignee') ? $data->consignee_id : $data->shipper_id) . '-' . $id_change;
        }

        DB::table('documento_detalle AS a')
            ->where('a.id', $id_detail_document->documento_detalle_id)
            ->update(['a.' . $table . '_id' => $id_dat]);
        return true;
    }

    public function getGuiasAgrupar($id, $id_detalle, $document = false)
    {
        if ($document) {
            $detalle = DB::table('documento_detalle AS a')
                ->join('documento AS b', 'a.documento_id', 'b.id')
                ->select(
                    'a.id',
                    'a.num_warehouse AS codigo',
                    'a.liquidado',
                    'a.piezas',
                    'a.valor AS declarado',
                    'a.peso'
                )
                ->where([
                    ['a.deleted_at', null],
                    ['a.id', '<>', $id_detalle],
                    ['a.flag', 0],
                    ['a.consolidado', 0],
                    ['b.carga_courier', 1]
                ])
                ->orderBy('codigo', 'DESC')
                ->get();
        } else {
            $detalle = DB::table('consolidado_detalle AS a')
                ->join('documento_detalle AS b', 'a.documento_detalle_id', 'b.id')
                ->leftJoin(DB::raw("(SELECT
                                    z.agrupado,
                                    SUM(x.peso) AS peso,
                                    SUM(x.peso2) AS peso2,
                                    GROUP_CONCAT(

                                        IF (
                                            z.flag = 1,
                                            CONCAT(

                                                IF (
                                                    x.liquidado = 1,
                                                    CONCAT('- ', x.num_guia),
                                                    CONCAT('- ', x.num_warehouse)
                                                )
                                            ),
                                            NULL
                                        )
                                    ) AS guias_agrupadas
                                FROM
                                    consolidado_detalle AS z
                                INNER JOIN documento_detalle AS x ON z.documento_detalle_id = x.id
                                WHERE
                                    z.deleted_at IS NULL
                                AND x.deleted_at IS NULL
                                GROUP BY
                                    z.agrupado
                            ) AS g"), 'a.agrupado', 'g.agrupado')
                ->select(
                    'a.id',
                    'a.documento_detalle_id',
                    DB::raw('IF(b.liquidado = 1,b.num_guia,b.num_warehouse) as codigo'),
                    'b.liquidado',
                    'g.peso2'
                )
                ->where([
                    ['a.deleted_at', null],
                    ['a.consolidado_id', $id],
                    ['a.id', '<>', $id_detalle],
                    ['a.flag', 0],
                    ['g.guias_agrupadas', null],
                ])
                ->get();
        }
        return \DataTables::of($detalle)->make(true);
    }

    public function agruparGuiasConsolidadoCreate(Request $request)
    {
        if (isset($request->all()['document']) and $request->all()['id_detalle']) {
            $this->mintic($request->all()['mintic'], $request->all()['id_detalle'], $request->all()['ids_guias']);
        } else {
            $deta_guia = DB::table('consolidado_detalle')->select('documento_detalle_id')->where('id', $request->all()['id_detalle'])->first();

            for ($i = 1; $i <= count($request->all()['ids_guias']); $i++) {
                DB::table('consolidado_detalle')
                    ->where('documento_detalle_id', $request->all()['ids_guias'][$i])
                    ->update(['agrupado' => $deta_guia->documento_detalle_id, 'flag' => 1]);
            }
        }
        $answer = array(
            'code' => 200,
        );
        return $answer;
    }

    public function mintic($mintic, $id, $ids)
    {
        $peso = 0;
        $declarado = 0;
        $piezas = 0;
        foreach ($ids as $key => $value) {
            DocumentoDetalle::where('id', $value)->update([
                'agrupado' => $id,
                'flag' => 1
            ]);
            if (!$mintic) {
                $data = DocumentoDetalle::select('id', 'piezas', 'valor', 'declarado2', 'peso', 'peso2', 'num_warehouse')
                    ->where('id', $value)->first();
                $peso += $data->peso;
                $declarado += $data->valor;
                $piezas += $data->piezas;
            }
        }
        if ($mintic) {
            DocumentoDetalle::where('id', $id)->update([
                'mintic' => 'Mintic'
            ]);
        } else {
            $data = DocumentoDetalle::select('id', 'piezas', 'valor', 'declarado2', 'peso', 'peso2', 'num_warehouse')
                ->where('id', $id)->first();
            $this->AddToLog('Documento agrupado, valores anteriores del documento (' . $data->id . ')' .
                ' WRH (' . $data->num_warehouse . ') Peso: ' . $data->peso . ' Piezas: ' . $data->piezas . ' Declarado:' . $data->valor);
            DocumentoDetalle::where('id', $id)->update([
                'mintic' => null,
                'piezas' => $piezas,
                'valor' => $declarado,
                'declarado2' => $declarado,
                'peso' => $peso,
                'peso2' => $peso
            ]);
        }
    }

    public function removerGuiaAgrupada($id, $id_detalle, $id_guia_detalle, $document = false)
    {
        if ($document) {
            DB::table('documento_detalle')
                ->where('id', $id_detalle)
                ->update(['agrupado' => $id_detalle, 'flag' => 0]);
        } else {
            DB::table('consolidado_detalle')
                ->where('id', $id_detalle)
                ->update(['agrupado' => $id_guia_detalle, 'flag' => 0]);
        }
        $answer = array(
            'code' => 200,
        );
        return $answer;
    }

    public function updatePositionArancel(Request $request)
    {
        $tabla = 'documento_detalle';
        $update = ['arancel_id2' => $request->rowData['id_pa']];
        if (isset($request->rowData['tabla']) and $request->rowData['tabla'] == 'whgTable') {
            $tabla = 'documento_detalle';
            $update = ['arancel_id2' => $request->rowData['id_pa'], 'posicion_arancelaria_id' => $request->rowData['id_pa']];
        }
        DB::table($tabla)
            ->where('id', $request->rowData['id_detalle'])
            ->update($update);
        $answer = array(
            'code' => 200,
        );
        return $answer;
    }

    public function getDataDetailDocument($id)
    {
        $detalle = DocumentoDetalle::join('documento', 'documento_detalle.documento_id', '=', 'documento.id')
            ->leftJoin('shipper', 'documento_detalle.shipper_id', '=', 'shipper.id')
            ->leftJoin('consignee', 'documento_detalle.consignee_id', '=', 'consignee.id')
            ->join('agencia', 'documento.agencia_id', '=', 'agencia.id')
            ->leftJoin('posicion_arancelaria', 'documento_detalle.posicion_arancelaria_id', '=', 'posicion_arancelaria.id')
            ->join('maestra_multiple', 'documento_detalle.tipo_empaque_id', '=', 'maestra_multiple.id')
            ->select(
                'documento_detalle.*',
                'agencia.descripcion AS nom_agencia',
                'posicion_arancelaria.pa AS nom_pa',
                'posicion_arancelaria.id AS id_pa',
                'shipper.nombre_full AS ship_nomfull',
                'consignee.nombre_full AS cons_nomfull',
                'maestra_multiple.nombre AS empaque',
                DB::raw("(SELECT Count(a.id) FROM tracking AS a WHERE a.documento_detalle_id = documento_detalle.id AND a.deleted_at IS NULL) as cantidad"),
                DB::raw("(SELECT
                IFNULL(SUM(a.total_puntos),0)
                FROM
                pivot_puntos_detalle AS a
                WHERE
                a.documento_detalle_id = documento_detalle.id) AS puntos")
            )
            ->where([['documento_detalle.deleted_at', null], ['documento_detalle.documento_id', $id]])
            ->get();

        return \DataTables::of($detalle)->make(true);
    }

    public function updateDetailDocument(Request $request)
    {
        try {
            $data = DocumentoDetalle::findOrFail($request->pk);

            if (isset($request->value) and $request->name === 'peso') {
                $data->peso = $request->value;
                $data->peso2 = $request->value;
            }
            if (isset($request->value) and $request->name === 'contenido') {
                $data->contenido = $request->value;
                $data->contenido2 = $request->value;
            }
            if (isset($request->value) and $request->name === 'declarado') {
                $data->valor = $request->value;
                $data->declarado2 = $request->value;
            }
            if (isset($request->value) and $request->name === 'piezas') {
                $data->piezas = $request->value;
            }
            if (isset($request->value) and $request->name === 'dimensiones') {
                $data->largo = $request->value['largo'];
                $data->ancho = $request->value['ancho'];
                $data->alto = $request->value['alto'];
                $data->dimensiones = $data->peso . ' Vol=' . $request->value['largo'] . 'x' . $request->value['ancho'] . 'x' . $request->value['alto'];
                $data->volumen = ($request->value['largo'] * $request->value['ancho'] * $request->value['alto'] / 166);
            }

            if ($data->save()) {
                $this->AddToLog('Documento detalle editado (' . $data->id . ')');
                $answer = array(
                    "datos"  => $data,
                    "code"   => 200,
                    "status" => 200,
                );
            } else {
                $answer = array(
                    "error"  => 'Error al intentar Eliminar el registro.',
                    "code"   => 600,
                    "status" => 500,
                );
            }
            return $answer;
        } catch (Exception $e) {
            $error = '';
            if (isset($e->errorInfo) and $e->errorInfo) {
                foreach ($e->errorInfo as $key => $value) {
                    $error .= $key . ' - ' . $value . ' <br> ';
                }
            } else {
                $error = $e;
            }
            $answer = array(
                "error"  => $error,
                "code"   => 600,
                "status" => 500,
            );
            return $answer;
        }
    }

    public function getBoxesConsolidado($id)
    {
        $where = [['a.consolidado_id', $id], ['a.deleted_at', null]];
        // if(!Auth::user()->isRole('admin')){
        $where[] = ['c.agencia_id', Auth::user()->agencia_id];
        // }

        $data = DB::table('consolidado_detalle as a')
            ->leftJoin('documento_detalle as b', 'a.documento_detalle_id', 'b.id')
            ->leftJoin('documento as c', 'b.documento_id', 'c.id')
            ->select(
                DB::raw("a.num_bolsa AS num_bolsa"),
                DB::raw("FORMAT(Sum(b.peso2),0) AS peso"),
                DB::raw("FORMAT(ROUND((Sum(b.peso2) * 0.453592)),0) AS peso_kl"),
                DB::raw("FORMAT(Count(a.id),0) AS cantidad"),
                DB::raw("FORMAT(ROUND(Sum(b.volumen)),0) AS volumen")
            )
            ->where($where)
            ->groupBy("a.num_bolsa")
            ->get();
        $answer = array(
            'code' => 200,
            'data' => $data,
        );
        return $answer;
    }

    public function removeBoxConsolidado($id, $num_bolsa)
    {
        try {
            $data = Documento::findOrFail($id);
            $detail = DB::table('consolidado_detalle')->where([['consolidado_id', $id], ['num_bolsa', $num_bolsa]])->get();

            if (count($detail) > 0) {
                foreach ($detail as $key) {
                    $this->deleteDetailConsolidado($id, $key->id, false);
                }
            }

            $this->AddToLog('Bolsa eliminada de consolidado (' . $data->consecutivo . ') N° bolsa(' . $num_bolsa . ')');
            $answer = array(
                "datos"  => $id,
                "code"   => 200,
                "status" => 200,
            );
            return $answer;
        } catch (Exception $e) {
            $error = '';
            if (isset($e->errorInfo) and $e->errorInfo) {
                foreach ($e->errorInfo as $key => $value) {
                    $error .= $key . ' - ' . $value . ' <br> ';
                }
            } else {
                $error = $e;
            }
            $answer = array(
                "error"  => $error,
                "code"   => 600,
                "status" => 500,
            );
            return $answer;
        }
    }

    public function changeBoxConsolidado($id, $num_bolsa, $consol_id)
    {
        try {
            $data = Documento::findOrFail($id);
            $data2 = Documento::findOrFail($consol_id);
            DB::table('consolidado_detalle')->where([['consolidado_id', $id], ['num_bolsa', $num_bolsa]])->update(['consolidado_id' => $consol_id]);

            $this->AddToLog('Bolsa trasladada del consolidado (' . $data->consecutivo . ') al consolidado (' . $data2->consecutivo . ')');
            $answer = array(
                "datos"  => $id,
                "code"   => 200,
                "status" => 200,
            );
            return $answer;
        } catch (Exception $e) {
            $error = '';
            if (isset($e->errorInfo) and $e->errorInfo) {
                foreach ($e->errorInfo as $key => $value) {
                    $error .= $key . ' - ' . $value . ' <br> ';
                }
            } else {
                $error = $e;
            }
            $answer = array(
                "error"  => $error,
                "code"   => 600,
                "status" => 500,
            );
            return $answer;
        }
    }

    public function closeDocument($id)
    {
        Documento::where('id', $id)->update(['close_document' => 1]);
        return $data = array('code' => 200,);
    }

    public function getDataByDocument($id)
    {
        $trackings = DB::table('documento_detalle as a')
            ->leftJoin('tracking as b', 'a.id', 'b.documento_detalle_id')
            ->select('b.codigo', 'b.contenido', 'a.contenido AS contenido_detalle', 'a.peso')
            ->where([['a.deleted_at', null], ['b.deleted_at', null], ['a.documento_id', $id]])
            ->get();
        $answer = array(
            'code' => 200,
            'trackings' => $trackings,
        );
        return $answer;
    }

    public function getDataPrintBagsConsolidate($id, $type = false)
    {
        if ($type) {
            $data = DB::table('master AS a')
                ->join('master_detalle AS b', 'b.master_id', 'a.id')
                ->join('transportador', 'a.consignee_id', 'transportador.id')
                ->join('agencia', 'agencia.id', 'a.agencia_id')
                ->select(
                    'b.piezas AS num_bolsa',
                    'a.num_master',
                    'transportador.nombre',
                    'transportador.ciudad',
                    'transportador.estado',
                    'transportador.pais',
                    'agencia.descripcion AS agencia',
                    'agencia.logo',
                    DB::raw("'master' AS master")
                )
                ->where([
                    ['a.deleted_at', NULL],
                    ['b.deleted_at', NULL],
                    ['a.id', $id]
                ])
                ->get();
            if (count($data) > 0) {
                foreach ($data as $value) {
                    $value->tipo_consolidado = $type;
                }
            }
        } else {
            $data = DB::table('consolidado_detalle AS a')
                ->join('documento AS b', 'a.consolidado_id', 'b.id')
                ->leftJoin('master AS c', 'c.id', 'b.master_id')
                ->join('localizacion AS d', 'b.ciudad_id', 'd.id')
                ->join('deptos AS e', 'd.deptos_id', 'e.id')
                ->join('pais AS f', 'e.pais_id', 'f.id')
                ->join('agencia AS g', 'g.id', 'b.agencia_id')
                ->select(
                    'a.num_bolsa',
                    'c.num_master',
                    'd.nombre AS ciudad',
                    'f.descripcion AS pais',
                    'g.descripcion AS agencia',
                    'g.logo',
                    'b.tipo_consolidado',
                    DB::raw("'consolidado' AS master")
                )
                ->where([
                    ['a.deleted_at', null],
                    ['b.deleted_at', null],
                    ['a.consolidado_id', $id],
                ])
                ->groupBy(
                    'a.num_bolsa',
                    'c.num_master',
                    'd.nombre',
                    'f.descripcion',
                    'g.descripcion',
                    'g.logo',
                    'b.tipo_consolidado'
                )
                ->get();
        }

        $pdf = PDF::loadView('pdf.labels.bolsasConsolidado', compact('data'))
            ->setPaper(array(0, 0, 550, 700), 'landscape');
        $nameDocument = 'Label Bolsa';
        return $pdf->stream($nameDocument . '.pdf');
    }

    public function exportLiquimp($id)
    {
        $data = DB::table('consolidado_detalle AS a')
            ->join('documento_detalle AS b', 'a.documento_detalle_id', 'b.id')
            ->join('posicion_arancelaria AS c', 'c.id', 'b.arancel_id2')
            ->join('shipper AS d', 'd.id', 'b.shipper_id')
            ->leftJoin('localizacion AS e', 'e.id', 'd.localizacion_id')
            ->leftJoin('deptos AS f', 'e.deptos_id', 'f.id')
            ->leftJoin('pais AS g', 'f.pais_id', 'g.id')
            ->join('consignee AS i', 'i.id', 'b.consignee_id')
            ->leftJoin('localizacion AS j', 'i.localizacion_id', 'j.id')
            ->leftJoin('deptos AS k', 'j.deptos_id', 'k.id')
            ->leftJoin('pais AS l', 'k.pais_id', 'l.id')
            ->select(
                'b.num_warehouse',
                'b.num_guia',
                'c.pa',
                'c.arancel',
                'c.iva',
                'b.contenido2 AS contenido',
                'b.declarado2 AS declarado',
                'b.peso2 AS peso_lb',
                'b.piezas',
                'd.nombre_full AS ship',
                'd.direccion AS ship_dir',
                'd.zip AS ship_zip',
                'd.telefono AS ship_tel',
                'e.nombre AS ship_ciu',
                'f.descripcion AS ship_depto',
                'g.descripcion AS ship_pais',
                'a.shipper AS ship_json',
                'i.nombre_full AS cons',
                'i.direccion AS cons_dir',
                'j.nombre AS cons_ciu',
                'k.descripcion AS cons_depto',
                'l.descripcion AS cons_pais',
                'i.telefono AS cons_tel',
                'i.zip AS cons_zip'
            )
            ->where([
                ['a.deleted_at', null],
                ['b.deleted_at', null],
                ['a.consolidado_id', $id],
            ])->orderBy('b.num_warehouse', 'ASC')->get();
        return Excel::download(
            new ConsolidadoExport('exports.excelLiquimp', array('datos' => $data,)),
            'Excel Liquimp.xlsx',
            \Maatwebsite\Excel\Excel::XLSX
        );
    }

    public function exportCellar($id)
    {
        $data = DB::table('consolidado_detalle AS a')
            ->join('documento_detalle AS b', 'a.documento_detalle_id', 'b.id')
            ->join('documento AS doc', 'b.documento_id', 'doc.id')
            ->join('posicion_arancelaria AS c', 'c.id', 'b.arancel_id2')
            ->join('shipper AS d', 'd.id', 'b.shipper_id')
            ->leftJoin('localizacion AS e', 'e.id', 'd.localizacion_id')
            ->leftJoin('deptos AS f', 'e.deptos_id', 'f.id')
            ->leftJoin('pais AS g', 'f.pais_id', 'g.id')
            ->join('consignee AS i', 'i.id', 'b.consignee_id')
            ->leftJoin('localizacion AS j', 'i.localizacion_id', 'j.id')
            ->leftJoin('deptos AS k', 'j.deptos_id', 'k.id')
            ->leftJoin('pais AS l', 'k.pais_id', 'l.id')
            ->select(
                'doc.consecutivo',
                'b.num_warehouse',
                'b.num_guia',
                'c.pa',
                'c.arancel',
                'c.iva',
                'b.contenido2 AS contenido',
                'b.declarado2 AS declarado',
                'b.peso2 AS peso_lb',
                'b.piezas',
                'd.nombre_full AS ship',
                'd.direccion AS ship_dir',
                'd.zip AS ship_zip',
                'd.telefono AS ship_tel',
                'e.nombre AS ship_ciu',
                'f.descripcion AS ship_depto',
                'g.descripcion AS ship_pais',
                'a.shipper AS ship_json',
                'i.nombre_full AS cons',
                'i.direccion AS cons_dir',
                'j.nombre AS cons_ciu',
                'k.descripcion AS cons_depto',
                'l.descripcion AS cons_pais',
                'i.telefono AS cons_tel',
                'i.zip AS cons_zip',
                DB::raw("(SELECT GROUP_CONCAT(tracking.codigo) FROM tracking WHERE tracking.documento_detalle_id = b.id) as tracking")
            )
            ->where([
                ['a.deleted_at', null],
                ['b.deleted_at', null],
                ['a.consolidado_id', $id],
            ])->orderBy('b.num_warehouse', 'ASC')->get();
        return Excel::download(
            new ConsolidadoExport('exports.excelBodega', array('datos' => $data,)),
            'Excel Bodega.xlsx',
            \Maatwebsite\Excel\Excel::XLSX
        );
    }

    public function getStatusDocument($id)
    {
        $estatus = Documento::select('documento.estado_id')
            ->where([
                ['documento.deleted_at', null],
                ['documento.id', $id],
            ])
            ->first();
        return $estatus;
    }

    // esta consulta era para el sidebar right
    public function getDataSearchDocument($data = false, $type)
    {
        if ($data === 'false') {
            $data = false;
        }
        /* ESTATUS DEL DOCUMENTO */
        // DB::connection()->enableQueryLog();
        $datos = DB::table('documento_detalle as c')
            ->join('documento as d', 'c.documento_id', 'd.id')
            ->join('shipper as e', 'd.shipper_id', 'e.id')
            ->join('consignee as f', 'd.consignee_id', 'f.id')
            ->join('localizacion as g', 'f.localizacion_id', 'g.id')
            ->join('deptos as h', 'g.deptos_id', 'h.id')
            ->join('pais as i', 'h.pais_id', 'i.id')
            ->leftJoin('tracking as t', 'c.id', 't.documento_detalle_id')
            ->select(
                'c.id',
                'c.peso',
                'c.valor AS declarado',
                'c.num_warehouse AS name',
                'c.num_guia',
                'c.contenido',
                'e.nombre_full AS procedencia',
                'f.nombre_full AS consignee',
                'c.num_consolidado',
                'g.nombre AS ciudad',
                'h.descripcion AS depto',
                'i.descripcion AS pais',
                DB::raw("(SELECT GROUP_CONCAT(tracking.codigo) FROM tracking WHERE tracking.documento_detalle_id = c.id) as tracking"),
                DB::raw("(SELECT GROUP_CONCAT(tracking.created_at) FROM tracking WHERE tracking.documento_detalle_id = c.id) as tracking_create")
            )
            ->where([
                ['c.deleted_at', null]
            ])
            ->when($data, function ($query, $data) use ($type) {
                if ($type == 'Warehouse') {
                    return $query->whereRaw("(c.num_warehouse LIKE '%" . $data . "%')");
                } else if ($type == 'Consignee') {
                    return $query->whereRaw("(f.nombre_full LIKE '%" . $data . "%')");
                } else if ($type == 'Tracking') {
                    return $query->whereRaw("(t.codigo LIKE '%" . $data . "%')");
                }
            })
            ->groupBy(
                'c.id',
                'c.peso',
                'declarado',
                'name',
                'c.num_guia',
                'c.contenido',
                'procedencia',
                'consignee',
                'c.num_consolidado',
                'ciudad',
                'depto',
                'pais',
                'tracking'
            )
            ->get();
        // return DB::getQueryLog();

        $datos2 = [];
        if ($type == 'Tracking' || $type == 'Consignee') {
            $datos2 = DB::table('tracking AS a')
                ->leftJoin('consignee as b', 'a.consignee_id', 'b.id')
                ->select(
                    'a.codigo AS tracking',
                    'a.contenido',
                    'a.created_at AS tracking_date',
                    'a.consignee_id',
                    'a.documento_detalle_id',
                    'b.nombre_full AS consignee'
                )
                ->where([
                    ['a.documento_detalle_id', null],
                ])
                ->whereRaw("(a.codigo LIKE '%" . $data . "%' OR b.nombre_full LIKE '%" . $data . "%')")
                ->get();
        }

        $answer = array(
            "data" => $datos,
            "data2" => $datos2,
            "code"  => 200,
        );
        return $answer;
    }

    public function updateShipperConsignee($id, $data_id, $op)
    {
        $update = ['consignee_id' => $data_id];
        if ($op == 'shipper') {
            $update = ['shipper_id' => $data_id];
        }
        DB::table('documento_detalle')
            ->where('id', $id)
            ->update($update);
        $answer = array(
            'code' => 200,
        );
        return $answer;
    }

    public function uploadFileStatus(Request $request)
    {
        DB::table('tbl_status_aux')->truncate();
        $data = (new FastExcel)->import($request->file('file'), function ($line) {
            $trans = trim($line['Transportadora']);
            $guia = trim($line['Guia_transportadora']);
            if ($trans == '') {
                $trans = null;
            }
            if ($guia == '') {
                $guia = null;
            }
            DB::table('tbl_status_aux')->insert([
                [
                    'wh'                  => trim($line['Warehouse']),
                    'status'              => trim($line['Status']),
                    'transportadora'      => $trans,
                    'guia_transportadora' => $guia
                ],
            ]);
        });
        return 200;
    }

    public function validateUploadDocs()
    {
        $data = DB::table('tbl_status_aux AS a')
            ->leftjoin('documento_detalle AS b', 'a.wh', 'b.num_warehouse')
            ->leftjoin('status AS c', 'a.status', 'c.descripcion')
            ->select(
                DB::raw('(a.id + 1) AS fila'),
                'a.status',
                'c.id AS status_id',
                'a.wh',
                'b.id AS documento_detalle_id'
            )
            ->where('b.num_warehouse', null)
            ->orWhere('c.id', null)
            ->orderBy('fila', 'ASC')
            ->get();

        return $data;
    }

    public function insertStatusUploadDocument()
    {
        try {
            $data = DB::select("INSERT INTO status_detalle (status_id, usuario_id, documento_detalle_id, codigo, transportadora, num_transportadora ) SELECT
        c.id AS status_id,
        1 AS usuario_id,
        b.id AS documento_detalle_id,
        a.wh,
        a.transportadora,
        a.guia_transportadora AS num_transportadora
        FROM
        tbl_status_aux AS a
        INNER JOIN documento_detalle AS b ON a.wh = b.num_warehouse
        INNER JOIN status AS c ON a.status = c.descripcion;");

            return array('code' => 200);
        } catch (\Exception $e) {
            return array('error' => $e, 'code' => 500);
        }
    }

    public function getDataShipperConsignee($table, $data)
    {
        if ($table == 'shipper') {
            $datos = Shipper::where([
                ['nombre_full', 'LIKE', '%' . $data . '%'],
                ['deleted_at', null]
            ])->get();
        } else {
            $datos = Consignee::where([
                ['nombre_full', 'LIKE', '%' . $data . '%'],
                ['deleted_at', null]
            ])->get();
        }
        $answer = array(
            'code' => 200,
            'data' => $datos
        );
        return \Response::json($answer);
    }

    public function getDataShipperConsigneeById($table, $id)
    {
        if ($table == 'shipper') {
            $datos = Shipper::where([
                ['id', $id],
                ['deleted_at', null]
            ])->first();
        } else {
            $datos = Consignee::where([
                ['id', $id],
                ['deleted_at', null]
            ])->first();
        }
        $answer = array(
            'code' => 200,
            'data' => $datos
        );
        return \Response::json($answer);
    }


    public function internalManifest($id)
    {
        try {
            $count_groups = 0;
            $data = DB::table('consolidado_detalle AS a')
                ->join('documento_detalle AS b', 'a.documento_detalle_id', 'b.id')
                ->join('documento AS c', 'c.id', 'b.documento_id')
                ->join('consignee AS d', 'c.consignee_id', 'd.id')
                ->join('localizacion AS e', 'd.localizacion_id', 'e.id')
                ->select(
                    'b.id',
                    'b.num_warehouse',
                    'd.nombre_full',
                    'e.nombre',
                    'd.id AS localizacion_id',
                    'd.direccion'
                )
                ->where([['a.deleted_at', null], ['a.consolidado_id', $id]])
                ->get();
            foreach ($data as $key => $value) {
                $det = DB::table('documento_detalle AS a')
                    ->join('documento AS b', 'a.documento_id', 'b.id')
                    ->join('consignee AS c', 'b.consignee_id', 'c.id')
                    ->join('localizacion AS d', 'c.localizacion_id', 'd.id')
                    ->select(
                        'a.id',
                        'a.num_warehouse',
                        'c.nombre_full',
                        'c.direccion',
                        'd.id AS localizacion_id',
                        'd.nombre'
                    )
                    ->where([
                        ['a.deleted_at', null],
                        ['a.mintic', null],
                        ['a.agrupado', $value->id],
                        ['a.id', '<>', $value->id]
                    ])
                    ->get();
                if (count($det) > 0) {
                    $count_groups += count($det) + 1;
                }
                $data[$key]->groups = $det;
            }
            // return \Response::json($data);
            Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
                $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
            });
            return Excel::download(
                new InternalManifest('exports.excelInternalManifest', array('datos' => $data, 'count_groups' => $count_groups)),
                'Manifiesto Interno.xlsx',
                \Maatwebsite\Excel\Excel::XLSX
            );
        } catch (Exception $e) {
            return array('error' => $e, 'code' => 500);
        }
    }
}
