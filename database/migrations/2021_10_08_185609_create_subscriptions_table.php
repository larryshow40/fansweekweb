<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('subscription_code');
            $table->string('customer_email');
            $table->integer('user_id');
            $table->string('customer_code');
            $table->integer('amount');
            $table->string('subscription_status');
            $table->boolean('status');
            $table->dateTime('next_payment_date');
            $table->string('plan_code');
            $table->longText('authorization');
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
        Schema::dropIfExists('subscriptions');
    }
}
