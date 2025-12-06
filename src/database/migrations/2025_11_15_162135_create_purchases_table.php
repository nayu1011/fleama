<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buyer_id')->constrained('users');
            $table->foreignId('item_id')->constrained();
            $table->string('postal_code', 8);
            $table->string('address', 255);
            $table->string('building', 255)->nullable();
            $table->unsignedTinyInteger('payment_method');
            $table->unsignedInteger('total_price');
            $table->unsignedTinyInteger('status')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('purchases');
    }
}
