<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tokens', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable(false)->unsigned()->index()->comment('ユーザーID');
            $table->string('token',60)->nullable(false)->index()->comment('トークン');
            $table->timestamps();
			$table->foreign('user_id')->references('id')->on('users')->comment('外部キー制約をつける参照先はusersテーブルidカラム');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tokens');
    }
}
