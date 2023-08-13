<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('user_information', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('first_name', 140);
            $table->string('last_name', 140);
            $table->string('mobile', 20);
            $table->string('gender', 20);
            $table->string('bio', 255)->nullable();
            $table->string('city', 140)->nullable();
            $table->string('expected_grade_for_qudrat', 140)->nullable();
            $table->string('expected_grade_for_tahsili', 140)->nullable();
            $table->string('expected_grade_for_step', 140)->nullable();
            $table->string('image', 255)->nullable();
            $table->boolean('is_available')->default(false); // if its online or not
            $table->date('date_of_birth')->nullable();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_information');
    }
};
