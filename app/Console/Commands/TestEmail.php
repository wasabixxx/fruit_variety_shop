<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Mail\EmailVerification;

class TestEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email verification system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        $this->info("Testing email to: {$email}");
        
        try {
            // Create a test user
            $testUser = new User();
            $testUser->name = 'Test User';
            $testUser->email = $email;
            
            $token = bin2hex(random_bytes(32));
            
            Mail::to($email)->send(new EmailVerification($testUser, $token));
            
            $this->info('✅ Email sent successfully!');
            $this->info("Gmail: {$email}");
            $this->info("From: fruitvarietyshop@gmail.com");
            
        } catch (\Exception $e) {
            $this->error('❌ Failed to send email: ' . $e->getMessage());
        }
    }
}
