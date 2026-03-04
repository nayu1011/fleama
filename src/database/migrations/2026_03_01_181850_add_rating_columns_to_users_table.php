<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRatingColumnsToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // ユーザーの評価平均値と評価数を保存するカラムを追加
            $table->decimal('rating_average', 3, 2)->nullable()->after('image_path');
            $table->unsignedInteger('rating_count')->default(0)->after('rating_average');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['rating_average', 'rating_count']);
        });
    }
}
