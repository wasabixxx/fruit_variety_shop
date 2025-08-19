<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class VerifyUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:verify {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manually verify a user email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("User with email {$email} not found!");
            return;
        }
        
        if ($user->hasVerifiedEmail()) {
            $this->info("User {$email} is already verified!");
            return;
        }
        
        // Mark email as verified
        $result = $user->markEmailAsVerified();
        
        // Reload user to get fresh data
        $user = $user->fresh();
        
        $this->info("✅ User {$email} has been verified successfully!");
        $this->info("Update result: " . ($result ? 'SUCCESS' : 'FAILED'));
        $this->info("Email verified at: " . ($user->email_verified_at ?? 'NULL'));
        $this->info("Has verified email: " . ($user->hasVerifiedEmail() ? 'YES' : 'NO'));
    }
}
