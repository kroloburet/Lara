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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('alias', 250)->unique();
            $table->ulid('storage')->unique();
            $table->string('type', 100)->default('page');
            $table->foreignId('category_id')->nullable();
            $table->string('robots', 100)->default('all');
            $table->mediumText('css')->nullable();
            $table->mediumText('js')->nullable();
            $table->json('layout')->nullable();
            $table->json('statistic')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
