<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('order_no')->unique(); // ORD-240512
            $table->string('style_name');
            $table->unsignedInteger('quantity');
            $table->enum('current_stage', ['cutting', 'sewing', 'qc_inspection', 'packing', 'dispatched'])
                ->default('cutting');
            $table->date('eta')->nullable();
            $table->timestamps();

            $table->index(['company_id', 'current_stage']);
        });

        Schema::create('order_stage_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('stage');
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('changed_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_stage_logs');
        Schema::dropIfExists('orders');
    }
};
