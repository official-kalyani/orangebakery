<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('order_price')->nullable();
            $table->string('coupon_id')->nullable();
            $table->string('order_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('user_address_id')->nullable();
            $table->string('assignto')->nullable();
            $table->enum('status', ['order_received','order_preparing','ontheway','delivered','cancelled'])->nullable();
            $table->enum('payment_type',['cod','online'])->nullable();
            $table->string('delivered_date')->nullable();
            $table->string('txnid')->nullable();
            $table->string('razorpay_signature')->nullable();
            $table->enum('amount_paid',['0','1'])->default('0');
            $table->string('deliveryboy_id')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
