<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinkedSocialAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('linked_social_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->comment('usersテーブルに登録されているユーザーID');
            $table->string('provider_name')->nullable()->comment('OAuth認証先のアプリ名');
            $table->string('provider_id')->unique()->nullable()->comment('OAuth認証先のID');
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
        Schema::dropIfExists('linked_social_accounts');
    }
}
