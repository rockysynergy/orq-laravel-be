<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrqAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orq_admins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('club_ids')->nullable()->comment('管理员所属组织id列表');

            $table->unsignedBigInteger('user_id')->nullable()->comment('登录信息表(user)的id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orq_admins', function (Blueprint $table) {
            Schema::dropIfExists('fx_admins');
        });
    }
}
