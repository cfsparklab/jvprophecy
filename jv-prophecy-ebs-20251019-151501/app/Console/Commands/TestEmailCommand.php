<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use App\Models\User;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Support\Facades\Password;

class TestEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test {--email=test@example.com} {--type=all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email functionality including SMTP, registration, and password reset emails';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->option('email');
        $type = $this->option('type');

        $this->info('🔍 Email System Audit - Jebikalam Vaanga Prophecy');
        $this->info('=' . str_repeat('=', 50));

        // Check current configuration
        $this->checkEmailConfiguration();

        if ($type === 'all' || $type === 'smtp') {
            $this->testBasicSMTP($email);
        }

        if ($type === 'all' || $type === 'registration') {
            $this->testRegistrationEmail($email);
        }

        if ($type === 'all' || $type === 'password') {
            $this->testPasswordResetEmail($email);
        }

        $this->info('');
        $this->info('✅ Email audit completed!');
    }

    private function checkEmailConfiguration()
    {
        $this->info('');
        $this->info('📧 Current Email Configuration:');
        $this->info('-' . str_repeat('-', 30));

        $default = Config::get('mail.default');
        $host = Config::get('mail.mailers.smtp.host');
        $port = Config::get('mail.mailers.smtp.port');
        $username = Config::get('mail.mailers.smtp.username');
        $encryption = Config::get('mail.mailers.smtp.encryption');
        $fromAddress = Config::get('mail.from.address');
        $fromName = Config::get('mail.from.name');

        $this->line("Default Mailer: <fg=yellow>{$default}</>");
        $this->line("SMTP Host: <fg=yellow>{$host}</>");
        $this->line("SMTP Port: <fg=yellow>{$port}</>");
        $this->line("SMTP Username: <fg=yellow>" . ($username ?: 'Not set') . "</>");
        $this->line("SMTP Encryption: <fg=yellow>" . ($encryption ?: 'Not set') . "</>");
        $this->line("From Address: <fg=yellow>{$fromAddress}</>");
        $this->line("From Name: <fg=yellow>{$fromName}</>");

        // Check if using log driver
        if ($default === 'log') {
            $this->warn('⚠️  WARNING: Email is set to LOG driver - emails will be saved to log files, not sent!');
            $this->warn('   To fix: Set MAIL_MAILER=smtp in your .env file');
        }

        // Check if SMTP credentials are missing
        if ($default === 'smtp' && (!$username || !Config::get('mail.mailers.smtp.password'))) {
            $this->error('❌ ERROR: SMTP credentials are missing!');
            $this->error('   Add MAIL_USERNAME and MAIL_PASSWORD to your .env file');
        }
    }

    private function testBasicSMTP($email)
    {
        $this->info('');
        $this->info('🧪 Testing Basic SMTP Connection:');
        $this->info('-' . str_repeat('-', 30));

        try {
            Mail::raw('This is a test email from Jebikalam Vaanga Prophecy system.', function ($message) use ($email) {
                $message->to($email)
                        ->subject('SMTP Test - Jebikalam Vaanga Prophecy')
                        ->from(Config::get('mail.from.address'), Config::get('mail.from.name'));
            });

            $this->info("✅ Basic SMTP test email sent successfully to {$email}");
            
            if (Config::get('mail.default') === 'log') {
                $this->warn('   📝 Email was logged to storage/logs/laravel.log (not actually sent)');
            }

        } catch (\Exception $e) {
            $this->error("❌ SMTP test failed: " . $e->getMessage());
        }
    }

    private function testRegistrationEmail($email)
    {
        $this->info('');
        $this->info('👤 Testing Registration Email System:');
        $this->info('-' . str_repeat('-', 30));

        try {
            // Create a test user (or find existing)
            $user = User::where('email', $email)->first();
            
            if (!$user) {
                $this->warn("   No user found with email {$email}");
                $this->warn("   Creating temporary test user...");
                
                $user = new User([
                    'name' => 'Test User',
                    'email' => $email,
                    'mobile' => '1234567890',
                    'password' => 'test123',
                    'preferred_language' => 'en',
                    'is_active' => false,
                ]);
                
                // Generate verification code without saving to database
                $user->email_verification_token = \Illuminate\Support\Str::random(60);
                $user->email_verification_code = sprintf('%06d', mt_rand(100000, 999999));
                $user->verification_code_expires_at = now()->addHours(24);
            }

            // Test email verification notification
            $user->notify(new EmailVerificationNotification($user));
            
            $this->info("✅ Registration verification email sent successfully to {$email}");
            $this->info("   📧 Email includes:");
            $this->info("      - Welcome message");
            $this->info("      - 6-digit verification code: {$user->email_verification_code}");
            $this->info("      - Verification link");
            $this->info("      - Professional branding");

            if (Config::get('mail.default') === 'log') {
                $this->warn('   📝 Email was logged to storage/logs/laravel.log (not actually sent)');
            }

        } catch (\Exception $e) {
            $this->error("❌ Registration email test failed: " . $e->getMessage());
        }
    }

    private function testPasswordResetEmail($email)
    {
        $this->info('');
        $this->info('🔑 Testing Password Reset Email System:');
        $this->info('-' . str_repeat('-', 30));

        try {
            // Check if user exists
            $user = User::where('email', $email)->first();
            
            if (!$user) {
                $this->warn("   No user found with email {$email}");
                $this->warn("   Password reset requires an existing user");
                return;
            }

            // Test password reset
            $status = Password::sendResetLink(['email' => $email]);

            if ($status === Password::RESET_LINK_SENT) {
                $this->info("✅ Password reset email sent successfully to {$email}");
                $this->info("   📧 Email includes:");
                $this->info("      - Password reset link");
                $this->info("      - Security information");
                $this->info("      - Expiration notice");

                if (Config::get('mail.default') === 'log') {
                    $this->warn('   📝 Email was logged to storage/logs/laravel.log (not actually sent)');
                }
            } else {
                $this->error("❌ Password reset failed with status: {$status}");
            }

        } catch (\Exception $e) {
            $this->error("❌ Password reset email test failed: " . $e->getMessage());
        }
    }
}