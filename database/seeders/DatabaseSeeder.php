<?php

namespace Database\Seeders;

use App\Models\Bonds;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $examples=[
            [
                'emission_date'=>'2021-11-08',
                'last_turnover_date'=>'2022-11-03',
                'nominal_price'=>100.00,
                'frequency_payment_coupons'=>'4',
                'period_calculating_interest'=>'360',
                'coupon_percent'=>10,
            ],
            [
                'emission_date'=>'2021-11-08',
                'last_turnover_date'=>'2022-11-07',
                'nominal_price'=>200.00,
                'frequency_payment_coupons'=>'4',
                'period_calculating_interest'=>'364',
                'coupon_percent'=>20,
            ],
            [
                'emission_date'=>'2021-11-08',
                'last_turnover_date'=>'2022-11-08',
                'nominal_price'=>300.00,
                'frequency_payment_coupons'=>'4',
                'period_calculating_interest'=>'365',
                'coupon_percent'=>30,

            ]
        ];
        foreach ($examples as $example) {
            Bonds::create([
                'emission_date' => $example['emission_date'],
                'last_turnover_date' => $example['last_turnover_date'],
                'nominal_price' => $example['nominal_price'],
                'frequency_payment_coupons' => $example['frequency_payment_coupons'],
                'period_calculating_interest' => $example['period_calculating_interest'],
                'coupon_percent' => $example['coupon_percent'],
            ]);

        }
    }
}
