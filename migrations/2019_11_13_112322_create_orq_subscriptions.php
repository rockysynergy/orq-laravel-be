<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrqSubscriptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orq_subscriptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamp('start_time')->nullable()->comment('开始时间');
            $table->timestamp('end_time')->nullable()->comment('结束时间');
            $table->tinyInteger('pay_status')->default(0)->comment('支付状态0:未支付; 1:支付成功');
            $table->unsignedBigInteger('pay_amount')->default(0)->comment('支付金额');

            $table->tinyInteger('approve_status')->default(0)->comment('审核状态0:待审核, 1:通过, 2:聚集');
            $table->tinyInteger('is_first')->default(0)->comment('是首次申请？0:否；1：是');
            $table->unsignedBigInteger('member_id')->default(0)->comment('会员id');
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
        Schema::dropIfExists('orq_subscriptions');
    }
}
