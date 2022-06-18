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
        return response()->json([
            'success'=>true,
            'code'=>200,
            'dates'=>$bond->payouts
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
        if (!$bond){
            return response()->json([
                'success' => false,
                'code' => 404,
                'title' => 'Not found'
            ]);

        }

        if ($order_date && $order_date<$bond->emission_date){
            return response()->json([
                'success' => false,
                'code' => 400,
                'title' => 'Sifariş tarixi emissiya tarixindən kiçik ola bilməz.'
            ]);

        }
        if ($order_date && $order_date>$bond->last_turnover_date){
            return response()->json([
                'success' => false,
                'code' => 400,
                'title' => 'Sifariş tarixi son tədavül tarixindən böyük ola bilməz.'
            ]);
        }
        $order=new Orders();
        $order->order_date=$order_date;
        $order->number_bonds_received=$bond_count;
        $order->bond_id=$id;
        $order->save();
        return response()->json([
           'success'=>true,
           'code'=>200,
           'order'=>$order
        ]);
    }

    public function bondOrder(Request $request){
        if (!(int)$request->order_id){
            return response()->json([
                'success' => false,
                'code' => 400,
                'title' => 'Bad request'
            ]);
        }
        $order_id=(int)$request->order_id;
        $order=Orders::find($order_id);
        if (!$order){
            return response()->json([
                'success' => false,
                'code' => 404,
                'title' => 'Not found'
            ]);
        }
        $payouts=$order->bond->payouts;

        foreach ($payouts as $key =>$payout) {
            if($key==0){
                $past_day=strtotime($order->order_date)-strtotime($payout['date']);
                $past_day=(int)date('d',$past_day);
            }else{
                $past_day=strtotime($payout['date'])-strtotime($payouts[$key-1]['date']);
                $past_day=(int)date('d',$past_day);
            }
            $totalPercent[$key]['date']=$payout['date'];
            $totalPercent[$key]['amount']=(double)(($order->bond->nominal_price*$order->bond->coupon_percent)/100)/$order->bond->frequency_payment_coupons*$past_day*$order->number_bonds_received;
        }
        return [
            'success'=>true,
            'code'=>200,
            'dates'=>$totalPercent
        ];
    }
}
