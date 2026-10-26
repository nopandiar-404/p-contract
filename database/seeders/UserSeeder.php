<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🚀 Starting user seeding process...');

        try {
            // 1. Hentikan sementara foreign key checks
            Schema::disableForeignKeyConstraints();

            $this->command->info('1. Disabled foreign key checks');

            // 2. Truncate tabel users dengan aman
            if (Schema::hasTable('users')) {
                // Hapus semua data
                DB::table('users')->delete();
                $this->command->info('2. Cleared existing users data');

                // Reset auto increment / sequence
                $this->resetAutoIncrement('users');
                $this->command->info('3. Reset auto increment / sequence');
            } else {
                Schema::enableForeignKeyConstraints();
                $this->command->error('❌ Users table does not exist!');
                return;
            }

            // 3. Aktifkan kembali foreign key checks
            Schema::enableForeignKeyConstraints();
            $this->command->info('4. Enabled foreign key checks');

            // 4. Buat array users dengan EMAIL UNIK
            $users = [
                [
                    'id' => 1,
                    'name' => 'Super Admin',
                    'email' => 'superadmin@contract.app',
                    'password' => Hash::make('Admin123!'),
                    'role' => 'admin',
                    'email_verified_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => 2,
                    'name' => 'System Admin',
                    'email' => 'admin@contract.app',
                    'password' => Hash::make('Admin123!'),
                    'role' => 'admin',
                    'email_verified_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => 3,
                    'name' => 'Contract Manager',
                    'email' => 'manager@contract.app',
                    'password' => Hash::make('Manager123!'),
                    'role' => 'user',
                    'email_verified_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => 4,
                    'name' => 'Staff User',
                    'email' => 'staff@contract.app',
                    'password' => Hash::make('Staff123!'),
                    'role' => 'user',
                    'email_verified_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ];

            $this->command->info('5. Inserting main users...');

            // 5. Insert menggunakan INSERT IGNORE untuk menghindari duplicate
            $insertedCount = 0;
            foreach ($users as $user) {
                try {
                    // Gunakan insert dengan ignore
                    $result = DB::table('users')->insertOrIgnore($user);
                    if ($result) {
                        $insertedCount++;
                        $this->command->info("   ✓ Created: {$user['email']}");
                    } else {
                        $this->command->warn("   ⚠ Skipped (exists): {$user['email']}");
                    }
                } catch (\Exception $e) {
                    $this->command->error("   ✗ Error creating {$user['email']}: " . $e->getMessage());
                }
            }

            $this->command->info("6. Successfully inserted {$insertedCount} main users");

            // 6. Buat user dummy dengan email yang pasti unik
            $this->command->info('7. Creating dummy users...');
            $dummyCount = 0;
            for ($i = 1; $i <= 3; $i++) {
                try {
                    User::firstOrCreate(
                        ['email' => "user{$i}@contract.app"],
                        [
                            'name' => "Test User {$i}",
                            'password' => Hash::make('Password123!'),
                            'role' => 'user',
                            'email_verified_at' => now(),
                        ]
                    );
                    $dummyCount++;
                    $this->command->info("   ✓ Created dummy: user{$i}@contract.app");
                } catch (\Exception $e) {
                    $this->command->error("   ✗ Error creating dummy user{$i}: " . $e->getMessage());
                }
            }

            $this->command->info("8. Created {$dummyCount} dummy users");

            // 7. Tampilkan summary
            $totalUsers = DB::table('users')->count();

            $this->command->info('=============================================');
            $this->command->info('🎉 USER SEEDING COMPLETED SUCCESSFULLY!');
            $this->command->info('=============================================');
            $this->command->info("Total users in database: {$totalUsers}");
            $this->command->info('');
            $this->command->info('🔐 LOGIN CREDENTIALS:');
            $this->command->info('---------------------');
            $this->command->info('👑 Super Admin:');
            $this->command->info('   Email: superadmin@contract.app');
            $this->command->info('   Password: Admin123!');
            $this->command->info('');
            $this->command->info('👨‍💼 Admin:');
            $this->command->info('   Email: admin@contract.app');
            $this->command->info('   Password: Admin123!');
            $this->command->info('');
            $this->command->info('📋 Manager:');
            $this->command->info('   Email: manager@contract.app');
            $this->command->info('   Password: Manager123!');
            $this->command->info('');
            $this->command->info('👨‍🔧 Staff:');
            $this->command->info('   Email: staff@contract.app');
            $this->command->info('   Password: Staff123!');
            $this->command->info('=============================================');
        } catch (\Exception $e) {
            $this->command->error('❌ ERROR during seeding: ' . $e->getMessage());
            $this->command->error('File: ' . $e->getFile());
            $this->command->error('Line: ' . $e->getLine());

            // Pastikan foreign key checks diaktifkan kembali
            Schema::enableForeignKeyConstraints();
        }
    }

    protected function resetAutoIncrement(string $table): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            DB::statement('DELETE FROM sqlite_sequence WHERE name = ?', [$table]);
        } elseif ($driver === 'mysql') {
            DB::statement("ALTER TABLE {$table} AUTO_INCREMENT = 1");
        }
    }
}
