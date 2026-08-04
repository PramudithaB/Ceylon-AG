<?php

namespace App\Console\Commands;

use App\Mail\TestMailable;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class TestMailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:test {email : Target email address to send test email to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verify Hostinger SMTP configuration by sending a test email';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $recipient = $this->argument('email');

        if (! filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
            $this->error("Invalid email address provided: {$recipient}");
            return Command::FAILURE;
        }

        $this->info("Attempting to send test email over Hostinger SMTP to: {$recipient}...");
        $this->comment("SMTP Host: " . config('mail.mailers.smtp.host'));
        $this->comment("SMTP Port: " . config('mail.mailers.smtp.port'));
        $this->comment("SMTP Encryption: " . config('mail.mailers.smtp.encryption'));
        $this->comment("From Address: " . config('mail.from.address'));

        try {
            Mail::to($recipient)->send(new TestMailable($recipient));
            $this->newLine();
            $this->info("SUCCESS: Test email was sent successfully to {$recipient}!");
            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $this->newLine();
            $this->error("FAILURE: Could not send email via Hostinger SMTP.");
            $this->error("Error Message: " . $e->getMessage());
            Log::error("Artisan mail:test failed for {$recipient}: " . $e->getMessage(), ['exception' => $e]);
            return Command::FAILURE;
        }
    }
}
