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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('isbn',13)->unique();
            $table->string('title');
            $table->string('cover_image');
            $table->string('authors');
            $table->integer('page_count');
            $table->string('publisher');
            $table->string('published_date',50);
            $table->string('language',50);
            $table->text('description');
            $table->timestamps();
            $table->fullText(['title', 'authors']);
        });

        Schema::create('book_items', function (Blueprint $table) {
            $table->id();
            $table->string('barcode',50)->unique();
            $table->date('date_acquired');
            $table->enum('status',['available', 'borrowed', 'lost', 'reserved'])->default('available');
            $table->integer('checkouts')->default(0);
            $table->integer('renewals')->default(0);
            $table->date('last_seen')->nullable();
            $table->date('date_accessioned')->nullable();
            $table->foreignId('book_id')->constrained('books')->onDelete('cascade');
            $table->foreignId('home_library')->constrained('libraries')->onDelete('cascade');
            $table->foreignId('current_location')->constrained('libraries')->onDelete('cascade');
            $table->foreignId('shelving_location')->nullable()->constrained('authorized_values')->onDelete('cascade');
        });

        Schema::create('book_collection', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained('books')->onDelete('cascade');
            $table->foreignId('collection_id')->constrained('authorized_values')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_collection');
        Schema::dropIfExists('book_items');
        Schema::dropIfExists('books');
    }
};
