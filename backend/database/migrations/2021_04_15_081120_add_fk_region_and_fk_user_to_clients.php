<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFkRegionAndFkUserToClients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->unsignedInteger('fk_user');
            $table->foreign('fk_user')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedInteger('fk_region');
            $table->foreign('fk_region')->references('id')->on('regions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropForeign('fk_user');
            $table->dropColumn('fk_user');

            $table->dropForeign('fk_region');
            $table->dropColumn('fk_region');
        });
    }
}
