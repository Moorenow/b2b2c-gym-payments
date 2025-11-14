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
        Schema::create('members', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('plan_id')->constrained()->cascadeOnUpdate();
            $table->string('name');
            $table->string('last_name');
            $table->string('identification_number')->unique();
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('emergency_contact_name');
            $table->string('emergency_contact_phone');
            $table->date('membership_start_date');
            $table->date('current_period_end_date')->nullable();
            $table->enum('status', ['active', 'inactive', 'suspended', 'trial'])->default('trial');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
