<?php

namespace App\Enums\Files;


/**
 * This enum will map all of the appropriate model classes to internal references.
 */
enum FileType: string
{
    case Image = "Image";
    case Invoice = "Invoice";
    case Quote = "Quote";
    case Slick = 'Slick';
    case Audio = 'Audio';
    case Document = 'Document';

    /**
     * Return the storage bucket for where these types of files go.
     * @return string
     */
    public function location(): string
    {
        return match ($this)
        {
            FileType::Invoice  => 'invoices',
            FileType::Quote    => 'quotes',
            FileType::Image    => 'images',
            FileType::Slick    => 'slicks',
            FileType::Audio    => 'audio',
            FileType::Document => 'documents',
        };
    }

    /**
     * This is the property on the model where this file is stored. This will be used
     * to match in our file explorer how to
     * @return string
     */
    public function property(): string
    {
        return match ($this)
        {
            default => 'lo_file_id'
        };
    }

    /**
     * Based on the type of file we were given, take in the mime type
     * and return if this object is allowed or not.
     * @return array
     */
    public function allowedTypes(): array
    {
        return match ($this)
        {
            FileType::Image                                                         => MimeRegistry::Images->list(),
            FileType::Quote, FileType::Invoice, FileType::Slick, FileType::Document => MimeRegistry::Documents->list(),
            FileType::Audio                                                         => MimeRegistry::Audio->list(),
            default                                                                 => []
        };
    }

    /**
     * Optionally define the max size for each type of file.
     * @return int
     */
    public function maxSize(): int
    {
        return match ($this)
        {
            FileType::Image => 30000000,
            default         => 100000000     // 100 MB is the max limit set for a file by default.
        };
    }
}
