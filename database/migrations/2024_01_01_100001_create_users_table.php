<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('fullname', 100);
            $table->string('email', 100)->unique();
            $table->string('phone', 20)->nullable();
            $table->string('password');
            $table->smallInteger('active')->default(1);
            $table->timestamp('created_at')->useCurrent();
            $table->string('imguser')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
