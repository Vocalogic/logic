<?php

namespace App\Enums\Files;

/**
 * This is a helper for getting all allowed mime types for a human readable
 * request.
 */
enum MimeRegistry
{
    case Images;
    case Documents;
    case Audio;

    /**
     * Return an array of allowed mime types.
     * @return array
     */
    public function list(): array
    {
        return match ($this)
        {
            MimeRegistry::Images    => [
                'image/jpeg',
                'image/png',
                'image/gif'
            ],
            MimeRegistry::Documents => [
                'application/pdf',
                'application/octect-stream'
            ],
            MimeRegistry::Audio     => [
                'audio/mpeg',
                'audio/mp4',
                'audio/vnd.wav',
                'audio/x-wav'
            ],
            default                 => []
        };
    }
}
