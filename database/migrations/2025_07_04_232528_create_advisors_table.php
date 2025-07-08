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
       Schema::create('advisors', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email')->unique()->nullable();
            $table->string('phone_number')->nullable();
            $table->enum('specialty', [
                'orientation',
                'psychologique',
                'sante_menstruelle',
                'developpement_personnel',
                'juridique',
                'education_sexuelle',
                'leadership'
            ])->default('orientation');
            $table->text('bio')->nullable(); // Courte présentation
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advisors');
    }
};
