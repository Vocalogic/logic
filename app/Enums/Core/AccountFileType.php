<?php

namespace App\Enums\Core;

use App\Enums\Files\FileType;

/**
 * Define the types of files that are allowed to be stored for an account.
 */
enum AccountFileType: string
{
    case Audio = 'AUDIO';
    case Documentation = 'DOCUMENT';
    case Photo = 'PHOTO';

    /**
     * Get the type of icon to use for this.
     * @return string
     */
    public function getIcon(): string
    {
        return match ($this)
        {
            self::Audio         => "fa-audio-description",
            self::Documentation => "fa-file",
            self::Photo         => "fa-image",
        };
    }

    /**
     * Get human-readable string
     * @return string
     */
    public function getHuman(): string
    {
        return match ($this)
        {
            self::Audio         => "Audio File",
            self::Documentation => "Documentation",
            self::Photo         => "Photo/Image",

        };
    }

    /**
     * Get root file type for upload
     * @return FileType
     */
    public function getRootType(): FileType
    {
        return match($this)
        {
            self::Audio => FileType::Audio,
            self::Documentation => FileType::Document,
            self::Photo => FileType::Image
        };
    }

    /**
     * Get selectable array.
     * @return array
     */
    static public function getSelectable(): array
    {
        $data = [];
        foreach (self::cases() as $case)
        {
            $data[$case->value] = $case->getHuman();
        }
        return $data;
    }

}
