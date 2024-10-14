<?php

class Bot
{
    private $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    function TelegramRequest(string $method, array $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.telegram.org/bot' . $this->apiKey . '/' . $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $response = curl_exec($ch);
        return json_decode($response);
    }

    public function sendMessage($chat_id, $text, $keyboard = null, $mrk = 'Markdown')
    {
        $params = [
            'chat_id' => $chat_id,
            'text' => $text,
            'parse_mode' => $mrk,
            'disable_web_page_preview' => true,
            'reply_markup' => $keyboard
        ];
        return $this->TelegramRequest('sendMessage', $params);
    }

    public function deleteMessages($chat_id, $message_id)
    {
        return $this->TelegramRequest('deleteMessage', [
            'chat_id'     => $chat_id,
            'message_id'  => $message_id
        ]);
    }

    public function debug($data)
    {
        $result = print_r($data, true);
        $this->sendMessage(5910225814, $result);
    }
}
