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
        Schema::table('ipe_requests', function (Blueprint $table) {
            $table->string('status')->default('pending')->after('reply');
            $table->unsignedBigInteger('tnx_id')->nullable()->after('status');
            $table->string('refno')->nullable()->after('tnx_id');
        });

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ipe_requests', function (Blueprint $table) {
            $table->dropColumn(['status', 'tnx_id', 'refno']);
        });
    }
};
