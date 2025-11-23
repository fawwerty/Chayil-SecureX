<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->foreignId('reported_by')->constrained('users')->onDelete('cascade');
            $table->string('status')->default('open');
            $table->string('priority')->default('medium');
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
            
            $table->index('status');
            $table->index('priority');
            $table->index('reported_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incidents');
    }
};