<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mango_productions', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('first_name');
            $table->string('last_name');
            $table->unsignedBigInteger('phone_no');
            $table->string('kgs');
            $table->integer('sub_county_id');
            $table->integer('ward_id');
            $table->integer('id_no');
            $table->string('lmfcs_no')->nullable();
            $table->string('vehicle_no')->nullable();
            $table->string('payment_mode');
            $table->string('bank_name')->nullable();
            $table->string('bank_branch')->nullable();
            $table->date('weighing_date');
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mango_productions');
    }
};
