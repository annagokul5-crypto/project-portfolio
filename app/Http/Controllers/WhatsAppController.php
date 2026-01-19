<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\Rest\Client;

class WhatsAppController extends Controller
{
    private $twilioClient;
    private $twilioWhatsAppNumber;

    public function __construct()
    {
        $sid = env('TWILIO_SID');
        $token = env('TWILIO_AUTH_TOKEN');
        $this->twilioWhatsAppNumber = env('TWILIO_WHATSAPP_NUMBER');

        $this->twilioClient = new Client($sid, $token);
    }

    // Test method to send WhatsApp message
    public function sendTest()
    {
        try {
            $message = $this->twilioClient->messages->create(
                "whatsapp:+919790168632", // Your WhatsApp number
                [
                    "from" => "whatsapp:" . $this->twilioWhatsAppNumber,
                    "body" => "🎉 Hello from Laravel! Your AI WhatsApp Dashboard is working! 🚀"
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'WhatsApp message sent successfully!',
                'sid' => $message->sid
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Webhook to receive WhatsApp messages
    public function receiveMessage(Request $request)
    {
        $from = $request->input('From'); // Sender's WhatsApp number
        $body = $request->input('Body'); // Message text

        // Log the message
        \Log::info('WhatsApp Message Received', [
            'from' => $from,
            'body' => $body
        ]);

        // Send automatic reply
        try {
            $this->twilioClient->messages->create(
                $from,
                [
                    "from" => "whatsapp:" . $this->twilioWhatsAppNumber,
                    "body" => "✅ Message received: " . $body
                ]
            );
        } catch (\Exception $e) {
            \Log::error('Error sending reply: ' . $e->getMessage());
        }

        return response('', 200);
    }
    public function twilioWebhook(Request $request)
    {
        $body = $request->input('Body'); // "footer:2025 to 2026"
        \Log::info('Twilio WA', $request->all());

        // TODO: parse $body and update footer setting
    }

}
