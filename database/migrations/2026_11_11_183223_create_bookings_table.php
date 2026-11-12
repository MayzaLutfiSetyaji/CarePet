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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('pet_id')->constrained('pets')->onDelete('cascade');
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
            $table->date('start_date'); //rentang tanggal khusus untuk penitipan
            $table->date('end_date')->nullable(); //rentang tanggal khusus untuk penitipan
            $table->time('time_slot')->nullable(); //untuk grooming
            $table->enum(
                'status', [
                    'In Progress',
                    'approved',
                    'checked_in',
                    'complete',
                    'rejected',
                ]
            )->default('In Progress');
            $table->text('rejection_reason')->nullable(); //wajib diisi ketika admin menolak
            $table->text('animal_condition_reason')->nullable(); //note kondisi hewan saat ditangani
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
