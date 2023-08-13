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
        Schema::disableForeignKeyConstraints();

        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->uuid('user_information_id');
            $table->boolean('is_active')->default(true); // should not log in if false
            $table->timestamp('email_verified_at')->nullable(); // should not log in if null
            $table->date('deleted_at')->nullable();
            $table
                ->foreign('user_information_id')
                ->references('id')
                ->on('user_information')
                ->cascadeOnDelete();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
