<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_reference', 30)->unique();

            $table->foreignId('guest_id')->constrained('guests');
            $table->foreignId('room_id')->constrained('rooms');

            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->dateTime('actual_check_in_at')->nullable();
            $table->dateTime('actual_check_out_at')->nullable();

            $table->integer('num_nights');
            $table->integer('num_guests')->default(1);

            $table->decimal('rate_per_night', 10, 2);
            $table->decimal('subtotal', 12, 2);
            $table->decimal('tax_rate', 5, 2);
            $table->decimal('tax_amount', 10, 2);
            $table->decimal('total_amount', 12, 2);

            $table->enum('status', [
                'confirmed', 'checked_in', 'checked_out',
                'cancelled', 'no_show',
            ])->default('confirmed');

            $table->enum('payment_status', ['unpaid', 'partial', 'paid'])->default('unpaid');

            $table->boolean('is_walk_in')->default(false);

            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();

            $table->timestamps();

            $table->index(['status', 'check_in_date']);
            $table->index('room_id');
            $table->index('guest_id');
            $table->index('booking_reference');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
