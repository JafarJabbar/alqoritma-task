<?php

namespace App\Http\Controllers;

use App\Models\Bonds;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Http\Request;
use function PHPUnit\Framework\returnArgument;

class PayoutsController extends Controller
{
    public function isWeekend($date)
    {
        return (date('N', strtotime($date)) >= 6);
    }

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
}
