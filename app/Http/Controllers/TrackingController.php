<?php

namespace App\Http\Controllers;

use Neodynamic\SDK\Web\WebClientPrint;
use App\Http\Requests\TrackingRequest;
use App\Tracking;
use App\Prealerta;
use Session;
use Auth;
use DataTables;
use JavaScript;
use App\Agencia;
use App\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\sendEmailAlerts;
use Illuminate\Support\Facades\Mail;
use App\Notifications\ChangeState;
use App\User;

class TrackingController extends Controller
{
    use sendEmailAlerts;

    public function __construct()
    {
        $this->middleware('permission:tracking.index')->only('index');
        $this->middleware('permission:tracking.store')->only('store');
        $this->middleware('permission:tracking.update')->only('update');
        $this->middleware('permission:tracking.delete')->only('delete');
    }

    public function index()
    {
      $agencias = Agencia::select('id', 'descripcion')
            ->where([['deleted_at', null]])
            ->get();
      $role_admin = (Auth::user()->isRole('admin')) ? 1 : 0;
      $this->assignPermissionsJavascript('tracking');

      // $update = DB::select(DB::raw("SELECT
      // a.id,
      // c.agencia_id AS agencia_documento
      // FROM
      // tracking AS a
      // INNER JOIN documento_detalle AS b ON a.documento_detalle_id = b.id
      // INNER JOIN documento AS c ON b.documento_id = c.id
      // WHERE
      // c.agencia_id <> 1"));
      // foreach ($update as $key => $value) {
      //   // echo $value->id . ' - '.$value->agencia_documento . '<br>';
      //   $data = Tracking::findOrFail($value->id);
      //   $data->agencia_id = $value->agencia_documento;
      //   $data->save();
      // }
      // exit();

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
      return view('templates/tracking', compact('agencias', 'role_admin', 'wcpScript'));
    }

