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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['Cash', 'Credit Card', 'Debit Card', 'Online'])->default('Cash');
            $table->enum('status', ['Pending', 'Completed', 'Refund', 'Failed', 'Overpaid'])->default('Pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
