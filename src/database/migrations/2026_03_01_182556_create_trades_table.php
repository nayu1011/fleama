<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTradesTable extends Migration
{
    public function up()
    {
        Schema::create('trades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('items')->cascadeOnDelete();
            $table->foreignId('buyer_id')->constrained('users');
            $table->foreignId('seller_id')->constrained('users');
            $table->unsignedTinyInteger('status')->default(0); // 0:取引中, 1:購入者取引完了, 2:両者取引完了
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->unique('item_id');
            $table->index(['buyer_id', 'status']);
            $table->index(['seller_id', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('trades');
    }
}