    public function store(TrackingRequest $request)
    {
        try {
            $data             = (new Tracking)->fill($request->all());
            // $data->agencia_id = Auth::user()->agencia_id;
            if ($request->confirmed_send) {
                $data->confirmed_send = 1;
            }
            $data->created_at = date('Y-m-d H:i:s');
            if ($data->save()) {
              $tr = Prealerta::where('tracking', $request->codigo)->first();
              if($tr){
                $tr->recibido = 1;
                $tr->save();
              }
              $answer = array(
                  "datos"  => $request->all(),
                  "code"   => 200,
                  "status" => 200,
              );
              /* INSERTAR EN STATUS_DETALLE*/
              DB::table('status_detalle')->insert([
                  [
                      'status_id'            => 2,
                      'usuario_id'           => Auth::user()->id,
                      'codigo'               => $request->codigo,
                      'fecha_status'         => date('Y-m-d H:i:s'),
                      'created_at'           => date('Y-m-d H:i:s'),
                  ],
              ]);
              if($request->consignee_id != null){
                $config = $this->getConfig('ingreso_tracking');
                $status = Status::where('id', 9)->first();// 9 es alerta de tracking
                if($status){
                  if($status->email === 1){
                    if ($status->json_data != null) {
                      $json_data = json_decode($status->json_data);
                      if(isset($json_data->email_template_id)){
                        if ($status->view_client) {
                          $user = User::where('consignee_id', $request->consignee_id)->first();
                          // $user->notify(new ChangeState($status));
                        }
                        // $this->verifySendEmail($config->value, $json_data->email_template_id, $request->consignee_id, $request->codigo);
                      }
                    }
                  }
                }
              }
            } else {
                $answer = array(
                    "error"  => 'Error al intentar Eliminar el registro.',
                    "code"   => 600,
                    "status" => 500,
                );
            }

            return $answer;
        } catch (Exception $e) {
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

    public function destroy($id)
    {
        $data = Tracking::findOrFail($id);
        $data->delete();
    }

    public function delete($id, $logical)
    {

        if (isset($logical) and $logical == 'true') {
            $data             = Tracking::findOrFail($id);
            $now              = new \DateTime();
            $data->deleted_at = $now->format('Y-m-d H:i:s');
            if ($data->save()) {
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
            $this->destroy($id);
        }
    }

    public function getAll($grid = false, $add = null, $id = false, $req_consignee = false, $bodega = false)
    {
        $where = [['tracking.deleted_at', null]];
        if (!Auth::user()->isRole('admin')) {
          $where[] = ['tracking.agencia_id', Auth::user()->agencia_id];
        }
        if ($grid == false || $grid == 'false') {
           if ($id != '') {
               $where[] = array('tracking.documento_detalle_id', $id);
           } else {
               // $where[] = array('tracking.documento_detalle_id', null);
           }

           if ($add != null and $add != 'null') {
               $where[] = array('tracking.consignee_id', $add);
               $where[] = array('tracking.documento_detalle_id', NULL);
           } else {
               // if ($req_consignee == false) {
               //     $where[] = array('tracking.consignee_id', null);
               // }
           }
       }else{
         if($bodega === true || $bodega === 'true'){
           $where[] = ['tracking.documento_detalle_id', '<>', null];
         }else{
           $where[] = ['tracking.documento_detalle_id', null];
         }
       }

        $data = Tracking::leftJoin('consignee AS b', 'tracking.consignee_id', 'b.id')
            ->leftJoin('documento_detalle AS c', 'tracking.documento_detalle_id', 'c.id')
            ->leftJoin('agencia AS d', 'tracking.agencia_id', 'd.id')
            ->select(
                'tracking.id',
                'tracking.consignee_id',
                'tracking.documento_detalle_id',
                'tracking.codigo',
                'tracking.contenido AS contenido',
                'c.contenido AS contenido_detalle',
                'tracking.confirmed_send',
                'tracking.created_at as fecha',
                'd.descripcion AS agencia',
                'b.nombre_full as cliente',
                'b.correo as cliente_email',
                'c.num_warehouse',
                DB::raw("(
              		SELECT
              			b.descripcion
              		FROM
              			status_detalle AS a
              		INNER JOIN `status` AS b ON a.status_id = b.id
              		WHERE
              			a.documento_detalle_id = c.id
              		ORDER BY
              			a.id DESC
              		LIMIT 1
              	) AS estatus"),
                DB::raw("(
              		SELECT
              			b.color
              		FROM
              			status_detalle AS a
              		INNER JOIN `status` AS b ON a.status_id = b.id
              		WHERE
              			a.documento_detalle_id = c.id
              		ORDER BY
              			a.id DESC
              		LIMIT 1
              	) AS estatus_color")
            )
            ->where($where)
            ->get();
        return \DataTables::of($data)->make(true);
    }

    public function getAllShipperConsignee($table)
    {
        $data = DB::table($table . ' as a')
            ->select('a.id', 'a.nombre_full as name')
            ->where([
                ['a.deleted_at', null],
                ['a.agencia_id', Auth::user()->agencia_id],
            ])
            ->orderBy('name', 'ASC')
            ->get();
        return \DataTables::of($data)->make(true);
    }

    public function searchTracking($tracking)
    {
        $data = DB::table('prealerta as a')
            ->leftjoin('consignee as b', 'a.consignee_id', 'b.id')
            ->select('a.id', 'a.consignee_id', 'a.tracking', 'a.contenido', 'a.instruccion', 'a.correo', 'a.despachar', 'b.nombre_full')
            ->where([
                ['a.deleted_at', null],
                ['a.tracking', $tracking],
                ['a.agencia_id', Auth::user()->agencia_id],
            ])
            ->first();
        $answer = array(
            'code' => 200,
            'data' => $data,
        );
        return $answer;
    }

    public function addOrDeleteDocument(Request $request)
    {
        $data = DB::table('tracking AS a')
            ->select('a.id', 'a.codigo', 'b.num_warehouse')
            ->leftjoin('documento_detalle as b', 'a.documento_detalle_id', 'b.id')
            ->where([
                ['a.deleted_at', null],
                ['a.codigo', $request->tracking],
                ['a.agencia_id', Auth::user()->agencia_id],
            ])
            ->first();
        if ($data != null) {
            if ($request->option === 'create') {
                if ($data->num_warehouse == null) {
                    DB::table('tracking')
                        ->where('id', $data->id)
                        ->update(['documento_detalle_id' => $request->id_detail], ['consignee_id' => $request->consignee_id]);
                    $answer = array(
                        'code' => 200,
                        'data' => $data,
                        'message' => 'Tracking agregado a este documento.'
                    );

                } else {
                    $answer = array(
                        'code'  => 600,
                        'error' => 'El numero de warehouse ingresado, ya esta asociado al documento (<strong>' . $data->num_warehouse . '</strong>).',
                    );
                }
            } else {
                DB::table('tracking')
                    ->where('id', $data->id)
                    ->update(['documento_detalle_id' => null]);
                $answer = array(
                    'code' => 200,
                    'data' => $data,
                    'message' => 'Tracking retirado de este documento.'
                );
            }
        } else {
            $answer = array(
                'code'  => 700,
                'error' => 'El numero de warehouse ingresado, no esta en la base de datos.',
            );
        }

        return $answer;
    }

    public function validar_tracking(Request $request)
    {
        try {
            $dataTrack = DB::table('tracking')->select('codigo')->where('codigo', $request->element)->first();
            if ($dataTrack) {
                $answer = array(
                    "valid"   => false,
                    "message" => "El registro ya se encuentra en nuestra base de datos.",
                );
            } else {
                $answer = array(
                    "valid"   => true,
                    "message" => "",
                );
            }
            return $answer;
        } catch (Exception $e) {
            return $e;
        }
    }

    public function getTrackingByCreateReceipt()
    {
      // DB::connection()->enableQueryLog();
      $where = [['tracking.deleted_at', null]];
      if (!Auth::user()->isRole('admin')) {
        $where[] = ['tracking.agencia_id', Auth::user()->agencia_id];
      }
      $data = Tracking::join('consignee AS b', 'tracking.consignee_id', 'b.id')
          ->select(
              'tracking.consignee_id',
              'b.nombre_full as cliente',
              'tracking.agencia_id',
              DB::raw("(
                SELECT
                Count(t.id)
                FROM
                tracking AS t
                WHERE
                t.deleted_at IS NULL AND
                t.documento_detalle_id IS NULL AND
                t.consignee_id = tracking.consignee_id
              ) AS cantidad"),
              DB::raw("(
                SELECT
                GROUP_CONCAT(t.codigo)
                FROM
                tracking AS t
                WHERE
                t.deleted_at IS NULL AND
                t.documento_detalle_id IS NULL AND
                t.consignee_id = tracking.consignee_id
              ) AS trackings"),
              DB::raw("(
              	SELECT
              		DATE_FORMAT(max(t.created_at),'%Y-%m-%d')
              	FROM
              		tracking AS t
              	WHERE
              		t.deleted_at IS NULL
              		AND t.documento_detalle_id IS NULL
              		AND t.consignee_id = tracking.consignee_id
              ) AS last_date"),
              DB::raw("(
                SELECT
              	(
              	SELECT
              		Count( t.id )
              	FROM
              		tracking AS t
              	WHERE
              		t.deleted_at IS NULL
              		AND t.documento_detalle_id IS NULL
              		AND t.consignee_id = tracking.consignee_id
              	) - sum( t.confirmed_send )
              FROM
              	tracking AS t
              WHERE
              	t.deleted_at IS NULL
              	AND t.documento_detalle_id IS NULL
              	AND t.consignee_id = tracking.consignee_id
              ) AS confirmed_send")
          )
          ->where([
            ['tracking.deleted_at', NULL],
            ['b.deleted_at', NULL],
            ['tracking.documento_detalle_id', NULL]
          ])
          ->groupBy(
            'b.nombre_full',
            'tracking.consignee_id',
            'cantidad',
            'trackings',
            'tracking.agencia_id'
            )
          ->orderBy('last_date', 'DESC')
          ->where($where)
          ->get();
          // return DB::getQueryLog();
      return \DataTables::of($data)->make(true);
    }

    public function getTrackingByIdConsignee($consignee_id)
    {
      $data = Tracking::select(
              'tracking.id',
              'tracking.codigo',
              'tracking.contenido',
              'tracking.confirmed_send'
          )
          ->where([
            ['tracking.deleted_at', NULL],
            ['tracking.documento_detalle_id', NULL],
            ['tracking.consignee_id', $consignee_id]
          ])
          ->get();
        return \DataTables::of($data)->make(true);
    }

    public function updateTrackingReceipt(Request $request)
    {
        try {
            $data = Tracking::findOrFail($request->pk);
            if($request->name === 'codigo'){
              $data->codigo = $request->value;
            }else{
              if($request->name === 'consignee_id'){
                $data->consignee_id = $request->value;
              }else{
                $data->contenido = $request->value;
              }
            }

            if ($data->save()) {
                $this->AddToLog('Tracking contenido editado (' . $data->id . ')');
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

    public function trackingRecibido($id, $track)
    {
      try {
        $code = 200;
        $error = '';
        $config = $this->getConfig('ingreso_tracking');
        $status = Status::where('id', 9)->first();// 9 es alerta de tracking
        if($status){
          if($status->email === 1){
            if ($status->json_data != null) {
              $json_data = json_decode($status->json_data);
              if(isset($json_data->email_template_id)){
                Tracking::where('consignee_id', $id)->update(['confirmed_send' => 1]);
                $data = $this->verifySendEmail($config->value, $json_data->email_template_id, $id, $track);
                if ($data['code']) {
                  $code = 500;
                  $error = $data['error'];
                }
              }
            }else{
              $code = 500;
              $error = 'No hay plantilla de email para el estatus de Alerta Tracking';
            }
          }else{
            $code = 500;
            $error = 'No esta habilitada la opción de envio de email para el estatus de Alerta Tracking';
          }
        }
        return array(
          'code' => $code,
          'error' => $error,
        );
      } catch (Exception $e) {
        return $e;
      }

    }
}
