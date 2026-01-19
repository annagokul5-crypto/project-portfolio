<?php

namespace App\Http\Controllers;

use App\Models\ContactSubmission;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Models\Setting;


class WhatsAppWebhookController extends Controller
{
    private $token ;
    private $phoneNumberId;
    private $apiUrl = 'https://graph.instagram.com/v18.0';

    public function __construct()
    {
        $this->token         = env('WHATSAPP_TOKEN');
        $this->phoneNumberId = env('WHATSAPP_PHONE_ID');
    }

    public function webhook(Request $request)
    {
        $input = $request->all();

        $messageData = $input['entry'][0]['changes'][0]['value']['messages'][0] ?? null;
        $from = $input['entry'][0]['changes'][0]['value']['contacts'][0]['wa_id'] ?? null;
        $body = $messageData ? strtolower(trim($messageData['text']['body'] ?? '')) : '';


        // 1) Handle footer command
        if (preg_match('/^footer:\s*(\d{4})\s*to\s*(\d{4})$/', $body, $m)) {
            $fromYear = $m[1];
            $toYear   = $m[2];

            Setting::updateOrCreate(
                ['key' => 'footer_year'],
                ['value' => $toYear]
            );

            $reply = "Footer updated: {$fromYear} → {$toYear}";

            $this->sendWhatsAppText($from, $reply);
            return response('OK');
        }
        // WhatsApp sends messages here
        if (isset($input['entry'][0]['changes'][0]['value']['messages'][0])) {
            $messageData = $input['entry'][0]['changes'][0]['value']['messages'][0];
            $senderPhone = $input['entry'][0]['changes'][0]['value']['contacts'][0]['wa_id'];
            $messageText = $messageData['text']['body'] ?? '';

            // Parse user request
            if (stripos($messageText, 'send me') !== false || stripos($messageText, 'last month') !== false) {
                $this->handleReportRequest($senderPhone, $messageText);
            }
        }

        return response('ok', 200);
    }

    private function handleReportRequest($senderPhone, $messageText)
    {
        // Parse: "send me last month forms", "last one month", etc.
        $days = 30; // default 1 month

        if (stripos($messageText, 'last week') !== false) {
            $days = 7;
        } elseif (stripos($messageText, 'last 3 month') !== false || stripos($messageText, 'last three month') !== false) {
            $days = 90;
        }

        // Query forms from last N days
        $submissions = ContactSubmission::where('created_at', '>=', Carbon::now()->subDays($days))
            ->orderBy('created_at', 'desc')
            ->get();

        if ($submissions->isEmpty()) {
            $this->sendWhatsAppMessage($senderPhone, "No forms found in the last $days days.");
            return;
        }

        // Generate PDF
        $pdf = Pdf::loadView('whatsapp.submissions-pdf', ['submissions' => $submissions]);
        $pdfPath = storage_path('app/public/submissions-' . time() . '.pdf');
        $pdf->save($pdfPath);

        // Send PDF via WhatsApp
        $this->sendWhatsAppPdf($senderPhone, $pdfPath, "Contact Forms (Last $days days)");

        // Optional: cleanup after sending
        // unlink($pdfPath);
    }

    private function sendWhatsAppMessage($phone, $message)
    {
        Http::withToken($this->token)->post(
            "{$this->apiUrl}/{$this->phoneNumberId}/messages",
            [
                'messaging_product' => 'whatsapp',
                'to' => $phone,
                'type' => 'text',
                'text' => ['body' => $message],
            ]
        );
    }

    private function sendWhatsAppPdf($phone, $filePath, $caption = 'Document')
    {
        // Upload media first
        $uploadResponse = Http::asMultipart()
            ->withToken($this->token)
            ->post("{$this->apiUrl}/{$this->phoneNumberId}/media", [
                'file' => fopen($filePath, 'r'),
                'messaging_product' => 'whatsapp',
            ]);

        $mediaId = $uploadResponse->json()['id'];

        // Send document
        Http::withToken($this->token)->post(
            "{$this->apiUrl}/{$this->phoneNumberId}/messages",
            [
                'messaging_product' => 'whatsapp',
                'to' => $phone,
                'type' => 'document',
                'document' => [
                    'id' => $mediaId,
                    'caption' => $caption,
                ],
            ]
        );

    }
    private function sendWhatsAppText(string $phone, string $message): void
    {
        Http::withToken($this->token)->post(
            "{$this->apiUrl}/{$this->phoneNumberId}/messages",
            [
                'messaging_product' => 'whatsapp',
                'to'   => $phone,
                'type' => 'text',
                'text' => ['body' => $message],
            ]
        );
    }

}
