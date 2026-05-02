<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateReferralCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-referral-codes {--force : Overwrite existing codes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate unique random referral codes for active and verified users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fetching active and verified users...');

        $query = User::where('is_active', true)
            ->where('kyc_status', 'Verified');

        // Only generate for those who don't have one, unless --force is used
        if (!$this->option('force')) {
            $query->where(function ($q) {
                $q->whereNull('referral_code')->orWhere('referral_code', '');
            });
        }

        $users = $query->get();

        if ($users->isEmpty()) {
            $this->info('No eligible users found without referral codes.');
            return;
        }

        $this->info('Generating unique codes for ' . $users->count() . ' users...');

        $results = [];
        foreach ($users as $user) {
            $code = $this->generateUniqueCode();
            $user->update(['referral_code' => $code]);
            
            $results[] = [
                'ID' => $user->id,
                'Name' => $user->name,
                'Email' => $user->email,
                'Code' => $code
            ];
        }

        // Display summary
        $this->table(['ID', 'Name', 'Email', 'Referral Code'], $results);
        $this->info('Total Generated: ' . count($results));
    }

    /**
     * Generate a unique referral code.
     */
    private function generateUniqueCode()
    {
        do {
            // Generate a random 8-character alphanumeric code
            $code = strtoupper(Str::random(8));
        } while (User::where('referral_code', $code)->exists());

        return $code;
    }
}
