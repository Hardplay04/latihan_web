<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // role inti aplikasi
            $table->string('role', 20)->default('student')->after('password'); 
            // status pengajuan instructor
            $table->string('instructor_status', 20)->default('none')->after('role'); 
            // data pengajuan (json)
            $table->json('instructor_application')->nullable()->after('instructor_status');

            $table->timestamp('instructor_applied_at')->nullable()->after('instructor_application');
            $table->timestamp('instructor_reviewed_at')->nullable()->after('instructor_applied_at');

            $table->unsignedBigInteger('instructor_reviewed_by')->nullable()->after('instructor_reviewed_at');
            $table->text('instructor_review_note')->nullable()->after('instructor_reviewed_by');

            $table->index(['role', 'instructor_status']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role', 'instructor_status']);

            $table->dropColumn([
                'role',
                'instructor_status',
                'instructor_application',
                'instructor_applied_at',
                'instructor_reviewed_at',
                'instructor_reviewed_by',
                'instructor_review_note',
            ]);
        });
    }
};
