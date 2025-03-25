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
        Schema::create('ill_requests', function (Blueprint $table) {
            $table->id();
            $table->string('status',50)->default('new request');
            $table->text('notes')->nullable();
            $table->foreignId('lending_library')->constrained('libraries')->onDelete('cascade');
            $table->foreignId('borrowing_library')->constrained('libraries')->onDelete('cascade');
            $table->foreignId('book_item')->nullable()->constrained('book_items')->onDelete('cascade');
            $table->foreignId('patron')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ill_requests');
    }
};
