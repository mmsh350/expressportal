<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MigrateOldUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:migrate-old-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate names, email, phone from usersold and balances from balance table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting migration from usersold and balance tables...');

        try {
            DB::transaction(function () {
                // 1. Migrate Users (Name, Email, Phone, Password)
                $this->info('Migrating users (names, emails, phones)...');
                
                // We use DB::statement for performance and because usersold is likely a temporary table
                $userCount = DB::table('usersold')->count();
                
                if ($userCount === 0) {
                    $this->warn('No users found in usersold table.');
                    return;
                }

                $this->info('Migrating users (hashing passwords)...');

                DB::table('usersold')->orderBy('id')->chunk(100, function ($oldUsers) {
                    $userData = [];
                    foreach ($oldUsers as $oldUser) {
                        $userData[] = [
                            'name' => trim(($oldUser->firstName ?? '') . ' ' . ($oldUser->lastName ?? '')),
                            'email' => $oldUser->email,
                            'phone_number' => $oldUser->phone,
                            'password' => \Illuminate\Support\Facades\Hash::make($oldUser->password),
                            'is_active' => $oldUser->status ?? 1,
                            'kyc_status' => ($oldUser->kyc ?? 0) == 1 ? 'Verified' : 'Pending',
                            'has_moved' => 1,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                    
                    // Use upsert to handle both insert and update scenarios based on email
                    DB::table('users')->upsert($userData, ['email'], [
                        'name', 
                        'phone_number', 
                        'password', 
                        'is_active',
                        'kyc_status',
                        'has_moved', 
                        'updated_at'
                    ]);
                });

                $this->info('Users migrated and passwords hashed.');

                // 2. Migrate Wallets (Amount, Bonus)
                $this->info('Migrating wallet balances and bonuses...');
                
                DB::statement("
                    INSERT INTO wallets (user_id, balance, bonus, created_at, updated_at)
                    SELECT 
                        u.id, 
                        COALESCE(CAST(NULLIF(b.amount, '') AS DECIMAL(15, 2)), 0.00), 
                        COALESCE(CAST(NULLIF(b.bonus, '') AS DECIMAL(15, 2)), 0.00), 
                        NOW(), 
                        NOW()
                    FROM balance b
                    JOIN users u ON b.user = u.email
                    ON DUPLICATE KEY UPDATE 
                        wallets.balance = VALUES(balance), 
                        wallets.bonus = VALUES(bonus)
                ");

                $this->info('Wallets migrated.');

                // 3. Update flags for users who now have wallets
                $this->info('Updating wallet_is_created flags...');
                
                DB::statement("
                    UPDATE users 
                    SET wallet_is_created = 1 
                    WHERE id IN (SELECT user_id FROM wallets)
                ");
            });

            $this->info('Migration completed successfully!');
            
        } catch (\Exception $e) {
            $this->error('Migration failed: ' . $e->getMessage());
            Log::error('Migration Error: ' . $e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
