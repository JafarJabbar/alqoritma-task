<?php

namespace App\Http\Controllers;

use App\Models\Bonds;
use App\Models\Orders;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Http\Request;

class PayoutsController extends Controller
{
    public function payouts(Request $request)
    {
        if (!$request->id) {
            return response()->json([
                'success' => false,
                'code' => 400,
                'title' => 'Bad request'
            ]);
        }
        $id = $request->id;
        $bond = Bonds::find($id);
        $periodDay = 0;
        $periodMonth = 0;
        $dates = [];
        switch ($bond->period_calculating_interest) {
            case 360:
                $periodDay = (12 / $bond->frequency_payment_coupons) * 30;
                break;
            case 364:
                $periodDay = (364 / $bond->frequency_payment_coupons);
                break;
            case 365:
                $periodMonth = (12 / $bond->frequency_payment_coupons);
                break;
        }

        $date = Carbon::create($bond->emission_date);
        while (strtotime($date) <= strtotime($bond->last_turnover_date)) {
            if ($periodDay) {
                $date = $date->addDays($periodDay);
            } elseif ($periodMonth) {
                $date = $date->addMonths($periodMonth);
            }
            if ($date->dayOfWeek == CarbonInterface::SUNDAY) {
                $date->addDay();
            } elseif ($date->dayOfWeek == CarbonInterface::SATURDAY) {
                $date->addDays(2);
            }
            $dates[]['date'] = date('Y-m-d', strtotime($date));
        }
        return response()->json([
            'success'=>true,
            'code'=>200,
            'dates'=>$dates
        ]);
    }

    public function orderPost(Request $request){
        if (!$request->id) {
            return response()->json([
                'success' => false,
                'code' => 400,
                'title' => 'Bad request'
            ]);
        }
        $id = $request->id;
        $order_date=$request->order_date;
        $bond_count=$request->bond_count;
        $bond = Bonds::find($id);
        if ($order_date && $order_date<$bond->emission_date){
            return response()->json([
                'success' => false,
                'code' => 400,
                'title' => 'Sifariş tarixi emissiya tarixindən kiçik ola bilməz.'
            ]);

        }
        if ($order_date && $order_date<$bond->emission_date){
            return response()->json([
                'success' => false,
                'code' => 400,
                'title' => 'Sifariş tarixi son tədavül tarixindən böyük ola bilməz.'
            ]);
        }
        $order=new Orders();
        $order->order_date=$order_date;
        $order->number_bonds_received=$bond_count;
        $order->save();

    }
}
