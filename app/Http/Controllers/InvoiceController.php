<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Invoice;
use App\InvoiceDetail;

class InvoiceController extends Controller
{
    public function index()
    {
      return view('templates.invoice.index');
    }

    public function getAll()
    {
        $sql = Invoice::join('shipper AS b', 'b.id', 'invoice.shipper_id')
        ->join('consignee AS c', 'c.id', 'invoice.consignee_id')
        ->select(
        	'invoice.id',
          'invoice.consecutive',
          'invoice.date_document',
          'b.nombre_full AS shipper',
          'c.nombre_full AS consignee',
          'invoice.observation'
          )->where('invoice.deleted_at', NULL)->get();

        return \DataTables::of($sql)->make(true);
    }

    public function pdfLabels($invoice_id)
    {
      $invoice = Invoice::join('shipper AS b', 'b.id', 'invoice.shipper_id')
        ->join('consignee AS c', 'c.id', 'invoice.consignee_id')
        ->join('agencia AS d', 'd.id', 'invoice.agency_id')
        ->select(
          'invoice.id',
          'invoice.consecutive',
          'invoice.date_document',
          'b.nombre_full AS shipper',
          'c.nombre_full AS consignee',
          'invoice.observation',
          'd.descripcion as agencia',
          'd.telefono as agencia_tel',
          'd.direccion as agencia_dir',
          'd.zip as agencia_zip',
          'd.email as agencia_email',
          'd.logo as agencia_logo'
          )->where([
              ['invoice.id', $invoice_id],
              ['invoice.deleted_at', NULL]
          ])->first();

      $detalle = InvoiceDetail::join('documento AS b', 'b.id', 'invoice_detail.document_id')
        ->join('documento_detalle AS c', 'c.documento_id', 'b.id')
        ->select(
          'b.piezas',
          'invoice_detail.id',
          'c.dimensiones',
          'c.largo',
          'c.ancho',
          'c.alto',
          'c.contenido',
          'c.volumen',
          'c.valor',
          'c.peso',
          'c.num_warehouse',
          'c.num_guia',
          'c.liquidado',
          'c.consolidado'
          )->where([
              ['invoice_detail.deleted_at', NULL],
              ['invoice_detail.invoice_id', $invoice_id]
          ])->get();
      return view('pdf/invoice/labels', compact('invoice', 'detalle'));
    }

}
