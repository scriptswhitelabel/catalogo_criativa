<?php

class WhaticketClient {
    private $apiBase;
    private $token;
    private $defaultUserId;
    private $defaultQueueId;

    public function __construct() {
        $this->apiBase = Env::get('WHATICKET_API', 'https://apiweb.ultrawhats.com.br');
        $this->token = Env::get('WHATICKET_TOKEN', '');
        $this->defaultUserId = Env::get('WHATICKET_USER_ID', '');
        $this->defaultQueueId = Env::get('WHATICKET_QUEUE_ID', '');
    }

    public function sendMessage($numberE164, $message, $userId = null, $queueId = null, $sendSignature = false, $closeTicket = true) {
        if (empty($this->token) || empty($numberE164) || empty($message)) {
            return false;
        }

        $payload = [
            'number' => $numberE164,
            'body' => $message,
            'userId' => ($userId !== null ? $userId : $this->defaultUserId),
            'queueId' => ($queueId !== null ? $queueId : $this->defaultQueueId),
            'sendSignature' => (bool)$sendSignature,
            'closeTicket' => (bool)$closeTicket
        ];

        $url = rtrim($this->apiBase, '/') . '/api/messages/send';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->token,
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err = curl_error($ch);
        curl_close($ch);

        if ($response === false) {
            return false;
        }
        return $httpCode >= 200 && $httpCode < 300;
    }

    public function sendToMany($numbersE164, $message, $userId = null, $queueId = null, $sendSignature = false, $closeTicket = true) {
        if (!is_array($numbersE164)) { return false; }
        $allOk = true;
        foreach ($numbersE164 as $num) {
            $num = trim((string)$num);
            if ($num === '') { continue; }
            $ok = $this->sendMessage($num, $message, $userId, $queueId, $sendSignature, $closeTicket);
            if (!$ok) { $allOk = false; }
        }
        return $allOk;
    }
}

?>


