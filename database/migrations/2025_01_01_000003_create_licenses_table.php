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
        Schema::create('licenses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('license_id')->unique();
            $table->string('product_key')->unique();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('rsa_key_id')->constrained()->onDelete('restrict');
            $table->enum('license_type', ['TRIAL', 'PERSONAL', 'PROFESSIONAL', 'ENTERPRISE', 'CUSTOM'])->default('TRIAL');
            $table->enum('status', ['ACTIVE', 'EXPIRED', 'REVOKED', 'PENDING'])->default('ACTIVE');
            $table->integer('max_devices')->default(1);
            $table->json('features')->nullable();
            $table->string('hardware_id')->nullable();
            $table->date('issue_date');
            $table->date('expiry_date');
            $table->timestamp('revoked_at')->nullable();
            $table->text('revocation_reason')->nullable();
            $table->longText('pem_content')->nullable();
            $table->json('license_data')->nullable();
            $table->text('signature')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('license_id');
            $table->index('product_key');
            $table->index('status');
            $table->index('expiry_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('licenses');
    }
};
