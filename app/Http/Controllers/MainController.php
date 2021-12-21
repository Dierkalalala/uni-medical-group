<?php

namespace App\Http\Controllers;

use App\Box;
use App\Nursery;
use App\Type;
use Illuminate\Http\Request;

class MainController extends Controller
{
    private function array_sort($array, $on, $order=SORT_ASC)
    {
        $new_array = array();
        $sortable_array = array();

        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }

            switch ($order) {
                case SORT_ASC:
                    asort($sortable_array);
                    break;
                case SORT_DESC:
                    arsort($sortable_array);
                    break;
            }

            foreach ($sortable_array as $k => $v) {
                $new_array[$k] = $array[$k];
            }
        }

        return $new_array;
    }
    public function main() {
        $types = Type::get();
        $boxes = Box::get();
        return view('pages.main', compact('boxes', 'types'));
    }

    public function getClinics(Request $request) {
        $patientName = $request->name;
        $patientAddress = $request->address;
        $index = Box::find($request->index);
        $clinics = Nursery::where('type_id', $request->type_id)->get();
        $lat =($index->getCoordinates()[0]['lat']);
        $lng =($index->getCoordinates()[0]['lng']);
        foreach($clinics as $clinic) {
            $clinic->lat = ($clinic->getCoordinates()[0]['lat']);
            $clinic->lng = ($clinic->getCoordinates()[0]['lng']);
            $latDiff = abs($clinic->lat - $lat);
            $lngDiff = abs($clinic->lng - $lng);
            $clinic->closestIndex = $latDiff + $lngDiff;
        }
        $clinics = $this->array_sort($clinics, 'closestIndex', SORT_DESC);
        return view('pages.clinics', compact('patientAddress', 'patientName', 'clinics'));
    }

}
