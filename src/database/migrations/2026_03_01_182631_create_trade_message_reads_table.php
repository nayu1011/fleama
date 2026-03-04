<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTradeMessageReadsTable extends Migration
{
    public function up()
    {
        Schema::create('trade_message_reads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trade_id')->constrained('trades')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users');
            $table->timestamp('last_read_at')->nullable();
            $table->timestamps();

            $table->unique(['trade_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('trade_message_reads');
    }
}
