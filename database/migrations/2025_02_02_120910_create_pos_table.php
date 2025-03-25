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
        Schema::create('cash_registers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('description');
            $table->integer('initial_amount');
            $table->integer('total_income')->default(0);
            $table->integer('total_income_cash')->default(0);
            $table->integer('total_outgoing')->default(0);
            $table->integer('total_outgoing_cash')->default(0);
            $table->integer('total_bankable')->default(0);
            $table->dateTime('last_cashup')->nullable();
            $table->foreignId('library_id')->constrained('libraries')->onDelete('cascade');
            $table->fullText(['name', 'description']);
        });

        Schema::create('debit_types', function (Blueprint $table) {
            $table->id();
            $table->string('code',50)->unqiue();
            $table->integer('default_amount')->default(0);
            $table->string('description');
            $table->boolean('can_be_sold')->default(true);
            $table->foreignId('library_id')->nullable()->constrained('libraries')->onDelete('cascade');
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date')->index();
            $table->string('payment_type',50);
            $table->integer('amount_tendered');
            $table->enum('transaction_type',['increase','decrease'])->default('increase');
            $table->boolean('cashup')->default(false)->index();
            $table->foreignId('cash_register_id')->constrained('cash_registers')->onDelete('cascade');
            $table->foreignId('operator')->constrained('users')->onDelete('cascade');
        });

        Schema::create('transaction_details', function (Blueprint $table) {
            $table->id();
            $table->string('debit_type');
            $table->tinyInteger('quantity');
            $table->integer('price');
            $table->boolean('refunded')->default(false);
            $table->foreignId('transaction_id')->constrained('transactions')->onDelete('cascade');
        });

        Schema::create('cashup_histories', function (Blueprint $table) {
            $table->id();
            $table->dateTime('from');
            $table->dateTime('to');
            $table->integer('cash');
            $table->integer('cash_out');
            $table->integer('others');
            $table->integer('others_out');
            $table->foreignId('operator')->constrained('users')->onDelete('cascade');
            $table->foreignId('cash_register_id')->constrained('cash_registers')->onDelete('cascade');
        });

        Schema::create('cashup_sumaries', function (Blueprint $table) {
            $table->id();
            $table->string('debit_type');
            $table->integer('total');
            $table->foreignId('cashup_history_id')->constrained('cashup_histories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cashup_sumaries');
        Schema::dropIfExists('cashup_histories');
        Schema::dropIfExists('transaction_details');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('debit_types');
        Schema::dropIfExists('cash_registers');
    }
};
