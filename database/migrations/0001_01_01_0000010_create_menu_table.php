<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu', function (Blueprint $table) {
            $table->id();
            $table->string('title', 50);
            $table->foreignId('parent_id')->nullable()->constrained('menu')->cascadeOnDelete();
            $table->string('url', 255)->nullable();
            $table->unsignedInteger('order')->default(1);
            $table->string('target', 20)->default('_self');
            $table->string('locale', 6);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu');
    }
};
