<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('room_number', 20)->unique();
            $table->foreignId('room_type_id')->constrained('room_types');
            $table->integer('floor')->default(1);
            $table->decimal('price_per_night', 10, 2);
            $table->enum('status', ['available', 'occupied', 'reserved', 'maintenance'])->default('available');
            $table->text('notes')->nullable();
            $table->integer('position_x')->default(0);
            $table->integer('position_y')->default(0);
            $table->integer('width')->default(2);
            $table->integer('height')->default(2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['floor', 'status']);
            $table->index('room_type_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
