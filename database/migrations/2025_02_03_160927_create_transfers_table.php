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
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->date('date_of_transfer');
            $table->date('received_on')->nullable();
            $table->foreignId('book_item_id')->constrained('book_items')->onDelete('cascade');
            $table->foreignId('on_hold_for')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('from')->constrained('libraries')->onDelete('cascade');
            $table->foreignId('to')->constrained('libraries')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
};
