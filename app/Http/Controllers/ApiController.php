<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function listDami(Request $request)
    {
        $valueLayout = $request->input('value');
        if ($valueLayout == '1'){
            $data = (object)[
                [
                    'value' => '16',
                    'label' => '16',
                ],
                [
                    'value' => '24',
                    'label' => '24',
                ],
                [
                    'value' => '32',
                    'label' => '32',
                ],
            ];
        }
        elseif ($valueLayout == '2') {
            $data = (object)[
                [
                    'value' => '12',
                    'label' => '12',
                ],
                [
                    'value' => '16',
                    'label' => '16',
                ],
                [
                    'value' => '32',
                    'label' => '32',
                ],
            ];
        }
        echo '<option label="Pilih"></option>';
        foreach ($data as $key => $value) {
            echo '<option value="'.$value['value'].'">'.$value['label'].'</option>';
        }
    }
    public function getPosisiLayout()
    {
        $posisiLayout = (object)[
            [
                'value' => '1',
                'label' => 'Potrait',
            ],
            [
                'value' => '2',
                'label' => 'Landscape',
            ]
        ];
        return response()->json($posisiLayout);
    }
    public function listFormatBuku(Request $request)
    {
        $posisi = $request->get('posisi');
        $dami = $request->get('dami');
        if ($posisi == '1' && $dami == '16') {
            $data = (object)[
                [
                    'value' => '20 x 18',
                    'label' => '20 x 18',
                ],
                [
                    'value' => '21 x 29,7',
                    'label' => '21 x 29,7',
                ],
                [
                    'value' => '16 x 25',
                    'label' => '16 x 25',
                ],
                [
                    'value' => '17,5 x 25',
                    'label' => '17,5 x 25',
                ],
                [
                    'value' => '19 x 25',
                    'label' => '19 x 25',
                ],
                [
                    'value' => '17,5 x 24,5',
                    'label' => '17,5 x 24,5',
                ],
            ];
        } elseif ($posisi == '1' && $dami == '24') {
            $data = (object)[
                [
                    'value' => '19 x 24',
                    'label' => '19 x 24',
                ],
                [
                    'value' => '19 x 23',
                    'label' => '19 x 23',
                ],
                [
                    'value' => '21 x 21',
                    'label' => '21 x 21',
                ],
                [
                    'value' => '19 x 19',
                    'label' => '19 x 19',
                ],
            ];
        } elseif ($posisi == '1' && $dami == '32') {
            $data = (object)[
                [
                    'value' => '10 x 15',
                    'label' => '10 x 15',
                ],
                [
                    'value' => '13 x 19',
                    'label' => '13 x 19',
                ],
                [
                    'value' => '12 x 19',
                    'label' => '12 x 19',
                ],
                [
                    'value' => '11 x 18',
                    'label' => '11 x 18',
                ],
                [
                    'value' => '14 x 21',
                    'label' => '14 x 21',
                ],
                [
                    'value' => '17,5 x 24,5',
                    'label' => '17,5 x 24,5',
                ],
                [
                    'value' => '16 x 23',
                    'label' => '16 x 23',
                ],
                [
                    'value' => '15 x 19',
                    'label' => '15 x 19',
                ],
                [
                    'value' => '15 x 15',
                    'label' => '15 x 15',
                ],
                [
                    'value' => '14 x 19',
                    'label' => '14 x 19',
                ],
                [
                    'value' => '16,5 x 21',
                    'label' => '16,5 x 21',
                ],
                [
                    'value' => '15 x 21',
                    'label' => '15 x 21',
                ],
                [
                    'value' => '14,5 x 21',
                    'label' => '14,5 x 21',
                ],
                [
                    'value' => '15 x 23',
                    'label' => '15 x 23',
                ],
            ];
        } elseif ($posisi == '2' && $dami == '12') {
            $data = (object)[
                [
                    'value' => '30 x 14',
                    'label' => '30 x 14',
                ]
            ];
        } elseif ($posisi == '2' && $dami == '16') {
            $data = (object)[
                [
                    'value' => '18 x 14,5',
                    'label' => '18 x 14,5',
                ],
                [
                    'value' => '25 x 20',
                    'label' => '25 x 20',
                ],
                [
                    'value' => '29,5 x 20,5',
                    'label' => '29,5 x 20,5',
                ],
                [
                    'value' => '21 x 15',
                    'label' => '21 x 15',
                ],
            ];
        } elseif ($posisi == '2' && $dami == '32') {
            $data = (object)[
                [
                    'value' => '22 x 15,5',
                    'label' => '22 x 15,5',
                ]
            ];
        }

        echo '<option label="Pilih"></option>';
        foreach ($data as $key => $value) {
            echo '<option value="'.$value['value'].'">'.$value['label'].'</option>';
        }
    }
}
