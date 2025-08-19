<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class CheckUserVerification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:check-verification {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check user email verification status';

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
        
        $this->info("User Information:");
        $this->info("Name: {$user->name}");
        $this->info("Email: {$user->email}");
        $this->info("Role: {$user->role}");
        $this->info("Email Verified At: " . ($user->email_verified_at ? $user->email_verified_at : 'NULL'));
        $this->info("Verification Token: " . ($user->email_verification_token ? 'EXISTS' : 'NULL'));
        $this->info("Has Verified Email: " . ($user->hasVerifiedEmail() ? 'YES' : 'NO'));
        
        if (!$user->hasVerifiedEmail()) {
            $this->warn("This user needs email verification!");
            
            if ($this->confirm('Do you want to manually verify this user?')) {
                $user->markEmailAsVerified();
                $this->info("✅ User has been manually verified!");
            }
        }
    }
}
