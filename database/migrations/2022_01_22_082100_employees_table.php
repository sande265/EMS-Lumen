<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email');
            $table->string('designation')->nullable();
            $table->string('depatment')->nullable();
            $table->string('blood_group')->nullable();
            $table->string('gender');
            $table->string('role');
            $table->string('phone_no');
            $table->string('branch')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->boolean('status');
            $table->integer('supervisor')->nullable();
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
        Schema::dropIfExists('employees');
    }
}
