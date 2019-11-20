<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrqSignlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orq_signlogs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('member_id')->comment('用户id');
            // $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('签到时间');
            $table->timestamp('created_at')->useCurrent()->comment('签到时间');

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
        Schema::dropIfExists('orq_signlogs');
    }
}
