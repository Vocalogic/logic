<?php

namespace App\Operations\Core;

use App\Exceptions\LogicException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

/**
 * The LogicStore class provides a permanent key store that will persist in
 * storage as logic_store.json. This provides the ability to store key value
 * pairs that need to be persisted outside of the normal session/cache stores
 * that can be erased.
 *
 * This file will contain a collection, each collection child should consist
 * of a key, value, and description. If not found it will require initialization.
 */
class LogicStore
{
    public Collection $storage;
    public string     $storageFile = "logic_storage.json";

    /**
     * Instantiate our storage object.
     */
    public function __construct()
    {
        $path = storage_path() . "/" . $this->storageFile;
        $exists = File::exists($path);
        if (!$exists)
        {
            File::put($path, null);
        }
        $file = File::get($path);
        $this->storage = collect(json_decode($file));
        $this->storage = $this->storage->keyBy('key');
    }

    /**
     * Get the current value in storage. If this key is not found
     * it will throw a LogicException saying it needs to be initialized. If you
     * are planning to use this, you should always initialize your key using the
     * init method.
     * @param string $key
     * @return mixed
     * @throws LogicException
     */
    public function get(string $key): mixed
    {
        if (!$this->exists($key)) throw new LogicException("$key does not exist in storage. Use the init() method.");
        return $this->storage->get($key);
    }

    /**
     * Store a key/value pair for the collection. Will throw a LogicException if
     * the key is not found. The init method must be called.
     * @param string $key
     * @param mixed  $value
     * @return void
     * @throws LogicException
     */
    public function store(string $key, mixed $value): void
    {
        if (!$this->exists($key)) throw new LogicException("$key does not exist in storage. Use the init() method.");
        $this->storage->mapWithKeys(function ($item, $keyTarget) use ($value, $key) {
            if ($key == $keyTarget)
            {
                $item->value = $value;
            }
        });
        $path = storage_path() . "/" . $this->storageFile;
        File::put($path, $this->storage->toJson());
    }

    /**
     * Initialize a new item in the storage collection.
     * @param string $key
     * @param mixed  $value
     * @param string $description
     * @return void
     */
    public function init(string $key, mixed $value, string $description): void
    {
        $this->storage->push([
            'key'         => $key,
            'value'       => $value,
            'description' => $description
        ]);
        $path = storage_path() . "/" . $this->storageFile;
        File::put($path, $this->storage->toJson());
    }

    /**
     * This method will check for the existing of a key. If false, you should probably
     * use the init method to initialize it.
     * @param string $key
     * @return bool
     */
    public function exists(string $key): bool
    {
        return in_array($key, $this->storage->keys()->toArray());
    }

}
