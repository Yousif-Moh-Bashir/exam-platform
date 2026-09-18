<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attempts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('exam_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->timestamp('started_at');

            $table->timestamp('submitted_at')->nullable();

            $table->unsignedInteger('score')->nullable();

            $table->enum('status', [
                'in_progress',
                'completed'
            ])->default('in_progress');

            $table->timestamps();

            $table->index([
                'student_id',
                'exam_id',
                'status'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attempts');
    }
};
