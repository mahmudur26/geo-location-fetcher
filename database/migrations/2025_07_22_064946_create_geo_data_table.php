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
        Schema::create('geo_data', function (Blueprint $table) {
            $table->id();
            $table->string('ip')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('continent')->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('state_district')->nullable();
            $table->string('town')->nullable();
            $table->text('full_formatted_address')->nullable();
            $table->integer('accuracy_in_meter')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('geo_data');
    }
};
