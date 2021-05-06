<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoadmapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roadmap', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fk_client')->unsigned();
            $table->foreign('fk_client')->references('id')->on('clients');
            $table->integer('fk_promo_material')->unsigned();
            $table->foreign('fk_promo_material')->references('id')->on('promo_material');
            $table->integer('fk_user')->unsigned();
            $table->foreign('fk_user')->references('id')->on('users');
            $table->date('applies_to_date');
            $table->string('description');
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
        Schema::dropIfExists('roadmap');
    }
}
