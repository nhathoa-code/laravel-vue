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
        Schema::create('lists', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('sort_by',['title','author','date_added']);
            $table->enum('type',['private','public']);
            $table->enum('allow_changes_from',[0,1,2]);
            $table->foreignId('owner')->constrained('users')->onDelete('cascade');
        });

        Schema::create('list_items', function (Blueprint $table) {
            $table->id();
            $table->date('added_on');
            $table->foreignId('book_id')->constrained('books')->onDelete('cascade');
            $table->foreignId('list_id')->constrained('lists')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('list_items');
        Schema::dropIfExists('lists');
    }
};
