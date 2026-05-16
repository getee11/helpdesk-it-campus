<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['superadmin', 'admin', 'teknisi', 'pelapor'])->default('pelapor')->after('password');
            $table->foreignId('department_id')->nullable()->constrained()->onDelete('set null')->after('role');
            $table->string('nim_nip')->nullable()->after('department_id');
            $table->string('phone')->nullable()->after('nim_nip');
            $table->boolean('is_active')->default(true)->after('phone');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn(['role', 'department_id', 'nim_nip', 'phone', 'is_active']);
        });
    }
};