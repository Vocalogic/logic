<?php

namespace App\Operations\Core;

use App\Enums\Core\AccountFileType;
use App\Enums\Core\LogSeverity;
use App\Enums\Files\FileType;
use App\Exceptions\LogicException;
use App\Models\Account;
use App\Models\FileCategory;
use App\Models\LOFile;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LoFileHandler
{
    const DISK = 'local';                                   // Define the laravel disk we are using

    protected ?Account $account     = null;                     // Linked to an account only?
    protected bool     $requireAuth = true;                     // By default we require authentication

    /**
     * Disable requiring authentication to view this file
     * @return void
     */
    public function disableAuth(): void
    {
        $this->requireAuth = false;
    }

    /**
     * Set an account for this file.
     * @param Account $account
     * @return void
     */
    public function setAccount(Account $account): void
    {
        $this->account = $account;
    }

    /**
     * Create a file from base64 encoded data.
     * @param string   $name
     * @param FileType $type
     * @param int      $id
     * @param string   $baseData
     * @param string   $mimeType
     * @return LOFile|null
     */
    public function create(string $name, FileType $type, int $id, string $baseData, string $mimeType): ?LOFile
    {
        $ext = explode(".", $name);
        if (!isset($ext[1])) $ext[1] = '.unk';               // Dont' crash, call this an unknown file ext.
        $real = sprintf("%s.%s", uniqid("LO-"), $ext[1]);    /// 1235h123.pdf
        $location = $type->location();
        $finalLocation = sprintf("%s/%s", $location, $real); // quotes/1234423.pdf
        $size = strlen(base64_decode($baseData));
        try
        {
            Storage::disk(self::DISK)->put($finalLocation, base64_decode($baseData));
        } catch (Exception $e)
        {
            _log(user(), "Unable to create file (" . $e->getMessage() . ")", LogSeverity::Error);
            return null;
        }
        return (new LOFile)->create([
            'hash'          => uniqid(),
            'filename'      => $name,
            'real'          => $real,
            'description'   => "Created " . now()->toDayDateTimeString(),
            'location'      => $type->location(),
            'type'          => $type,
            'ref_id'        => $id,
            'mime_type'     => $mimeType,
            'filesize'      => $size,
            'account_id'    => $this->account ? $this->account->id : 0,
            'auth_required' => $this->requireAuth
        ]);
    }

    /**
     * Create a new file with a request and a file key.
     * @param Request  $request
     * @param string   $key
     * @param FileType $type
     * @param int      $id
     * @return LOFile|null
     * @throws LogicException
     */
    public function createFromRequest(Request $request, string $key, FileType $type, int $id): ?LOFile
    {
        $name = $request->file($key)->getClientOriginalName();
        $ext = explode(".", $name);
        if (!isset($ext[1])) $ext[1] = '.unk';               // Dont' crash, call this an unknown file ext.
        $real = sprintf("%s.%s", uniqid("LO-"), $ext[1]);    /// 1235h123.pdf
        $location = $type->location();
        // Make sure that the mime type is allowed for the type of file requested.
        info("Trying to determine MIME from $key ($name)");
        $mime = $request->file($key)->getMimeType();
        if (sizeOf($type->allowedTypes()) && !in_array($mime, $type->allowedTypes()))
        {
            $types = implode(", ", $type->allowedTypes());
            throw new LogicException("File type is not allowed. Allowed types are: $types ($mime detected)");
        }
        $size = $request->file($key)->getSize();
        if ($size > $type->maxSize())
        {
            throw new LogicException("Unable to save. Max file size for this type of file is " . formatBytes($type->maxSize()));
        }

        try
        {
            Storage::disk(self::DISK)->putFileAs($location, $request->file($key), $real);
        } catch (Exception $e)
        {
            _log(user(), "Unable to create file (" . $e->getMessage() . ")", LogSeverity::Error);
            return null;
        }
        return (new LOFile)->create([
            'hash'          => uniqid(),
            'filename'      => $name,
            'real'          => $real,
            'description'   => "Created " . now()->toDayDateTimeString(),
            'location'      => $type->location(),
            'type'          => $type,
            'ref_id'        => $id,
            'mime_type'     => $mime,
            'filesize'      => $size,
            'account_id'    => $this->account ? $this->account->id : 0,
            'auth_required' => $this->requireAuth
        ]);
    }

    /**
     * Unlock file
     * @param LOFile $file
     * @return void
     */
    public function unlock(LOFile $file): void
    {
        $file->update(['auth_required' => 0]);
    }

    /**
     * Creates a duplicate of a file and assigns it a new name.
     * @param int $file_id
     * @param int $newRef
     * @return ?LOFile
     */
    public function duplicate(int $file_id, int $newRef): ?LOFile
    {
        // We'll just use our own create method with the data that exists in the record.
        $file = LOFile::find($file_id);
        if (!$file) return null;
        $based = base64_encode(file_get_contents(_file($file->id)->internal));
        return $this->create($file->filename, FileType::from($file->type), $newRef, $based, $file->mime_type);
    }

    /**
     * Set Related Category
     * @param LOFile       $file
     * @param FileCategory $category
     * @return void
     */
    public function setCategory(LOFile $file, FileCategory $category): void
    {
        $file->update(['file_category_id' => $category->id]);
    }

    /**
     * Remove a LogicFile safely from the filesystem.
     * @param int $id
     * @return void
     */
    public function delete(int $id): void
    {
        $file = LOFile::find($id);
        if (!$file) return;
        try
        {
            Storage::disk(self::DISK)->delete($file->location . "/" . $file->real);
        } catch (Exception)
        {
            info("Could not delete from filesystem. This is probably just an orphaned file.");
        }
        $file->delete();
    }

}
