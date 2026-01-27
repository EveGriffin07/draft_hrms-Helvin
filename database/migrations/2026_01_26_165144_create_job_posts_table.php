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
        Schema::create('job_posts', function (Blueprint $table) {
    $table->id('job_id');
    $table->foreignId('created_by')->constrained('users', 'user_id');
    $table->foreignId('department_id')->constrained('departments', 'department_id');
    $table->string('job_title');
    $table->text('job_description');
    $table->string('location');
    $table->enum('job_status', ['open', 'closed', 'draft']);
    $table->string('employment_type'); // Full-time, Contract
    $table->string('salary_range')->nullable();
    $table->text('requirements')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_posts');
    }
};
