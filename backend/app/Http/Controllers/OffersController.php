<?php

namespace App\Http\Controllers;

use App\Offers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OffersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function add(Request $request)
    {
        //Validate input
        $this->validate($request, [
            'board' => 'bail|required|max:22|min:22',
            'boxesremaining' => 'bail|required|min:1|max:2',
            'boxesopened' => 'bail|required|min:1|max:2',
            'average' => 'bail|required',
            'offer' => 'bail|required',
            'percentofaverage' => 'bail|required',
            'gameoverview' => 'bail|required',
        ]);

        //Calculate round
        $roundnum = 1;
        if ((int)$request->input('boxesopened') >= 8) {
            $roundnum = 2;
        }
        if ((int)$request->input('boxesopened') >= 11) {
            $roundnum = 3;
        }
        if ((int)$request->input('boxesopened') >= 14) {
            $roundnum = 4;
        }
        if ((int)$request->input('boxesopened') >= 17) {
            $roundnum = 5;
        }
        if ((int)$request->input('boxesopened') >= 20) {
            $roundnum = 6;
        }

        //Store offer
        $record = new Offers();
        $record->board = $request->input('board');
        $record->boxesremaining = $request->input('boxesremaining');
        $record->boxesopened = $request->input('boxesopened');
        $record->average = $request->input('average');
        $record->offer = $request->input('offer');
        $record->percentofaverage = $request->input('percentofaverage');
        $record->gameoverview = $request->input('gameoverview');
        $record->roundnum = "".$roundnum;
        $record->save();

        //Calculate colors remaining
        $bc = 0;
        $rc = 0;
        $c = 0;
        $boardstate = str_split($request->input('board'));
        foreach ($boardstate as $box) {
            $c++;
            if ((int)$box > 0) {
                if ((int)$c < 12) {
                    $bc++;
                } else {
                    $rc++;
                }
            }
        }
        $weight = "l";
        if ((int)$rc >= (int)$bc) {
            $weight = "h";
        }

        //Static offer ranges
        $staticoffers = array(
            "1" => array("l" => array("min" => 15, "max" => 25), "h" => array("min" => 20, "max" => 35)),
            "2" => array("l" => array("min" => 18, "max" => 30), "h" => array("min" => 20, "max" => 35)),
            "3" => array("l" => array("min" => 20, "max" => 35), "h" => array("min" => 25, "max" => 40)),
            "4" => array("l" => array("min" => 25, "max" => 40), "h" => array("min" => 30, "max" => 50)),
            "5" => array("l" => array("min" => 30, "max" => 50), "h" => array("min" => 40, "max" => 75)),
            "6" => array("l" => array("min" => 50, "max" => 80), "h" => array("min" => 60, "max" => 110))
        );

        //Make static offer
        $staticofferrange = $staticoffers["".$roundnum][$weight];
        // $randomperc = rand($staticofferrange['min'], $staticofferrange['max']);
        $lowoffer = ((int)$request->input('average') / 100) * $staticofferrange['min'];
        $highoffer = ((int)$request->input('average') / 100) * $staticofferrange['max'];

        //Response
        return response()->json(array("status"=>true, "low_offer"=>round($lowoffer), "high_offer"=>round($highoffer), "raw_low_offer" => $lowoffer, "raw_high_offer" => $highoffer, "offer_range"=>$staticofferrange));
    }
}
