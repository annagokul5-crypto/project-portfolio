<?php

$token = "8304882644:AAEPstQPNjWMAtOYhRul4Rgj6rVlIb8asgo";

// Get updates
$url = "https://api.telegram.org/bot{$token}/getUpdates";
$response = file_get_contents($url);
$data = json_decode($response, true);

echo "=== TELEGRAM BOT CHAT ID FINDER ===\n\n";

if (!empty($data['result'])) {
    echo "✅ Messages found!\n\n";

    foreach ($data['result'] as $update) {
        if (isset($update['message'])) {
            $chatId = $update['message']['chat']['id'];
            $text = $update['message']['text'] ?? '';
            $firstName = $update['message']['chat']['first_name'] ?? '';

            echo "------------------------\n";
            echo "Chat ID: " . $chatId . "\n";
            echo "Name: " . $firstName . "\n";
            echo "Message: " . $text . "\n";
            echo "------------------------\n\n";

            echo "🎯 YOUR CHAT ID IS: " . $chatId . "\n\n";
        }
    }
} else {
    echo "❌ No messages found!\n\n";
    echo "STEPS TO FIX:\n";
    echo "1. Go to: https://t.me/gj_portfolioadmin_bot\n";
    echo "2. Click START button\n";
    echo "3. Send any message like 'Hello'\n";
    echo "4. Wait 2 seconds\n";
    echo "5. Run this script again\n";
}

?>
