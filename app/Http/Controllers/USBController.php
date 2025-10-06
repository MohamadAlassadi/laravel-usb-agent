<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\USBService;
use Exception;

class USBController extends ApiController
{
    protected USBService $usbService;

    public function __construct(USBService $usbService)
    {
        $this->usbService = $usbService;
    }

    public function listFiles()
    {
        try {
            $files = $this->usbService->listFiles();
            if (!$files) {
                return $this->errorResponse('USB-001', 'No file founded', 404);
            }
            return $this->successResponse(['files' => $files], 'Files listed successfully');
        } catch (Exception $e) {
            Log::error('USB-001: Error listing files', ['error' => $e->getMessage()]);
            return $this->errorResponse('USB-001',' Error listing files', 400);
        }
    }

    public function uploadFile(Request $request)
    {
        try {
            if (!$request->hasFile('file')) {
                return $this->errorResponse('USB-002', 'No file uploaded', 404);
            }

            $result = $this->usbService->uploadFile($request->file('file'));
            return $this->successResponse($result, 'File uploaded successfully');
        } catch (Exception $e) {
            Log::error('USB-002: Error uploading file', ['error' => $e->getMessage()]);
            return $this->errorResponse('USB-002', 'Error uploading file', 400);
        }
    }

    public function downloadFile($filename)
    {
        try {
            $result = $this->usbService->downloadFile($filename);

            if (isset($result['content'])) {
                return response(base64_decode($result['content']))
                    ->header('Content-Type', 'application/octet-stream')
                    ->header('Content-Disposition', "attachment; filename=\"$filename\"");
            }

            return $this->successResponse($result, 'File downloaded successfully');
        } catch (Exception $e) {
            Log::error('USB-003: Error downloading file', ['error' => $e->getMessage()]);
            return $this->errorResponse('USB-003','Error downloading file', 400);
        }
    }
}
