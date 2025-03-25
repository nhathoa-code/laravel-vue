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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone',20)->nullable();
            $table->string('address')->nullable();
        });

        Schema::create('baskets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('notes')->nullable();
            $table->date('opened_on')->default(now()->toDateString());
            $table->date('closed_on')->nullable();
            $table->enum('status',['spending','closed'])->default('spending');
            $table->boolean('finish')->default(false);
            $table->json('managed_by')->nullable();
            $table->foreignId('billing_place')->constrained('libraries')->onDelete('cascade');
            $table->foreignId('vendor_id')->constrained('vendors')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
        });

        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('name');
            $table->unsignedInteger('total_amount')->default(0);
            $table->boolean('active')->default(false);
            $table->boolean('lock')->default(false);
        });

        Schema::create('funds', function (Blueprint $table) {
            $table->id();
            $table->string('code',50)->unique();
            $table->string('name');
            $table->unsignedInteger('amount');
            $table->unsignedBigInteger('warning_at')->nullable();
            $table->text('notes')->nullable();
            $table->enum('permission',[0,1,2,3])->default(0);
            $table->json('users')->nullable();
            $table->foreignId('owner')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('library_id')->nullable()->constrained('libraries')->onDelete('cascade');
            $table->foreignId('budget_id')->constrained('budgets')->onDelete('cascade');
        });

        Schema::create('basket_items', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('quantity');
            $table->tinyInteger('received')->default(0);
            $table->date('creation_date')->default(now()->toDateString());
            $table->date('receive_date')->nullable();
            $table->integer('vendor_price');
            $table->enum('status',['new','received'])->default('new');
            $table->string('internal_note')->nullable();
            $table->decimal('discount')->default(0);
            $table->foreignId('book_id')->constrained('books')->onDelete('cascade');
            $table->foreignId('fund_id')->constrained('funds')->onDelete('cascade');
            $table->foreignId('basket_id')->constrained('baskets')->onDelete('cascade');
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number',20)->unique();
            $table->date('shipping_date');
            $table->date('billing_date')->nullable();
            $table->enum('status',['open','closed'])->default('open');
            $table->foreignId('billing_place')->constrained('libraries')->onDelete('cascade');
            $table->foreignId('vendor_id')->constrained('vendors')->onDelete('cascade');
        });

        Schema::create('invoice_details', function (Blueprint $table) {
            $table->id();
            $table->integer('cost');
            $table->tinyInteger('quantity');
            $table->foreignId('fund_id')->constrained('funds')->onDelete('cascade');
            $table->foreignId('book_id')->constrained('books')->onDelete('cascade');
            $table->foreignId('basket_id')->constrained('baskets')->onDelete('cascade');
            $table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_details');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('basket_items');
        Schema::dropIfExists('funds');
        Schema::dropIfExists('budgets');
        Schema::dropIfExists('baskets');
        Schema::dropIfExists('vendors');
    }
};
