<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use TelegramBot\Api\BotApi;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\AICommandController;

class TelegramController extends Controller
{
    protected $telegram;
    protected $ai;

    public function __construct()
    {
        $this->telegram = new BotApi(env('TELEGRAM_BOT_TOKEN'));
        $this->ai = new AICommandController();
    }

    public function webhook(Request $request)
    {
        try {
            $data = $request->all();

            Log::info('Telegram Webhook Received', $data);

            if (isset($data['message']['text'])) {
                $chatId = $data['message']['chat']['id'];
                $text = $data['message']['text'];

                // Process command with AI
                $response = $this->ai->processCommand($text);

                // Send response
                $this->telegram->sendMessage($chatId, $response);
            }

            return response()->json(['status' => 'ok']);

        } catch (\Exception $e) {
            Log::error('Telegram Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function sendTestMessage()
    {
        try {
            $chatId = env('TELEGRAM_CHAT_ID');

            if (!$chatId) {
                return response()->json(['error' => 'TELEGRAM_CHAT_ID not set']);
            }

            $message = "🎉 Portfolio Admin AI is LIVE!\n\nType 'help' to see available commands!";
            $this->telegram->sendMessage($chatId, $message);

            return response()->json(['status' => 'Message sent!']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function setWebhook()
    {
        try {
            $url = env('APP_URL') . '/api/telegram/webhook';
            $response = $this->telegram->setWebhook($url);

            return response()->json([
                'status' => 'Webhook set',
                'url' => $url
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
