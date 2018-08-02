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

class DocumentoController extends Controller
{
    public function __construct(){
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
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->assignPermissionsJavascript('documento');
        return view('templates/documento/index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($tipo_documento_id)
    {
        $this->assignPermissionsJavascript('documento');
        $tipo      = TipoDocumento::findOrFail($tipo_documento_id);
        $agencias  = Agencia::all();
        $servicios = Servicios::all();
        $embarques = MaestraMultiple::select('id', 'nombre')
            ->where([['modulo_id', 5], ['deleted_at', null]])
            ->get();
        $empaques = MaestraMultiple::select('id', 'nombre')
            ->where([['modulo_id', 6], ['deleted_at', null]])
            ->get();
        $tipoPagos = MaestraMultiple::select('id', 'descripcion')
            ->where([['modulo_id', 2], ['deleted_at', null]])
            ->get();
        $formaPagos = MaestraMultiple::select('id', 'nombre')
            ->where([['modulo_id', 1], ['deleted_at', null]])
            ->get();
        $grupos = MaestraMultiple::select('id', 'nombre')
            ->where([['modulo_id', 3], ['deleted_at', null]])
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

    /**
     * Crea el documento vacio y retorna hacia la vista para terminar el registro.
     *
     * @return \Illuminate\Http\Response
     */
    public function ajaxCreate(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $data                    = new Documento;
                $data->agencia_id        = Auth::user()->agencia_id;
                $data->tipo_documento_id = $request->tipo_documento_id;
                $data->usuario_id        = Auth::user()->id;
                $data->created_at        = date('Y-m-d H:i:s');

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
                                'servicios_id'     => 1,
                                'forma_pago_id'    => 1,
                                'tipo_pago_id'     => 1,
                                'tipo_embarque_id' => 1,
                                'grupo_id'         => 1,
                                'estado_id'        => ($request->tipo_documento_id == 2) ? 27 : 28, //maestra multiple
                                'created_at'       => date('Y-m-d H:i:s'),
                            ],
                        ]);

                        /* GENERAR NUMERO DE GUIA */
                        $caracteres      = strlen($consecutivo);
                        $sumarCaracteres = 7 - $caracteres;
                        $carcater        = '0';
                        $prefijo         = 'WRH';
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
                } else {
                    $answer = array(
                        "error"  => 'Error al intentar Eliminar el registro.',
                        "code"   => 600,
                        "status" => 500,
                    );
                }
                return $answer;
            });
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

