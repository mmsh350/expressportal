<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // 1. Temporarily change the column to VARCHAR to allow any string value
        DB::statement("ALTER TABLE nin_validations MODIFY status VARCHAR(255)");

        // 2. Update existing values to the new capitalized ones
        DB::statement("UPDATE nin_validations SET status = 'Successful' WHERE status = 'resolved'");
        DB::statement("UPDATE nin_validations SET status = 'Pending' WHERE status = 'pending'");
        DB::statement("UPDATE nin_validations SET status = 'Failed' WHERE status = 'rejected'");
        DB::statement("UPDATE nin_validations SET status = 'In-Progress' WHERE status = 'processing'");

        // 3. Finalize the column as the new ENUM
        DB::statement("ALTER TABLE nin_validations MODIFY status ENUM('Successful', 'Pending', 'In-Progress', 'Failed') DEFAULT 'Pending'");
    }

    public function down()
    {
        // Reverse: VARCHAR -> update data -> ENUM
        DB::statement("ALTER TABLE nin_validations MODIFY status VARCHAR(255)");
        DB::statement("UPDATE nin_validations SET status = 'resolved' WHERE status = 'Successful'");
        DB::statement("UPDATE nin_validations SET status = 'pending' WHERE status = 'Pending'");
        DB::statement("UPDATE nin_validations SET status = 'rejected' WHERE status = 'Failed'");
        DB::statement("UPDATE nin_validations SET status = 'processing' WHERE status = 'In-Progress'");
        
        DB::statement("ALTER TABLE nin_validations MODIFY status ENUM('resolved', 'pending', 'rejected', 'processing') DEFAULT 'pending'");
    }
};
