<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('first_name', 80);
            $table->string('last_name', 80);
            $table->string('email', 150)->nullable();
            $table->string('phone', 30);

            $table->string('cnic', 20)->nullable()->index();
            $table->string('passport_number', 30)->nullable();
            $table->string('nationality', 60)->default('Pakistani');

            $table->text('address')->nullable();
            $table->string('city', 80)->nullable();
            $table->string('country', 80)->default('Pakistan');

            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->string('emergency_contact_name', 120)->nullable();
            $table->string('emergency_contact_phone', 30)->nullable();
            $table->text('notes')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['first_name', 'last_name']);
            $table->index('phone');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guests');
    }
};
