<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class USBService
{
    protected string $host;

    public function __construct()
    {
        $this->host = env('CLIENT_HOST', '127.0.0.1');
    }
    protected int $port = 9000;           

    private function sendCommand(string $command, array $payload = []): array
    { 
        $socket = @fsockopen($this->host, $this->port, $errno, $errstr, 10);
        if (!$socket) {
            Log::error("USB-001: Connection failed: $errstr ($errno)");
            return []; 
        }

        $message = json_encode(['command' => $command, 'payload' => $payload]);
        fwrite($socket, $message . "\n");

        $response = fgets($socket);
        fclose($socket);

        if (!$response) {
            Log::error("USB-002: Empty response from USB Agent");
            return [
            'success' => false,
            'message' => 'Empty response from USB Agent'
            ];
        }

        $decoded = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error("USB-003: Invalid JSON from USB Agent: " . json_last_error_msg());
            return [
            'success' => false,
            'message' => 'Invalid JSON from USB Agent'
            ];
        }

        return $decoded;
    }

    public function listFiles(): array
    {
        return $this->sendCommand('list');
    }

public function uploadFile($file): array
{
    if (!$file || !$file->isValid()) {
        Log::error("USB-004: File does not exist or is invalid");
        return [
            'success' => false,
            'message' => 'File does not exist or is invalid'
        ];
    }

    $maxSize = 5 * 1024 * 1024; // 5MB
    if ($file->getSize() > $maxSize) {
        Log::error("USB-005: File exceeds maximum allowed size ({$file->getSize()} bytes)");
        return [
            'success' => false,
            'message' => 'File size exceeds the maximum allowed limit'
        ];
    }

    try {
        $contents = base64_encode(file_get_contents($file->getRealPath()));
        $response = $this->sendCommand('upload', [
            'filename' => $file->getClientOriginalName(),
            'content'  => $contents
        ]);

        return $response;
    } catch (Exception $e) {
        Log::error("USB-006: Error uploading file: " . $e->getMessage());
        return [
            'success' => false,
            'message' => 'Error uploading file',
            'error' => $e->getMessage()
        ];
    }
}

    public function downloadFile(string $filename): array
    {
        return $this->sendCommand('download', ['filename' => $filename]);
    }
}
