<?php
namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\AfterSheet;

class InternalManifest implements FromView, WithEvents, WithDrawings
{
  public $view;
  public $data;

  public function __construct($view, $data = null)
  {
      $this->data = $data['datos'];
      $this->count_groups = $data['count_groups'];
      $this->view = $view;
  }

  public function view(): View
  {
      return view($this->view, [
          'data' => $this->data
      ]);
  }

  public function drawings()
    {
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo');
        $drawing->setPath(public_path('img/logo.png'));//para el proyecto local.. hay que pasar la ruta con asset('img/logo.png')
        // $drawing->setPath(asset('img/logo.png'));//para el proyecto local.. hay que pasar la ruta con asset('img/logo.png')
        $drawing->setHeight(50);
        $drawing->setWidth(140);
        $drawing->setCoordinates('A1');
        $drawing->setOffsetX(10);
        $drawing->setOffsetY(15);

        return $drawing;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) { 
                $cellRange = 'A1:K150'; // All headers
                /* TAMAÑO GENERAL DE LA HOJA */
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(10);
                $event->sheet->getDelegate()->getStyle('E1:F1')->getFont()->setSize(20);
                /* TEXTO AJUSTADO A LA CELDA */
                $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setWrapText(true);
                /* ANCHO DE COLUMNA PERSONALIZADO */
                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(25);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(35);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(15);

                /* CENTRAR DATOS DE LA COLUMNA E */
                $event->sheet->styleCells(
                    'E1:F2',
                    [
                        'alignment' => array(
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        )
                    ]
                );

                /* CENTRAR CABECERA DE LA TABLA */
                $event->sheet->styleCells(
                    'A6:G6',
                    [
                        'alignment' => array(
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        )
                    ]
                );

                /* UNIR CELDAS */
                $event->sheet->getDelegate()->mergeCells('E1:F1');
                $event->sheet->getDelegate()->mergeCells('E2:F2');

                /* ENCABEZADO DE LA TABLA */
                $event->sheet->styleCells(
                    'A6:G'.(count($this->data) + 6 + $this->count_groups),
                    [
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '000000'],
                            ],
                        ]
                    ]
                );

                $styleArray = [
                    
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ], 
                    /* PROPIEDADES DEL TIPO DE LETRA Y COLOR DE LETRA */                       
                    'font' => [
                        'name' => 'Century Gothic',
                        'size' => 12,
                        'bold' => true,
                        // 'color' => ['argb' => 'EB2B02'],
                    ],
                    /* COLOR DE FONDO DE LA CELDA */ 
                    // 'fill' => [
                    //     'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    //     'startColor' => [
                    //         'argb' => 'FFA0A0A0',
                    //     ],
                    // ]
                ];

                $event->sheet->getDelegate()->getStyle('E1:F1')->applyFromArray($styleArray);
                $event->sheet->getDelegate()->getStyle('G1')->applyFromArray(
                    ['fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => 'FFCE88',
                        ],
                    ]]
                );
                $event->sheet->getDelegate()->getStyle('G3')->applyFromArray(
                    ['fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => 'EEBCFD',
                        ],
                    ]]
                );
            }
        ];
    }
}
