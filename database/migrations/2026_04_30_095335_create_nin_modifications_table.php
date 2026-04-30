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
        Schema::create('nin_modifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('tnx_id');
            $table->string('refno');
            $table->string('type'); // DOB, NAME, PHONE, ADDRESS, GENDER, OTHER
            $table->string('nin');
            $table->string('phone_number')->nullable();
            $table->string('surname')->nullable();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('dob')->nullable();
            $table->text('address')->nullable();
            $table->string('town')->nullable();
            $table->string('lga_origin')->nullable();
            $table->string('state_origin')->nullable();
            $table->string('lga_residence')->nullable();
            $table->string('state_residence')->nullable();
            $table->string('gender')->nullable();
            $table->string('modification_type_detail')->nullable();
            $table->string('clear_picture')->nullable();
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->enum('status', ['Pending', 'In-Progress', 'Successful', 'Failed'])->default('Pending');
            $table->text('reason')->nullable();
            $table->timestamp('refunded_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('tnx_id')->references('id')->on('transactions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nin_modifications');
    }
};