/**
 * Show the form for liquidar the specified resource.
 *
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
    public function liquidar($id)
    {
        return $this->edit($id, true);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, $liquidar = false)
    {
        $this->assignPermissionsJavascript('documento');
        $data = Documento::findOrFail($id);

        $tipo     = TipoDocumento::findOrFail($data->tipo_documento_id);
        $agencias = Agencia::select('id', 'descripcion')
            ->where([['deleted_at', null]])
            ->get();
        $embarques = MaestraMultiple::select('id', 'nombre')
            ->where([['modulo_id', 5], ['deleted_at', null]])
            ->get();
        $empaques = MaestraMultiple::select('id', 'nombre')
            ->where([['modulo_id', 6], ['deleted_at', null]])
            ->get();
        $tipoPagos = MaestraMultiple::select('id', 'descripcion')
            ->where([['modulo_id', 2], ['deleted_at', null]])
            ->get();
        $formaPagos = MaestraMultiple::select('id', 'nombre')
            ->where([['modulo_id', 1], ['deleted_at', null]])
            ->get();
        $grupos = MaestraMultiple::select('id', 'nombre')
            ->where([['modulo_id', 3], ['deleted_at', null]])
            ->get();

        $documento = Documento::leftJoin('shipper', 'documento.shipper_id', '=', 'shipper.id')
            ->leftJoin('consignee', 'documento.consignee_id', '=', 'consignee.id')
            ->join('agencia', 'documento.agencia_id', '=', 'agencia.id')
            ->leftJoin('guia_wrh_pivot', 'documento.id', '=', 'guia_wrh_pivot.documento_id')
            ->join('tipo_documento', 'documento.tipo_documento_id', '=', 'tipo_documento.id')
            ->leftJoin('maestra_multiple', 'documento.transporte_id', 'maestra_multiple.id')
            ->leftJoin('pais', 'documento.pais_id', '=', 'pais.id')
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
                'pais.descripcion as pais',
                'central_destino.nombre as central_destino',
                'maestra_multiple.nombre as transporte',
                'maestra_multiple.id as transporte_id'
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
            ->select('documento_detalle.*', 'agencia.descripcion AS nom_agencia', 'posicion_arancelaria.pa AS nom_pa', 'posicion_arancelaria.id AS id_pa', 'shipper.nombre_full AS ship_nomfull', 'consignee.nombre_full AS cons_nomfull', 'maestra_multiple.nombre AS empaque',
                DB::raw("(SELECT Count(a.id) FROM tracking AS a WHERE a.documento_detalle_id = documento_detalle.id AND a.deleted_at IS NULL) as cantidad")
            )
            ->where([['documento_detalle.deleted_at', null], ['documento_detalle.documento_id', $id]])
            ->get();

        $funcionalidades_doc = MaestraMultiple::select('id', 'nombre')
            ->where([['modulo_id', 7], ['deleted_at', null]])
            ->get();

        $funcionalidades = json_decode($documento->funcionalidades);
        if ($liquidar) {
            /* SI EXISTE LIQUIDAR ENTONCES TOMAMOS LAS FUNCIONALIDADES DE LA GUIA */
            $tipoGuia        = TipoDocumento::findOrFail(1); //el 1 es el tipo de documento guia hija
            $funcionalidades = json_decode($tipoGuia->funcionalidades);
        }
        JavaScript::put([
            'functionalities_doc' => $funcionalidades,
            'functionalities_db'  => json_decode(json_encode($funcionalidades_doc)),
        ]);
        $this->AddToLog('Documento ver (' . $id . ') consecutivo (' . $documento->consecutivo . ')');
        return view('templates/documento/documento', compact(
            'documento',
            'detalle',
            'tipo',
            'agencias',
            'embarques',
            'empaques',
            'tipoPagos',
            'formaPagos',
            'grupos',
            'func'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        if ($request->document_type === 'consolidado') {
            try {
                $data                     = Documento::findOrFail($id);
                $data->pais_id            = $request->pais_id;
                $data->central_destino_id = $request->central_destino_id;
                $data->transporte_id      = $request->transporte_id;
                $data->observaciones      = $request->observacion;
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
                $data->agencia_id = $request->agencia_id;
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
                    if ($request->consignee_id == '' and $data->shipper_id != '') {
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
                            $request->session()->flash('print_document', array('id' => $id, 'document' => 'warehouse'));
                        } else {
                            $data->liquidado       = 1;
                            $data->peso            = $request->peso_total;
                            $data->peso_cobrado    = $request->peso_cobrado;
                            $data->valor           = ($request->valor_libra != '') ? $request->valor_libra : 0;
                            $data->valor_libra     = ($request->valor_libra2 != '') ? $request->valor_libra2 : 0;
                            $data->impuesto        = $request->impuesto;
                            $data->flete           = $request->flete;
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
                            $request->session()->flash('print_document', array('id' => $id, 'document' => 'guia'));
                        }
                        $data->piezas            = $request->piezas;
                        $data->volumen           = $request->volumen;
                        $data->observaciones     = $request->observaciones;
                        $data->tipo_documento_id = 1;

                        /* GENERAR NUMERO DE GUIA */
                        $caracteres      = strlen($data->consecutivo);
                        $sumarCaracteres = 7 - $caracteres;
                        $carcater        = '0';
                        $prefijo         = (isset($prefijoGuia->prefijo) and $prefijoGuia->prefijo != '') ? $prefijoGuia->prefijo : '';
                        $prefijoPais     = (isset($prefijoGuia->iso2) and $prefijoGuia->iso2 != '') ? $prefijoGuia->iso2 : '';
                        for ($i = 1; $i <= $sumarCaracteres; $i++) {
                            $prefijo = $prefijo . $carcater;
                        }
                        $data->num_guia = $prefijo . $data->consecutivo . $prefijoPais;
                    }
                }

                if ($request->factura) {
                    $data->factura = $request->factura;
                } else { $data->factura = 0;}

                if ($request->carga_peligrosa) {
                    $data->carga_peligrosa = $request->carga_peligrosa;
                } else { $data->carga_peligrosa = 0;}

                if ($request->re_empacado) {
                    $data->re_empacado = $request->re_empacado;
                } else { $data->re_empacado = 0;}

                if ($request->mal_empacado) {
                    $data->mal_empacado = $request->mal_empacado;
                } else { $data->mal_empacado = 0;}

                if ($request->rota) {
                    $data->rota = $request->rota;
                } else { $data->rota = 0;}

                $data->save();

                $detalle = DocumentoDetalle::where('documento_id', $id)->get();

                /* GENERAR NUMERO DE GUIA A LOS DETALLES DEL DOCUMENTO */
                foreach ($detalle as $val) {
                    $num_guia = $prefijo . $data->consecutivo . $val->paquete . $prefijoPais;
                    DocumentoDetalle::where('id', $val->id)->update([
                        'shipper_id'   => $data->shipper_id,
                        'consignee_id' => $data->consignee_id,
                        'num_guia'     => $num_guia,
                    ]);
                }

                /* INSERCION EN LA TABLA PIVOT DE GUIA_WRH */
                if ($request->document_type === 'warehouse') {
                    DB::table('guia_wrh_pivot')
                        ->where('documento_id', $id)
                        ->update([
                            'servicios_id'     => ($request->servicios_id) ? $request->servicios_id : 1,
                            'tipo_embarque_id' => ($request->tipo_embarque_id) ? $request->tipo_embarque_id : 7,
                        ]);
                } else {
                    if ($request->document_type === 'guia') {
                        DB::table('guia_wrh_pivot')
                            ->where('documento_id', $id)
                            ->update([
                                'servicios_id'     => ($request->servicios_id) ? $request->servicios_id : 1,
                                'tipo_embarque_id' => ($request->tipo_embarque_id) ? $request->tipo_embarque_id : 7,
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
            if ($request->enviarEmailRemitente) {
                $this->sendEmailDocument($id);
                $msn = ' Email remitente enviado!';
            }
            if ($request->enviarEmailDestinatario) {
                $this->sendEmailDocument($id);
                $msn .= ' Email destinatario enviado!';
            }
            if ($msn) {
                $request->session()->put('sendemail', $msn);
            }
            
            $this->AddToLog('Documento WRH/Guia actualizado (' . $id . ')');
        }

        return redirect()->route('documento.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteDetailConsolidado($id, $id_detalle, $logical)
    {
        $obj = DB::table('consolidado_detalle')->where('id', $id_detalle)->first();
        DB::table('consolidado_detalle')->where([['id', $id_detalle]])->delete();

        $data              = DocumentoDetalle::findOrFail($obj->documento_detalle_id);
        $data->consolidado = 0;
        $data->save();
        $this->AddToLog('Consolidado detalle eliminado (' . $id_detalle . ')');
        $answer = array(
            "code" => 200,
        );

        return $answer;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $table = null)
    {
        if ($table) {
            $data = DocumentoDetalle::findOrFail($id);
            $data->delete();
            $this->AddToLog('Documento detalle eliminado (' . $id . ')');
        } else {
            $data = Documento::findOrFail($id);
            $data->delete();
            $this->AddToLog('Documento eliminado (' . $id . ')');
        }
    }

    /**
     * Actualiza el campo deleted_at del registro seleccionado.
     *
     * @param  int  $id
     * @param  boolean  $deleteLogical
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Restaura registro eliminado
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Obtener todos los registros de la tabla para el datatable
     *
     * @return \Illuminate\Http\Response
     */
    public function getAll(Request $request)
    {
        /* GRILLA */
        if ($request->id_tipo_doc == 3) {
            $codigo = 'consecutivo';
            $sql    = DB::table('documento')
                ->leftJoin('shipper', 'documento.shipper_id', '=', 'shipper.id')
                ->leftJoin('consignee', 'documento.consignee_id', '=', 'consignee.id')
                ->join('agencia', 'documento.agencia_id', '=', 'agencia.id')
                ->select('documento.id as id', 'documento.liquidado', 'documento.tipo_documento_id as tipo_documento_id', 'documento.' . $codigo . ' as codigo', 'documento.created_at as fecha', 'shipper.nombre_full as ship_nomfull', 'consignee.nombre_full as cons_nomfull', 'consignee.correo as email_cons', 'agencia.descripcion as agencia', $codigo,
                    DB::raw("(SELECT IFNULL(COUNT(consolidado_detalle.id),0) FROM consolidado_detalle WHERE consolidado_detalle.deleted_at IS NULL AND consolidado_detalle.consolidado_id = documento.id) as cantidad"),
                    DB::raw("(SELECT IFNULL(Sum(documento_detalle.peso2),0) FROM consolidado_detalle INNER JOIN documento_detalle ON consolidado_detalle.documento_detalle_id = documento_detalle.id WHERE consolidado_detalle.deleted_at IS NULL AND consolidado_detalle.consolidado_id = documento.id) as peso"),
                    DB::raw("(SELECT IFNULL(Sum(documento_detalle.volumen),0) FROM consolidado_detalle INNER JOIN documento_detalle ON consolidado_detalle.documento_detalle_id = documento_detalle.id WHERE consolidado_detalle.deleted_at IS NULL AND consolidado_detalle.consolidado_id = documento.id) as volumen")
                )
                ->where([
                    ['documento.deleted_at', null],
                    ['shipper.deleted_at', null],
                    ['consignee.deleted_at', null],
                    ['agencia.deleted_at', null],
                    ['documento.tipo_documento_id', $request->id_tipo_doc],
                    ['documento.agencia_id', Auth::user()->agencia_id],
                ])
                ->orderBy('documento.created_at', 'DESC');
        } else {
            /* CODIGO PARA TRAER LOS DOCUMENTOS DIFERENTES A CONSOLIDADOS */

            $sql = DB::table('documento')
                ->leftJoin('shipper', 'documento.shipper_id', '=', 'shipper.id')
                ->leftJoin('consignee', 'documento.consignee_id', '=', 'consignee.id')
                ->join('agencia', 'documento.agencia_id', '=', 'agencia.id')
                ->leftJoin(DB::raw("(SELECT
                                            Count(DISTINCT z.consolidado) AS consolidado,
                                            z.documento_id
                                        FROM
                                            documento_detalle AS z
                                        GROUP BY
                                            z.documento_id
                                    ) AS t"), "documento.id", "t.documento_id")
                ->select('documento.id as id', 'documento.liquidado', 'documento.tipo_documento_id as tipo_documento_id', 'documento.consecutivo as codigo', 'documento.num_warehouse', 'documento.num_guia', 'documento.created_at as fecha', 'shipper.nombre_full as ship_nomfull', 'consignee.nombre_full as cons_nomfull', 'consignee.correo as email_cons', 'agencia.descripcion as agencia',
                    DB::raw("(SELECT Count(a.id) AS cantidad FROM documento_detalle AS a WHERE a.documento_id = documento.id AND a.deleted_at IS NULL) as cantidad"),
                    DB::raw("(SELECT Sum(documento_detalle.peso) FROM documento_detalle WHERE documento_detalle.documento_id = documento.id AND documento_detalle.deleted_at IS NULL) as peso"),
                    DB::raw("(SELECT Sum(documento_detalle.volumen) FROM documento_detalle WHERE documento_detalle.documento_id = documento.id AND documento_detalle.deleted_at IS NULL) as volumen"),
                    't.consolidado'
                )
                ->where([
                    ['documento.deleted_at', null],
                    ['shipper.deleted_at', null],
                    ['consignee.deleted_at', null],
                    ['agencia.deleted_at', null],
                    ['documento.tipo_documento_id', $request->id_tipo_doc],
                    ['documento.agencia_id', Auth::user()->agencia_id]
                ])
                ->orderBy('documento.created_at', 'DESC');
        }

        return \DataTables::of($sql)->make(true);
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
            $data = (new DocumentoDetalle)->fill($request->all());
            if ($request->valor == '') {
                $data->valor = 0;
            }
            if ($request->declarado2 == '') {
                $data->declarado2 = 0;
            }

            $data->created_at = date('Y-m-d H:i:s');
            if ($data->tracking == '') {
                $data->tracking = null;
            }

            /* OBTENER EL PREFIJO DE LA CIUDAD DEL CONSIGNEE PARA HACER EL NUMERO DE GUIA */
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

            $documento = Documento::findOrFail($data->documento_id);

            $documentoD = DocumentoDetalle::select('documento_detalle.id')
                ->where([
                    ['documento_detalle.documento_id', $data->documento_id],
                ])->get();
            // $data->num_guia      = $documento->num_guia . '' . (count($documentoD) + 1);

            /* GENERAR NUMERO DE GUIA */
            $caracteres      = strlen($documento->consecutivo);
            $sumarCaracteres = 7 - $caracteres;
            $carcater        = '0';
            $prefijo         = (isset($prefijoGuia->prefijo) and $prefijoGuia->prefijo != '') ? $prefijoGuia->prefijo : '';
            $prefijoPais     = (isset($prefijoGuia->iso2) and $prefijoGuia->iso2 != '') ? $prefijoGuia->iso2 : '';
            for ($i = 1; $i <= $sumarCaracteres; $i++) {
                $prefijo = $prefijo . $carcater;
            }
            $data->num_guia = $prefijo . $documento->consecutivo . (count($documentoD) + 1) . $prefijoPais;
            $data->paquete  = (count($documentoD) + 1);

            /* GENERAR NUMERO DE WAREHOUSE */
            $data->num_warehouse = $documento->num_warehouse . '' . (count($documentoD) + 1);
            if ($documento->liquidado === 1) {
                $data->liquidado = 1;
            }

            if ($data->save()) {
                /* INSERTAR EN STATUS_DETALLE*/
                DB::table('status_detalle')->insert([
                    [
                        'status_id'            => 2,
                        'usuario_id'           => Auth::user()->id,
                        'documento_detalle_id' => $data->id,
                        'codigo'               => $data->num_warehouse,
                        'fecha_status'         => date('Y-m-d H:i:s'),
                        'created_at'           => date('Y-m-d H:i:s'),
                    ],
                ]);

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
            } else {
                $answer = array(
                    "error"  => 'Error al intentar Eliminar el registro.',
                    "code"   => 600,
                    "status" => 500,
                );
            }
            return $answer;
        } catch (\Exception $e) {
            return $e;
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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

    public function pdfTsa()
    {
        $agencia = $this->getDataAgenciaById(1);
        $pdf     = PDF::loadView('pdf.tsaPdf', compact('agencia'));
        $this->AddToLog('Impresion TSA');
        return $pdf->stream('TSA.pdf');
    }

    public function pdf($id, $document, $id_detalle = null)
    {
        $documento = DB::table('documento')
            ->leftJoin('master AS m', 'documento.master_id', '=', 'm.id')
            ->leftJoin('aerolineas_aeropuertos AS aerolinea', 'm.aerolineas_id', '=', 'aerolinea.id')
            ->leftJoin('aerolineas_aeropuertos AS aeropuerto', 'm.aeropuertos_id', '=', 'aeropuerto.id')
            ->leftJoin('transportador', 'transportador.id', '=', 'm.consignee_id')
            ->leftJoin('shipper', 'documento.shipper_id', '=', 'shipper.id')
            ->leftJoin('consignee', 'documento.consignee_id', '=', 'consignee.id')
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
            ->select(
                'documento.*', 'users.name as usuario',
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
                'agencia.descripcion as agencia',
                'agencia.telefono as agencia_tel',
                'agencia.direccion as agencia_dir',
                'agencia.zip as agencia_zip',
                'ciudad_agencia.nombre AS agencia_ciudad',
                'deptos_agencia.descripcion AS agencia_depto',
                'pais_agencia.descripcion AS agencia_pais',
                'embarque.nombre as tipo_embarque',
                'forma_pago.nombre as forma_pago',
                'tipo_pago.nombre as tipo_pago',
                'grupo.nombre as grupo',
                'servicios.nombre AS servicio',
                'tipo_documento.nombre AS tipo_documento',
                'm.num_master',
                'aerolinea.nombre AS aerolinea',
                'aeropuerto.nombre AS aeropuerto',
                'transportador.nombre AS consignee_master',
                'transportador.ciudad AS ciudad_destino'
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
                'deptos_consignee.descripcion AS cons_depto'
            )
            ->where($where)
            ->get();

        if ($document === 'guia') {
            $this->AddToLog('Impresion Guia (' . $documento->id . ')');
            $pdf          = PDF::loadView('pdf.guiaPdf', compact('documento', 'detalle'));
            $nameDocument = $documento->tipo_documento . '-' . $documento->id;
        } else {
            if ($document === 'warehouse') {
                $this->AddToLog('Impresion warehouse (' . $documento->id . ')');
                $pdf          = PDF::loadView('pdf.warehousePdf', compact('documento', 'detalle'));
                $nameDocument = $documento->tipo_documento . '-' . $documento->id;
            } else {
                if ($document === 'invoice') {
                    $this->AddToLog('Impresion Invoice (' . $documento->id . ')');
                    $pdf          = PDF::loadView('pdf.invoicePdf', compact('documento', 'detalle'));
                    $nameDocument = 'comercial invoice -' . $documento->id;
                } else {
                    if ($document === 'consolidado_guias') {
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
                                        z.agrupado,
                                        SUM(x.peso) AS peso,
                                        SUM(x.peso2) AS peso2,
                                        GROUP_CONCAT(

                                            IF (
                                                z.flag = 1,
                                                CONCAT(

                                                    IF (
                                                        x.liquidado = 1,
                                                        CONCAT('<label>- ', x.num_guia, \"</label><a style='float: right;cursor:pointer;color:red' title='Quitar' data-toggle='tooltip' onclick='removerGuiaAgrupada(\",z.id,\")'><i class='fa fa-remove' style='font-size: 15px;'></i></a>\"),
                                                        CONCAT('<label>- ', x.num_warehouse, \"</label><a style='float: right;cursor:pointer;color:red' title='Quitar' data-toggle='tooltip' onclick='removerGuiaAgrupada(\",z.id,\")'><i class='fa fa-remove' style='font-size: 15px;'></i></a>\")
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
                                'e.nombre as ship_ciudad',
                                'f.descripcion as ship_depto',
                                'pais.descripcion as ship_pais',
                                'd.nombre_full as cons_nomfull',
                                'g.nombre as cons_ciudad',
                                'h.descripcion as cons_depto',
                                'd.direccion as cons_dir',
                                'd.telefono as cons_tel',
                                'i.descripcion as cons_pais',
                                'b.declarado2',
                                'j.peso2',
                                'b.contenido2',
                                'b.liquidado'
                            )
                            ->where([['a.deleted_at', null], ['a.consolidado_id', $id], ['a.flag', 0]])
                            ->get();
                        $this->AddToLog('Impresion Consolidado guias (' . $id . ')');
                        // $pdf          = PDF::loadView('pdf.consolidadoGuiasPdf', compact('documento', 'detalle', 'detalleConsolidado'));
                        $pdf          = PDF::loadView('pdf.consolidadoGuiasPdf2', compact('documento', 'detalle', 'detalleConsolidado'));
                        $nameDocument = 'Guias -' . $documento->id;
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
                                        z.agrupado,
                                        SUM(x.peso) AS peso,
                                        SUM(x.peso2) AS peso2,
                                        GROUP_CONCAT(

                                            IF (
                                                z.flag = 1,
                                                CONCAT(

                                                    IF (
                                                        x.liquidado = 1,
                                                        CONCAT('<label>- ', x.num_guia, ' (', x.peso2, ' lbs) ', ' ($ ', x.declarado2, '.00) ', \"</label><a style='float: right;cursor:pointer;color:red' title='Quitar' data-toggle='tooltip' onclick='removerGuiaAgrupada(\",z.id,\")'><i class='fa fa-remove' style='font-size: 15px;'></i></a>\"),
                                                        CONCAT('<label>- ', x.num_warehouse, ' (', x.peso2, ' lbs) ', ' ($ ', x.declarado2, '.00) ', \"</label><a style='float: right;cursor:pointer;color:red' title='Quitar' data-toggle='tooltip' onclick='removerGuiaAgrupada(\",z.id,\")'><i class='fa fa-remove' style='font-size: 15px;'></i></a>\")
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
                                    DB::raw('CONCAT_WS(" ", c . primer_nombre, c . segundo_nombre, c . primer_apellido, c . segundo_apellido) as nom_ship'),
                                    'c.direccion as dir_ship',
                                    'c.telefono as tel_ship',
                                    'e.nombre as ciu_ship',
                                    'f.descripcion as depto_ship',
                                    'pais.descripcion as pais_ship',
                                    DB::raw('CONCAT_WS(" ", d . primer_nombre, d . segundo_nombre, d . primer_apellido, d . segundo_apellido) as nom_cons'),
                                    'g.nombre as ciu_cons',
                                    'h.descripcion as depto_cons',
                                    'd.direccion as dir_cons',
                                    'd.telefono as tel_cons',
                                    'i.descripcion as pais_cons',
                                    'b.declarado2',
                                    'j.peso2',
                                    'b.contenido2',
                                    'b.liquidado'
                                )
                                ->where([['a.deleted_at', null], ['a.consolidado_id', $id], ['a.flag', 0]])
                                ->get();
                            $this->AddToLog('Impresion Consolidado (' . $id . ')');
                            // $pdf          = PDF::loadView('pdf.consolidadoPdf', compact('documento', 'detalle', 'detalleConsolidado'));
                            $pdf          = PDF::loadView('pdf.consolidadoPdf2', compact('documento', 'detalle', 'detalleConsolidado'));
                            $nameDocument = $documento->tipo_documento . '-' . $documento->id;
                        }
                    }
                }
            }
        }

        // return $pdf->download($nameDocument . ' . pdf');
        return $pdf->stream($nameDocument . '.pdf'); //visualizar en el navegador
    }

    public function pdfLabel($id, $document, $id_detalle = null)
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
                ['documento.id', $id],
            ])
            ->first();

        $where = [['documento_detalle.deleted_at', null], ['documento_detalle.documento_id', $id]];
        if ($id_detalle != null) {
            $where[] = array('documento_detalle.id', $id_detalle);
        }
        $detalle = DocumentoDetalle::join('documento as a', 'documento_detalle.documento_id', 'a.id')
            ->leftJoin('shipper', 'documento_detalle.shipper_id', '=', 'shipper.id')
            ->leftJoin('consignee', 'documento_detalle.consignee_id', '=', 'consignee.id')
            ->leftJoin('localizacion AS ciudad_consignee', 'consignee.localizacion_id', '=', 'ciudad_consignee.id')
            ->leftJoin('localizacion AS ciudad_shipper', 'shipper.localizacion_id', '=', 'ciudad_shipper.id')
            ->leftJoin('deptos AS deptos_consignee', 'ciudad_consignee.deptos_id', '=', 'deptos_consignee.id')
            ->leftJoin('deptos AS deptos_shipper', 'ciudad_shipper.deptos_id', '=', 'deptos_shipper.id')
            ->select(
                'documento_detalle.id',
                'documento_detalle.contenido',
                'documento_detalle.contenido2',
                'documento_detalle.tracking',
                'documento_detalle.volumen',
                'documento_detalle.valor',
                'documento_detalle.declarado2',
                'documento_detalle.peso',
                'documento_detalle.peso2',
                'documento_detalle.' . $codigo . ' as codigo',
                'documento_detalle.num_warehouse',
                'documento_detalle.num_guia',
                'documento_detalle.created_at',
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
                'deptos_consignee.descripcion AS cons_depto'
            )
            ->where($where)
            ->get();

        $this->AddToLog('Impresion labels (' . $documento->id . ')');
        $pdf = PDF::loadView('pdf.labelWG', compact('documento', 'detalle', 'document'))
            ->setPaper(array(25, -25, 260, 360), 'landscape');
        $nameDocument = 'Label' . $document . '-' . $documento->id;

        // return $pdf->download($nameDocument.'.pdf');
        return $pdf->stream($nameDocument . '.pdf');
    }

    public function buscarGuias($id, $num_guia, $num_bolsa, $pais_id)
    {

        $detalle = DocumentoDetalle::select('documento_detalle.id', 'documento_detalle.consignee_id')
            ->where([
                ['documento_detalle.deleted_at', null],
            ])
            ->whereRaw('(documento_detalle.num_warehouse = "' . $num_guia . '" or documento_detalle.num_guia = "' . $num_guia . '")')
            ->first();
        if (count($detalle) > 0) {
            /* VERIFICAR QUE EL NUMERO INGRESADO NO ESTE EN OTRO CONSOLIDADO O YA ESTE INGRESADO */
            $cons_detail = DB::table('consolidado_detalle as a')
                ->join('documento as b', 'a.consolidado_id', 'b.id')
                ->select('a.consolidado_id', 'b.consecutivo')
                ->where([['a.deleted_at', null], ['a.documento_detalle_id', $detalle->id]])
                ->first();

            if (count($cons_detail) == 0) {
                /* VERIFICAR SI LA GUIA O WAREHOUSE INGRESADO PERTENECE AL PAIS DEL CONSOLIDADO */
                $cons = DB::table('consignee as a')
                    ->join('localizacion as b', 'a.localizacion_id', 'b.id')
                    ->join('deptos as c', 'b.deptos_id', 'c.id')
                    ->select(
                        'c.pais_id'
                    )
                    ->where([
                        ['a.id', $detalle->consignee_id]
                    ])
                    ->first();
                if($cons->pais_id == $pais_id){
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
                }else{
                   $answer = array(
                        "code" => 600,
                        "data" => 'El país de destino de el documento ingresado no coincide con el país de este consolidado'
                    ); 
                }
            } else {
                $answer = array(
                    "code" => 600,
                    "data" => 'El número de Guía / WRH ingresado, ya se encuentra registrado en el consolidado # ' . $cons_detail->consecutivo,
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

    public function getAllConsolidadoDetalle($id)
    {
        $detalle = DB::table('consolidado_detalle AS a')
            ->join('documento AS b', 'a.consolidado_id', 'b.id')
            ->join('documento_detalle AS c', 'a.documento_detalle_id', 'c.id')
            ->leftJoin('shipper as d', 'c.shipper_id', 'd.id')
            ->leftJoin('consignee as e', 'c.consignee_id', 'e.id')
            ->leftJoin('posicion_arancelaria as f', 'c.arancel_id2', 'f.id')
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
                                                    CONCAT('<label>- ', x.num_guia, ' (', x.peso2, ' lbs) ', ' ($ ', x.declarado2, '.00) ', \"</label><a style='float: right;cursor:pointer;color:red' title='Quitar' data-toggle='tooltip' onclick='removerGuiaAgrupada(\",z.id,\")'><i class='fa fa-remove' style='font-size: 15px;'></i></a>\"),
                                                    CONCAT('<label>- ', x.num_warehouse, ' (', x.peso2, ' lbs) ', ' ($ ', x.declarado2, '.00) ', \"</label><a style='float: right;cursor:pointer;color:red' title='Quitar' data-toggle='tooltip' onclick='removerGuiaAgrupada(\",z.id,\")'><i class='fa fa-remove' style='font-size: 15px;'></i></a>\")
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
                'c.documento_id',
                'a.documento_detalle_id',
                'b.estado_id',
                'a.num_bolsa',
                'c.num_warehouse',
                'c.num_guia',
                'a.shipper AS shipper_json',
                'a.consignee AS consignee_json',
                'd.id as shipper_id',
                'd.nombre_full as shipper',
                'd.contactos_json as shipper_contactos',
                'e.id as consignee_id',
                'e.nombre_full as consignee',
                'e.contactos_json as consignee_contactos',
                'c.contenido2',
                'f.id AS pa_id',
                'f.pa',
                'c.declarado2',
                'g.peso2',
                'g.peso',
                'g.guias_agrupadas',
                'c.liquidado',
                DB::raw('(SELECT Count(z.id) FROM consolidado_detalle AS z WHERE z.agrupado = a.documento_detalle_id AND z.deleted_at IS NULL AND z.flag = 1) AS agrupadas')
            )
            ->where([
                ['a.consolidado_id', $id],
                ['a.deleted_at', null],
                ['a.flag', 0],
            ])
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
            } else { $error = $e;}
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
        $detalle = DB::table('documento_detalle AS a')
            ->join('documento as b', 'a.documento_id', 'b.id')
            ->leftJoin('guia_wrh_pivot', 'b.id', '=', 'guia_wrh_pivot.documento_id')
            ->join('consignee as c', 'b.consignee_id', 'c.id')
            ->join('localizacion as d', 'c.localizacion_id', 'd.id')
            ->join('deptos as e', 'd.deptos_id', 'e.id')
            ->select(
                'a.id',
                'a.created_at',
                'a.num_guia',
                'a.num_warehouse',
                'a.liquidado',
                'a.peso2',
                DB::raw('IFNULL(a.declarado2,0) as declarado2')
            )
            ->where([
                ['a.deleted_at', null],
                ['b.deleted_at', null],
                ['b.agencia_id', Auth::user()->agencia_id],
                ['a.consolidado', 0],
                ['guia_wrh_pivot.tipo_embarque_id', $transporte_id],
                ['e.pais_id', $pais_id],
            ])
            ->get();
        return \DataTables::of($detalle)->make(true);
    }

    public function getDataSelectWarehousesModalTagGuia($id)
    {
        $data = DocumentoDetalle::join('documento as b', 'documento_detalle.documento_id', 'b.id')
            ->select('documento_detalle.id', DB::raw('(CASE WHEN b.liquidado = 1 THEN documento_detalle.num_guia ELSE documento_detalle.num_warehouse END) AS name'))
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
                    'created_at'   => date('Y-m-d H:i:s')
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
        $trackings  = array();
        foreach ($detalle as $objD) {
            $trackings[] = DB::table('tracking as a')
                ->select('a.codigo', 'a.contenido')
                ->where([['a.deleted_at', null], ['a.documento_detalle_id', $objD->id]])
                ->get();

            $datosEnvio .= $cont + 1 . '. <strong>Peso:</strong>' . $objD->peso . " Lbs | <strong>Contenido:</strong> " . $objD->contenido . "<br>";
            $datosEnvio .= '<strong>Trackings:</strong><br>';
            foreach ($trackings[$cont] as $t) {
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
            if (filter_var($objConsignee->correo, FILTER_VALIDATE_EMAIL)) {
                /* ENVIO DE EMAIL REPLACEMENT($id_documento, $objAgencia, $objDocumento, $objShipper, $objConsignee, $datosEnvio)*/
                $replacements = $this->replacements($id_documet, $objAgencia, $objDocumento, $objShipper, $objConsignee, $datosEnvio);

                $cuerpo_correo = preg_replace(array_keys($replacements), array_values($replacements), $plantilla->mensaje);
                $asunto_correo = preg_replace(array_keys($replacements), array_values($replacements), $plantilla->subject);

                $from_self = array(
                    'address' => $objAgencia->email,
                    'name'    => $objAgencia->descripcion,
                );

                // $moreUsers     = $objConsignee->correo;
                // $evenMoreUsers = $objConsignee->correo;

                // return new \App\Mail\WarehouseEmail($cuerpo_correo);
                $this->AddToLog('Email enviado (' . $id_documet . ')');
                return Mail::to($objConsignee->correo)
                // ->cc($moreUsers)
                // ->bcc($evenMoreUsers)
                    ->send(new \App\Mail\WarehouseEmail($cuerpo_correo, $from_self, $asunto_correo));
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

    public function createContactsConsolidadoDetalle(Request $request, $id)
    {
        $campos = json_encode($request->all()['campos']);
        $data   = $request->all()['data'];
        DB::table('consolidado_detalle')
            ->where('id', $data['id'])
            ->update([$data['opcion'] => $campos]);
        $answer = array(
            'code' => 200,
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
        }
        $this->AddToLog('Estatus agregado a guias. Conolidado id (' . $id . ')');

        $answer = array(
            'code' => 200,
        );
        return $answer;
    }

    public function restoreShipperConsignee($id, $idD, $table)
    {
        DB::table('consolidado_detalle')
            ->where('id', $idD)
            ->update([$table => null]);
        $answer = array(
            'code' => 200,
        );
        return $answer;
    }

    public function getGuiasAgrupar($id, $id_detalle)
    {
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

        return \DataTables::of($detalle)->make(true);
    }

    public function agruparGuiasConsolidadoCreate(Request $request)
    {
        $deta_guia = DB::table('consolidado_detalle')->select('documento_detalle_id')->where('id', $request->all()['id_detalle'])->first();

        for ($i = 1; $i <= count($request->all()['ids_guias']); $i++) {
            DB::table('consolidado_detalle')
                ->where('documento_detalle_id', $request->all()['ids_guias'][$i])
                ->update(['agrupado' => $deta_guia->documento_detalle_id, 'flag' => 1]);
        }
        $answer = array(
            'code' => 200,
        );
        return $answer;
    }

    public function removerGuiaAgrupada($id, $id_detalle)
    {
        DB::table('consolidado_detalle')
            ->where('id', $id_detalle)
            ->update(['agrupado' => $id_detalle, 'flag' => 0]);
        $answer = array(
            'code' => 200,
        );
        return $answer;
    }

    public function updatePositionArancel(Request $request)
    {
        DB::table('documento_detalle')
            ->where('id', $request->rowData['id_detalle'])
            ->update(['arancel_id2' => $request->rowData['id_pa']]);
        $answer = array(
            'code' => 200,
        );
        return $answer;
    }

}
