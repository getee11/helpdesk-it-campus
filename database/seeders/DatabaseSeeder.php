<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Departments
        $deptIT = \App\Models\Department::create(['name' => 'IT / Sistem Informasi']);
        $deptAkademik = \App\Models\Department::create(['name' => 'Akademik / Kemahasiswaan']);

        // 2. Seed Categories
        $catProyektor = \App\Models\Category::create(['name' => 'Proyektor & Layar', 'description' => 'Kerusakan pada proyektor kelas atau layar.']);
        $catKomputer = \App\Models\Category::create(['name' => 'Komputer Lab', 'description' => 'Kerusakan hardware atau software di lab.']);
        $catWifi = \App\Models\Category::create(['name' => 'Jaringan WiFi', 'description' => 'Kendala koneksi jaringan atau internet lambat.']);

        // 3. Seed Users
        $admin = \App\Models\User::create([
            'name' => 'Administrator',
            'email' => 'admin@helpdesk.com',
            'password' => bcrypt('password'),
            'role' => 'superadmin',
            'nim_nip' => 'ADM001',
            'department_id' => $deptIT->id,
            'phone' => '081234567890',
            'is_active' => true,
        ]);

        $teknisi1 = \App\Models\User::create([
            'name' => 'Teknisi Satu',
            'email' => 'teknisi1@helpdesk.com',
            'password' => bcrypt('password'),
            'role' => 'teknisi',
            'nim_nip' => 'TKN001',
            'department_id' => $deptIT->id,
            'phone' => '081234567891',
            'is_active' => true,
        ]);

        $teknisi2 = \App\Models\User::create([
            'name' => 'Teknisi Dua',
            'email' => 'teknisi2@helpdesk.com',
            'password' => bcrypt('password'),
            'role' => 'teknisi',
            'nim_nip' => 'TKN002',
            'department_id' => $deptIT->id,
            'phone' => '081234567892',
            'is_active' => true,
        ]);

        $pelapor = \App\Models\User::create([
            'name' => 'Dosen / Mahasiswa',
            'email' => 'pelapor@helpdesk.com',
            'password' => bcrypt('password'),
            'role' => 'pelapor',
            'nim_nip' => 'DSN001',
            'department_id' => $deptAkademik->id,
            'phone' => '081234567893',
            'is_active' => true,
        ]);

        // 4. Seed Tickets
        $ticket1 = \App\Models\Ticket::create([
            'ticket_number' => \App\Models\Ticket::generateTicketNumber(),
            'user_id' => $pelapor->id,
            'category_id' => $catProyektor->id,
            'subject' => 'Proyektor Kelas A Mati',
            'description' => 'Proyektor di kelas A gedung utama tidak bisa menyala meskipun kabel power sudah dicolokkan.',
            'location' => 'Gedung Utama',
            'room' => 'Kelas A',
            'priority' => 'tinggi',
            'status' => 'open',
        ]);

        $ticket2 = \App\Models\Ticket::create([
            'ticket_number' => \App\Models\Ticket::generateTicketNumber(),
            'user_id' => $pelapor->id,
            'category_id' => $catWifi->id,
            'subject' => 'WiFi Lambat di Perpustakaan',
            'description' => 'Koneksi WiFi mahasiswa sering putus nyambung saat berada di lantai 2 perpustakaan.',
            'location' => 'Perpustakaan',
            'room' => 'Lantai 2',
            'priority' => 'sedang',
            'status' => 'progress',
        ]);
        
        // Attach technician to progress ticket
        $ticket2->technicians()->attach($teknisi1->id);
    }
}
