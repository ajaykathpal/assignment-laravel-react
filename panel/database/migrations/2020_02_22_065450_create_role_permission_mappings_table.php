<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolePermissionMappingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_permission_mappings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('role_id')->unsigned()->nullable();
            $table->integer('permission_id')->unsigned()->nullable();
            $table->timestamps();
        });

        Schema::table('role_permission_mappings', function($table) {
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');;
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_permission_mappings');
    }
}
