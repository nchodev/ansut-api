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
            $table->dropColumn('city_id');
            $table->dropForeign(['school_id']);
            $table->dropColumn('school_id');
            $table->dropForeign(['grade_id']);
            $table->dropColumn('grade_id');
            $table->dropColumn('mother_tongue_id');
            $table->dropColumn('social_statut_id');
            
        });
    }
};
