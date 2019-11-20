<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrqMembers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orq_members', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 50)->comment('姓名');
            $table->string('mobile', 11)->comment('电话');
            $table->string('company', 100)->comment('单位');
            $table->string('address', 200)->nullable()->comment('地址');

            $table->string('email', 30)->nullable()->comment('电子邮件');
            $table->string('wx_account', 30)->nullable()->comment('微信号');
            $table->unsignedBigInteger('bp_points')->default(0)->comment('积分');
            $table->string('club_ids', 10)->nullable()->comment('所属俱乐部id');
            $table->string('position', 15)->nullable()->comment('职位');

            $table->unsignedBigInteger('user_id')->comment('登录信息表(user)的id');
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
        Schema::dropIfExists('orq_members');
    }
}
