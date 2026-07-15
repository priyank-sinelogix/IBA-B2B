<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('samples', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('sample_code')->unique(); // SMP-0247
            $table->string('style_name');
            $table->string('fabric')->nullable();     // Piqué 220 GSM
            $table->string('color')->nullable();
            $table->enum('status', ['pending', 'approved', 'changes_requested'])->default('pending');
            $table->foreignId('submitted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            $table->index(['company_id', 'status']);
        });

        Schema::create('sample_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sample_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('version_no')->default(1);
            $table->string('image_path'); // S3 key, served via signed URL
            $table->string('fabric_swatch_path')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['sample_id', 'version_no']);
        });

        Schema::create('sample_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sample_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sample_version_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('comment');
            $table->enum('action', ['comment', 'approve', 'revise'])->default('comment');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sample_comments');
        Schema::dropIfExists('sample_versions');
        Schema::dropIfExists('samples');
    }
};
