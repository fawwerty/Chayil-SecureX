<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('billings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->string('status')->default('pending');
            $table->date('due_date');
            $table->date('paid_date')->nullable();
            $table->string('invoice_number')->nullable()->unique();
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->index('status');
            $table->index('due_date');
            // NO user_id
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('billings');
    }
};