<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // $table->string('employee_id')->after('id');
            // $table->string('gender')->after('name');
            // $table->string('age')->after('gender');
            // $table->string('phone_number')->after('age');
            // $table->string('role')->after('phone_number');
            $table->string('image')->after('role')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // $table->string('employee_id');
            // $table->string('gender');
            // $table->string('age');
            // $table->string('phone_number');
            // $table->string('email');
            // $table->string('password');
            // $table->string('role');
            $table->string('image');
        });    
    }

    
}