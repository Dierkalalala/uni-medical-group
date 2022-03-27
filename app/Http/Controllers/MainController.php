<?php

namespace App\Http\Controllers;

use App\Box;
use App\Nursery;
use App\Type;
use Illuminate\Http\Request;

class MainController extends Controller
{
    private function array_sort($array, $on, $order = SORT_ASC)
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

    public function main()
    {
        $clinics = Nursery::get();
        return view('pages.main', compact('clinics'));
    }

    public function getClinics(Request $req)
    {
        $clinics = Nursery::all();
        $lat = $req->lat;
        $lng = $req->lng;
        $myCoordinates = $lat . ',' . $lng;
        foreach ($clinics as $clinic) {
            $clinic->lat = ($clinic->getCoordinates()[0]['lat']);
            $clinic->lng = ($clinic->getCoordinates()[0]['lng']);
            $clinic->coordinates = trim($clinic->getCoordinates()[0]['lat'] . ',' . $clinic->getCoordinates()[0]['lng']);
            $latDiff = abs($clinic->lat - $lat);
            $lngDiff = abs($clinic->lng - $lng);
            $clinic->closestIndex = $latDiff + $lngDiff;
        }
        $clinics = $this->array_sort($clinics, 'closestIndex', SORT_DESC);
        return $clinics;
    }

}
