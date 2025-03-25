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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->date('checkout_date');
            $table->date('due_date');
            $table->date('return_date')->nullable();
            $table->enum('status',['active', 'completed', 'overdue'])->default('active');
            $table->foreignId('book_item_id')->constrained('book_items')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        });

        Schema::create('holds', function (Blueprint $table) {
            $table->id();
            $table->enum('status',['waiting'])->default('waiting');
            $table->timestamp('expires_on')->nullable()->useCurrent();
            $table->timestamp('placed_at')->useCurrent();
            $table->foreignId('book_id')->constrained('books')->onDelete('cascade');
            $table->foreignId('book_item_id')->nullable()->constrained('book_items')->onDelete('cascade');
            $table->foreignId('pickup_library')->constrained('libraries')->onDelete('cascade');
            $table->foreignId('patron')->constrained('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('holds');
        Schema::dropIfExists('loans');
    }
};
