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
        Schema::table('users', function (Blueprint $table) {
            //school info
            $table->enum('role', ['admin', 'mentor','donor','advisor','student'])->default('student');;
            $table->foreignId('social_statut_id') ->nullable()->constrained()->nullOnDelete();
            $table->foreignId('mother_tongue_id') ->nullable()->constrained()->nullOnDelete();
            $table->foreignId('grade_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('city_id') ->nullable()->constrained()->nullOnDelete();
            $table->foreignId('school_id')->nullable()->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropColumn(['role']);
            $table->dropForeign(['city_id']);
            $table->dropForeign(['school_id']);
            $table->dropColumn('grade_id');
            $table->dropColumn('mother_tongue_id');
            $table->dropColumn('social_statut_id');
            
        });
    }
};
