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
        Schema::create('libraries', function (Blueprint $table) {
            $table->id();
            $table->string('name',100);
            $table->string('address');
            $table->string('contact',100);
        });

        Schema::create('authorized_categories', function (Blueprint $table) {
            $table->id();
            $table->string('code',50)->unique();
        });

        Schema::create('authorized_values', function (Blueprint $table) {
            $table->id();
            $table->string('value',100);
            $table->string('description')->nullable();
            $table->foreignId('category_id')->constrained('authorized_categories')->onDelete('cascade');
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('parent_id')->nullable()->constrained('categories')->onDelete('cascade');
            $table->unsignedTinyInteger('order')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
        Schema::dropIfExists('authorized_values');
        Schema::dropIfExists('authorized_categories');
        Schema::dropIfExists('libraries');
    }
};
