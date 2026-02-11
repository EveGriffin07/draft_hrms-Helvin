<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;

class FaceService
{
    private string $baseUrl;
    private int $timeout = 25;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.face_api.url', ''), '/');
    }

    public function enroll(int $employeeId, array $images): array
    {
        $request = Http::timeout($this->timeout)->acceptJson();

        foreach ($images as $idx => $image) {
            /** @var UploadedFile $image */
            $request = $request->attach("images", file_get_contents($image->getRealPath()), $image->getClientOriginalName());
        }

        $response = $request->post($this->baseUrl.'/enroll', [
            'employee_id' => $employeeId,
        ]);

        if ($response->failed()) {
            return ['ok' => false, 'message' => $this->error($response)];
        }

        return [
            'ok' => true,
            'embeddings' => $response->json('embeddings', []),
        ];
    }

    public function match(int $employeeId, UploadedFile $frame): array
    {
        $request = Http::timeout($this->timeout)
            ->acceptJson()
            ->attach('image', file_get_contents($frame->getRealPath()), $frame->getClientOriginalName());

        $response = $request->post($this->baseUrl.'/match', [
            'employee_id' => $employeeId,
        ]);

        if ($response->failed()) {
            return ['ok' => false, 'message' => $this->error($response)];
        }

        return [
            'ok' => true,
            'matched' => $response->json('matched'),
            'score' => $response->json('score'),
        ];
    }

    private function error($response): string
    {
        $json = $response->json();
        if (is_array($json) && isset($json['error'])) {
            return $json['error'];
        }
        return $response->reason();
    }
}
