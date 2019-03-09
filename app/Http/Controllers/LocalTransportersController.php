<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TransportadorasLocales;
use App\Pais;
class LocalTransportersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('templates.localTransporters');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      try {
          $data              = (new TransportadorasLocales)->fill($request->all());
          $data->created_at  = date('Y-m-d H:i:s');
          if ($data->save()) {
              $this->AddToLog('Transportadora local creada id (' . $data->id . ')');
              $answer = array(
                  "datos"  => $request->all(),
                  "code"   => 200,
                  "status" => 200,
              );
          } else {
              $answer = array(
                  "error"  => 'Error al intentar crear el registro.',
                  "code"   => 600,
                  "status" => 500,
              );
          }
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getAll()
    {
        $sql = TransportadorasLocales::join('pais AS b', 'transportadoras_locales.pais_id', 'b.id')
        ->select(
        	'transportadoras_locales.id',
    			'transportadoras_locales.pais_id',
    			'transportadoras_locales.nombre',
    			'transportadoras_locales.url_rastreo',
    			'b.descripcion AS pais'
        )
        ->get();

        return \DataTables::of($sql)->make(true);
    }
    public function getAllPais()
    {
        return Pais::all();
    }
}
