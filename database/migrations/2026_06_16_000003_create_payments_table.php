<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_reference', 30)->unique();

            $table->foreignId('booking_id')->constrained('bookings');
            $table->foreignId('guest_id')->constrained('guests');

            $table->decimal('amount', 12, 2);
            $table->enum('method', ['cash', 'card', 'bank_transfer', 'mobile_wallet'])->default('cash');
            $table->enum('type', ['payment', 'refund'])->default('payment');

            $table->date('payment_date');
            $table->string('transaction_id', 100)->nullable();
            $table->text('notes')->nullable();

            $table->foreignId('received_by')->constrained('users');
            $table->dateTime('voided_at')->nullable();
            $table->text('void_reason')->nullable();

            $table->timestamps();

            $table->index(['booking_id', 'payment_date']);
            $table->index('payment_reference');
            $table->index('payment_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
