<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ledger_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['invoice', 'payment', 'credit_note', 'debit_note'])->default('invoice');
            $table->string('reference_no')->nullable(); // invoice/payment ref
            $table->decimal('amount', 14, 2);
            $table->decimal('balance_after', 14, 2);
            $table->text('description')->nullable();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();

            $table->index(['company_id', 'created_at']);
        });

        Schema::create('statements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->date('period_from');
            $table->date('period_to');
            $table->string('file_path'); // generated PDF, S3 key
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('statements');
        Schema::dropIfExists('ledger_entries');
    }
};
