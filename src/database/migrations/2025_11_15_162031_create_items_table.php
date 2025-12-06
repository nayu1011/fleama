<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained('users');
            $table->string('name', 255);
            $table->string('brand_name', 255)->nullable();
            $table->text('description');
            $table->unsignedInteger('price');
            $table->unsignedTinyInteger('condition');
            $table->string('image_path', 255)->nullable();
            $table->unsignedInteger('like_count')->default(0);
            $table->unsignedTinyInteger('status')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('items');
    }
}
