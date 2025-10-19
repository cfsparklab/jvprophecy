<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;
use Exception;

class EmailVerificationNotification extends Notification
{
    // Removed Queueable trait and ShouldQueue interface to send emails immediately

    protected $user;
    protected $verificationUrl;

    /**
     * Create a new notification instance.
     */
    public function __construct($user)
    {
        $this->user = $user;
        
        // Generate verification URL safely
        try {
            $this->verificationUrl = URL::signedRoute(
                'verification.verify',
                [
                    'id' => $user->id,
                    'hash' => sha1($user->email)
                ]
            );
        } catch (Exception $e) {
            // Fallback URL if route generation fails
            $this->verificationUrl = url('/email/verify/' . $user->id . '/' . sha1($user->email));
        }
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Verify Your Email Address - Jebikalam Vaanga Prophecy')
            ->greeting('Welcome to Jebikalam Vaanga Prophecy!')
            ->line('Thank you for registering with us. Please verify your email address to activate your account.')
            ->line('**Your Verification Code:** ' . $this->user->email_verification_code)
            ->line('This verification code and link will remain valid until you verify your account.')
            ->action('Verify Email Address', $this->verificationUrl)
            ->line('Alternatively, you can enter the 6-digit verification code above on the verification page.')
            ->line('If you did not create an account, no further action is required.')
            ->salutation('Best regards, The Jebikalam Vaanga Prophecy Team');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'verification_code' => $this->user->email_verification_code,
            'verification_url' => $this->verificationUrl,
            'expires_at' => $this->user->verification_code_expires_at,
        ];
    }
}