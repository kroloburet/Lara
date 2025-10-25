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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('type', '24')->default('moderator'); // "moderator" because admin was ones seed.
            $table->json('permissions')->nullable();
            $table->json('settings')->nullable();
            $table->json('log')->nullable(); // Contains the history of model events
            $table->string('verify_email_token')->unique()->nullable();
            $table->rememberToken();
            $table->string('password');
            $table->timestamp('last_activity_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
