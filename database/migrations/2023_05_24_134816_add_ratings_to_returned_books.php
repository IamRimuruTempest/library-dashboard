<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRatingsToReturnedBooks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('returned_books', function (Blueprint $table) {
             $table->integer('rated')->default(0)->after('time');
             $table->integer('total')->default(0)->after('rated');
             $table->integer('reviewed')->default(0)->after('total');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('returned_books', function (Blueprint $table) {
             $table->dropColumn('rated');
             $table->dropColumn('reviewed');
             $table->dropColumn('total');
        });
    }
}
