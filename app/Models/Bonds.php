<?php

namespace App\Models;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bonds extends Model
{
    use HasFactory;
    protected $table='bonds';
    protected $appends=['payouts'];
    protected $hidden=[
        'created_at',
        'updated_at',
    ];

    public function getPayoutsAttribute(){
        $periodDay = 0;
        $periodMonth = 0;
        $dates = [];
        switch ($this->period_calculating_interest) {
            case 360:
                $periodDay = (12 / $this->frequency_payment_coupons) * 30;
                break;
            case 364:
                $periodDay = (364 / $this->frequency_payment_coupons);
                break;
            case 365:
                $periodMonth = (12 / $this->frequency_payment_coupons);
                break;
        }

        $date = Carbon::create($this->emission_date);
        while (strtotime($date) <= strtotime($this->last_turnover_date)) {
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
        return $dates;
    }

}
