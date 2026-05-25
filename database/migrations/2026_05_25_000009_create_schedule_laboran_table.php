<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedule_laboran', function (Blueprint $table) {
            $table->foreignId('schedule_id')->constrained()->cascadeOnDelete();
            $table->foreignId('laboran_id')->constrained('laborans')->cascadeOnDelete();
            $table->primary(['schedule_id', 'laboran_id']);
            $table->index(['laboran_id', 'schedule_id'], 'idx_sl_laboran_schedule');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedule_laboran');
    }
};
