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
        Schema::table('bvn_enrollments', function (Blueprint $table) {
            $table->string('agent_location')->nullable()->after('refno');
            $table->string('first_name')->nullable()->after('agent_location');
            $table->string('last_name')->nullable()->after('first_name');
            $table->date('dob')->nullable()->after('phone_number');
            $table->string('geo_zone')->nullable()->after('city');
            $table->string('kegow_account')->nullable()->after('bvn');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bvn_enrollments', function (Blueprint $table) {
            $table->dropColumn(['agent_location', 'first_name', 'last_name', 'dob', 'geo_zone', 'kegow_account']);
        });
    }
};
