<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTradeMessagesTable extends Migration
{
    public function up()
    {
        Schema::create('trade_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trade_id')->constrained('trades')->cascadeOnDelete();
            $table->foreignId('sender_id')->constrained('users');
            $table->text('message')->nullable();
            $table->string('image_path', 255)->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['trade_id', 'sender_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('trade_messages');
    }
}
