<?php

declare(strict_types=1);

namespace App\Core;

class ClaudeAPI
{
    private string $apiKey;
    private string $model;
    private int    $maxTokens;
    private string $apiUrl;
    private string $apiVersion;
    private string $systemPrompt;

    public function __construct()
    {
        $cfg = config('ai');
        $this->apiKey       = $cfg['api_key'];
        $this->model        = $cfg['model'];
        $this->maxTokens    = $cfg['max_tokens'];
        $this->apiUrl       = $cfg['api_url'];
        $this->apiVersion   = $cfg['version'];
        $this->systemPrompt = $cfg['system_prompt'];
    }

    // Asosiy so'rov
    public function ask(string $userMessage, ?string $systemOverride = null): array
    {
        $start = microtime(true);

        $body = json_encode([
            'model'      => $this->model,
            'max_tokens' => $this->maxTokens,
            'system'     => $systemOverride ?? $this->systemPrompt,
            'messages'   => [
                ['role' => 'user', 'content' => $userMessage]
            ],
        ]);

        $ch = curl_init($this->apiUrl);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $body,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'x-api-key: ' . $this->apiKey,
                'anthropic-version: ' . $this->apiVersion,
            ],
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $duration = (int)((microtime(true) - $start) * 1000);
        curl_close($ch);

        if ($response === false || $httpCode !== 200) {
            error_log("Claude API xato: HTTP {$httpCode}");
            return [
                'success' => false,
                'text'    => 'AI xizmati hozircha mavjud emas. Iltimos, keyinroq urinib ko\'ring.',
                'tokens'  => 0,
                'duration'=> $duration,
            ];
        }

        $data = json_decode($response, true);
        $text = $data['content'][0]['text'] ?? '';

        return [
            'success'  => true,
            'text'     => $text,
            'tokens'   => $data['usage']['input_tokens'] + $data['usage']['output_tokens'],
            'duration' => $duration,
        ];
    }

    // Mashq bajarishda yordam
    public function help(string $exerciseTitle, string $userQuestion): array
    {
        $cfg    = config('ai');
        $prompt = sprintf($cfg['help_prompt_template'], $exerciseTitle, $userQuestion);
        return $this->ask($prompt);
    }

    // Javobni baholash (JSON qaytaradi)
    public function review(string $exerciseTitle, string $submission): array
    {
        $cfg    = config('ai');
        $prompt = sprintf($cfg['review_prompt_template'], $exerciseTitle, $submission);
        $result = $this->ask($prompt);

        if (!$result['success']) return $result;

        // JSON parse qilish
        try {
            $json = json_decode($result['text'], true, 512, JSON_THROW_ON_ERROR);
            $result['review'] = [
                'score'        => max(0, min(100, (int)($json['score'] ?? 0))),
                'feedback'     => $json['feedback'] ?? '',
                'strengths'    => $json['strengths'] ?? [],
                'improvements' => $json['improvements'] ?? [],
            ];
        } catch (\JsonException $e) {
            // JSON parse bo'lmasa, oddiy matn sifatida qaytarish
            $result['review'] = [
                'score'        => 50,
                'feedback'     => $result['text'],
                'strengths'    => [],
                'improvements' => [],
            ];
        }

        return $result;
    }
}
