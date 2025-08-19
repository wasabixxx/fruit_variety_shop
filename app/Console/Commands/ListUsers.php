<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class ListUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all users and their verification status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->info('No users found in database.');
            return;
        }
        
        $this->info("Found {$users->count()} users:");
        $this->line('');
        
        foreach ($users as $user) {
            $this->info("ID: {$user->id}");
            $this->info("Name: {$user->name}");
            $this->info("Email: {$user->email}");
            $this->info("Role: {$user->role}");
            $this->info("Email Verified At: " . ($user->email_verified_at ? $user->email_verified_at : 'NULL'));
            $this->info("Verification Token: " . ($user->email_verification_token ? 'EXISTS' : 'NULL'));
            $this->info("Has Verified Email: " . ($user->hasVerifiedEmail() ? 'YES' : 'NO'));
            $this->line('---');
        }
    }
}
