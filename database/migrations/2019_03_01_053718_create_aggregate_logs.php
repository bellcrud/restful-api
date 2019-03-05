<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAggregateLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aggregate_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->double('ave_execution_time',10,5)->comment('平均処理時間');
            $table->unsignedInteger('total_access_count')->comment('総アクセス数');
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
        Schema::dropIfExists('aggregate_logs');
    }
}
