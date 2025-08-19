<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class UpdateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update admin user to have correct role and verified status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $admin = User::where('email', 'admin@admin.com')->first();
        
        if (!$admin) {
            $this->error('Admin user not found!');
            return;
        }
        
        $admin->update([
            'role' => 'admin',
            'email_verified_at' => now(),
            'email_verification_token' => null,
        ]);
        
        $this->info('✅ Admin user updated successfully!');
        $this->info("Role: {$admin->fresh()->role}");
        $this->info("Is Admin: " . ($admin->fresh()->isAdmin() ? 'YES' : 'NO'));
        $this->info("Email Verified: " . ($admin->fresh()->hasVerifiedEmail() ? 'YES' : 'NO'));
    }
}
