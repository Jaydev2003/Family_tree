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
        Schema::create('data', function (Blueprint $table) {
            $table->id();
            $table->string('kutumb_no')->nullable();
            $table->string('name');
            $table->string('email')->unique(); 
            $table->enum('gender', ['Male', 'Female']); 
            $table->enum('status', ['Married', 'Unmarried']); 
            $table->enum('relation', [
                'father', 'mother', 'brother', 'sister',
                'son', 'daughter', 'wife'
            ]); 
            $table->string('phone', 10)->unique(); 
            $table->string('address', 500); 
            $table->unsignedBigInteger('old_parent_id')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable(); 
            $table->timestamps(); 
            
            

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data');
    }
};
