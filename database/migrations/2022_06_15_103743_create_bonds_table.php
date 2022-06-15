<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bonds', function (Blueprint $table) {
            $table->id();
            $table->date('emission_date');
            $table->date('last_turnover_date');
            $table->decimal('nominal_price', $precision = 8, $scale = 2);
            $table->enum('frequency_payment_coupons', [1,2,4,12]);
            $table->enum('period_calculating_interest', [360,364,365]);
            $table->integer('coupon_percent');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bonds');
    }
};
