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
        
      
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('full_name')->default('Anonyme');
            $table->string('username')->default('Anonyme'); // login via pseudo

            // Email & téléphone (nullable pour les users OAuth ou login téléphone uniquement)
            $table->string('email')->unique()->nullable();
            $table->string('phone_number')->unique()->nullable();
            $table->string('fcm_token')->nullable();

            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->string('password')->nullable()->index();

            $table->date('date_of_birth')->nullable();
            $table->string('matricule')->unique()->nullable();
            $table->string('badge')->nullable();

            // OAuth
            $table->string('login_provider')->nullable()->index();    // ex: google, facebook
            $table->string('provider_id')->nullable()->index();       // ID chez le provider

            // Statut utilisateur
            $table->tinyInteger('status')->default(0)->index(); 
            $table->string('profile_picture')->nullable();   
            $table->string('lang');      // URL CDN ou locale
            $table->timestamp('last_login_at')->nullable();           // Pour journal d'activité

            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->unique()->nullable(); // Pour les utilisateurs qui se connectent par email
            $table->string('phone')->unique()->nullable(); // Pour les utilisateurs qui se connectent par téléphone
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
