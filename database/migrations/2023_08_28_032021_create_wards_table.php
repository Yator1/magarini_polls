<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('county_id');
            $table->unsignedBigInteger('subcounty_id');
            $table->string('name');
            $table->string('alias')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();


            // Define foreign key constraints
            // $table->foreign('county_id')->references('id')->on('counties')->onDelete('cascade');
            // $table->foreign('subcounty_id')->references('id')->on('sub_counties')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wards');
    }
}
