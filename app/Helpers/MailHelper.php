<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Mail\Transport\SmtpTransport; // Import SmtpTransport for type checking

class MailHelper
{
    // Use self::$debug to access this static property
    private static $debug = true;

    /**
     * Send an email with verification of SMTP connection (if applicable).
     *
     * @param string $toName The recipient's name.
     * @param string $toEmail The recipient's email address.
     * @param string $subject The email subject.
     * @param string $message The email body content (text or HTML).
     * @param string $type The type of email content ('text' or 'html'). Defaults to 'text'.
     * @return bool True if the email was processed successfully, false otherwise.
     */
    public static function sendEmail(string $toName, string $toEmail, string $subject, string $message, string $type = 'text'): bool
    {
        // Use self::$debug for accessing the static property
        if (self::$debug) {
            Log::info('Email sending initiated', ['to' => $toEmail]);
        }

        try {
            // Get the underlying Symfony Transport instance
            $transport = Mail::mailer()->getSymfonyTransport();

            // Only attempt start() for transports that have this method (like SMTP)
            // Check if the transport is an instance of SmtpTransport or similar
            if ($transport instanceof SmtpTransport) {
                try {
                    $transport->start();
                    if (self::$debug) {
                        Log::info('SMTP connection verified', [
                            // Accessing mailer config directly
                            'host' => config('mail.mailers.smtp.host') ?? 'N/A',
                            'port' => config('mail.mailers.smtp.port') ?? 'N/A',
                            'encryption' => config('mail.mailers.smtp.encryption') ?? 'N/A'
                        ]);
                    }
                } catch (\Exception $transportException) {
                    // Log transport verification failure but continue to attempt sending
                    if (self::$debug) {
                         Log::warning('SMTP transport verification failed', [
                             'error' => $transportException->getMessage()
                         ]);
                    }
                    // Note: An exception here doesn't necessarily mean sending will fail,
                    // as the Mail facade handles its own connection logic during the actual send.
                }
            } else {
                if (self::$debug) {
                    Log::info('Skipping transport verification for non-SMTP driver', [
                        'driver' => config('mail.mailer')
                    ]);
                }
            }

            // Use self::$debug for accessing the static property
            if (self::$debug) {
                Log::info('Attempting to send email', [
                    'from' => config('mail.from.address'),
                    'to' => $toEmail,
                    'subject' => $subject
                ]);
            }

            // Use Mail::html for sending raw HTML content
            if ($type === 'html') {
                // Pass the HTML content as the first argument to Mail::html
                Mail::html($message, function($mailMessage) use ($toName, $toEmail, $subject) {
                    // Renamed closure parameter to $mailMessage to avoid conflict with function parameter $message
                    $mailMessage->from(config('mail.from.address'), config('mail.from.name'))
                        ->to($toEmail, $toName)
                        ->subject($subject);
                });
            } else { // Default to text
                // Pass the text content as the first argument to Mail::raw
                Mail::raw($message, function($mailMessage) use ($toName, $toEmail, $subject) {
                    // Renamed closure parameter to $mailMessage for consistency
                    $mailMessage->from(config('mail.from.address'), config('mail.from.name'))
                        ->to($toEmail, $toName)
                        ->subject($subject);
                });
            }

            // Use self::$debug for accessing the static property
            if (self::$debug) {
                Log::info('Email processed successfully', [
                    'to' => $toEmail,
                    'subject' => $subject,
                    'driver' => config('mail.mailer') // Indicate which driver was used
                ]);
            }

            return true;

        } catch (Exception $e) {
            // Use self::$debug for accessing the static property
            if (self::$debug) {
                Log::error('Failed to process email', [
                    'to' => $toEmail,
                    'subject' => $subject,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString() // Include trace for detailed debugging
                ]);
            }

            return false;
        }
    }
}
