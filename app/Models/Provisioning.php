<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property mixed $id
 * @property mixed $account
 * @property mixed $order
 * @property mixed $extensions
 * @property mixed $dids
 * @property mixed $extensionsArray
 */
class Provisioning extends Model
{
    protected $guarded = ['id'];
    public    $dates   = ['install_date'];

    /**
     * A provisioning order belongs to an account
     * @return BelongsTo
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * A provisoning order belongs to a master order
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get name attribute for activity widget
     * @return string
     */
    public function getNameAttribute(): string
    {
        return "#$this->id for {$this->account->name}";
    }

    /**
     * Get extensions array
     * @return array
     */
    public function getExtensionsArrayAttribute(): array
    {
        $x = json_decode($this->extensions);
        if (!$x) return [];
        if (!is_array($x)) return (array)$x;
        return $x;
    }


    /**
     * Get tn array
     * @return array
     */
    public function getTnArrayAttribute(): array
    {
        $x = json_decode($this->dids);
        if (!$x) return [];
        if (!is_array($x)) return (array)$x;
        return $x;
    }

    /**
     * Add an extension to the extension array
     * @param string      $ext
     * @param string      $extname
     * @param string|null $mac
     * @return void
     */
    public function addExtension(string $ext, string $extname, ?string $mac = null): void
    {
        $arr = $this->getExtensionsArrayAttribute();
        $arr[] = (object)[
            'ext'  => $ext,
            'name' => $extname,
            'mac'  => $mac
        ];
        $this->update(['extensions' => json_encode($arr)]);
    }

    /**
     * Add telephone number to array
     * @param string $number
     * @param string $desc
     * @return void
     */
    public function addTN(string $number, string $desc): void
    {
        $arr = $this->getTnArrayAttribute();
        $arr[] = (object)[
            'number'      => $number,
            'description' => $desc
        ];
        $this->update(['dids' => json_encode($arr)]);
    }

    /**
     * Remove an extension
     * @param int $idx
     * @return void
     */
    public function removeExtension(int $idx): void
    {
        $extArray = $this->getExtensionsArrayAttribute();
        unset($extArray[$idx]);
        $this->update(['extensions' => json_encode($extArray)]);
    }

    /**
     * Remove telephone number.
     * @param int $idx
     * @return void
     */
    public function removeTN(int $idx)
    {
        $extArray = $this->getTnArrayAttribute();
        unset($extArray[$idx]);
        $this->update(['dids' => json_encode($extArray)]);
    }


}
