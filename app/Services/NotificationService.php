<?php

namespace App\Services;

use App\Models\NotificationLog;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send Email and log the result
     */
    public static function sendEmail($userId, $email, $name, $subject, $viewName, $viewData)
    {
        $content = '';
        try {
            $content = view($viewName, $viewData)->render();

            Mail::send($viewName, $viewData, function($message) use ($email, $name, $subject) {
                $message->to($email, $name)
                        ->subject($subject);
            });

            NotificationLog::create([
                'user_id' => $userId,
                'type' => 'email',
                'recipient' => $email,
                'recipient_name' => $name,
                'subject' => $subject,
                'content' => $content,
                'status' => 'success',
            ]);

            return true;
        } catch (\Exception $e) {
            if (empty($content)) {
                $content = 'Failed to render email template: ' . $viewName;
            }

            NotificationLog::create([
                'user_id' => $userId,
                'type' => 'email',
                'recipient' => $email,
                'recipient_name' => $name,
                'subject' => $subject,
                'content' => $content,
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            Log::error('Gagal mengirim email ke ' . $email . ': ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send WhatsApp and log the result
     */
    public static function sendWhatsApp($userId, $phone, $name, $message)
    {
        $token = env('FONNTE_TOKEN');
        
        // Normalize phone number (convert 08xxx to 628xxx)
        $formattedPhone = $phone;
        if (substr($formattedPhone, 0, 1) === '0') {
            $formattedPhone = '62' . substr($formattedPhone, 1);
        }

        try {
            if (empty($token) || $token === 'your_token_here') {
                // If token is not configured, simulate success in log
                NotificationLog::create([
                    'user_id' => $userId,
                    'type' => 'whatsapp',
                    'recipient' => $phone,
                    'recipient_name' => $name,
                    'subject' => null,
                    'content' => $message,
                    'status' => 'success',
                    'error_message' => 'Simulated success (FONNTE_TOKEN not configured in .env)',
                ]);
                return true;
            }

            // Real HTTP request to Fonnte WA API
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->post('https://api.fonnte.com/send', [
                'target' => $formattedPhone,
                'message' => $message,
            ]);

            $result = $response->json();
            $status = (isset($result['status']) && $result['status'] == true) ? 'success' : 'failed';
            $err = $status === 'failed' ? ($result['reason'] ?? 'Unknown Fonnte error') : null;

            NotificationLog::create([
                'user_id' => $userId,
                'type' => 'whatsapp',
                'recipient' => $phone,
                'recipient_name' => $name,
                'subject' => null,
                'content' => $message,
                'status' => $status,
                'error_message' => $err,
            ]);

            return $status === 'success';
        } catch (\Exception $e) {
            NotificationLog::create([
                'user_id' => $userId,
                'type' => 'whatsapp',
                'recipient' => $phone,
                'recipient_name' => $name,
                'subject' => null,
                'content' => $message,
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            Log::error('Gagal mengirim WhatsApp ke ' . $phone . ': ' . $e->getMessage());
            return false;
        }
    }
}
