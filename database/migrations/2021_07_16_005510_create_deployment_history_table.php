<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeploymentHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deployment_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('deployment_id');
            $table->foreign('deployment_id')->references('id')->on('deployment')->onUpdate('cascade')->onDelete('cascade');
            $table->boolean('was_succes')->default(true);
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
        Schema::dropIfExists('deployment_history');
    }
}
