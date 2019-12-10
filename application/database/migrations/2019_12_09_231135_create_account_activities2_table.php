<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountActivities2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_activities', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('account_id');
            $table->dateTime('trade_date')->nullable();
            $table->dateTime('transaction_date')->nullable();
            $table->dateTime('settlement_date')->nullable();
            $table->string('action', 255)->nullable();
            $table->string('symbol', 255);
            $table->string('symbol_id', 2000);
            $table->text('description')->nullable();
            $table->string('currency', 100);
            $table->decimal('quantity', 10, 2);
            $table->decimal('price', 10, 4);
            $table->decimal('gross_amount', 10, 4);
            $table->decimal('commission', 10, 4);
            $table->decimal('net_amount', 10, 4);
            $table->string('type', 255);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('account_id')->references('id')->on('accounts')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_activities');
    }
}
