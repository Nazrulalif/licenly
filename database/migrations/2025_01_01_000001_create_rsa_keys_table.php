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
        Schema::create('rsa_keys', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->text('public_key');
            $table->text('private_key'); // Encrypted with AES-256
            $table->integer('key_size')->default(4096);
            $table->boolean('is_active')->default(false);
            $table->timestamp('generated_at');
            $table->timestamps();
            $table->softDeletes();

            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rsa_keys');
    }
};
