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
        Schema::create('contact', function (Blueprint $table) {
            $table->id();
            $table->string('type', 100)->default('contact');
            $table->ulid('storage')->unique();
            $table->string('robots', 100)->default('all');
            $table->mediumText('css')->nullable();
            $table->mediumText('js')->nullable();
            $table->json('layout')->nullable();
            $table->json('location')->nullable();
            $table->json('links')->nullable();
            $table->json('emails')->nullable();
            $table->json('phones')->nullable();
            $table->json('social_networks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact');
    }
};
