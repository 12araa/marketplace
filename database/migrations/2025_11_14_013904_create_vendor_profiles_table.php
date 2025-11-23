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
        Schema::create('vendor_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('shop_name')->unique(); // Nama toko, harus unik
            $table->text('shop_description')->nullable();
            $table->string('shop_phone_number', 20)->nullable();
            $table->text('shop_address')->nullable();
            $table->string('shop_city', 100)->nullable();
            $table->enum('verification_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('id_card_image_path')->nullable(); // Foto KTP
            $table->string('bank_account_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_profiles');
    }
};
