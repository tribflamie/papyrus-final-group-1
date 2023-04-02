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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 150)->nullable(false);
            $table->string('email', 150)->nullable(false)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 150)->nullable(false);
            $table->rememberToken()->nullable();
            $table->string('gender', 45)->nullable();
            $table->date('yob')->nullable();
            $table->string('phone', 45)->nullable();
            $table->string('location', 150)->nullable();
            $table->text('bio')->nullable();
            $table->string('photo', 150)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
