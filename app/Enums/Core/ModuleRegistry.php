<?php

namespace App\Enums\Core;


use Nwidart\Modules\Facades\Module;

enum ModuleRegistry: string
{
    // Define Module including feature sets included. -- TODO: Replace with Downloadable Modules


    /**
     * Return the path of where the module directory lives.
     * @return string
     */
    public function getHintPath(): string
    {
        $m = Module::find($this->value);
        return $m->getPath();
    }
    /**
     * Get status of module.
     * @return bool
     */
    public function isEnabled(): bool
    {
        $m = Module::find($this->value);
        return $m->isEnabled();
    }

    /**
     * Get App Namespace for Module
     * @return string
     */
    public function getName(): string
    {
        $m = Module::find($this->value);
        return $m->getName();
    }

    /**
     * Static caller from checking for enabled module.
     * @param string $module
     * @return bool
     */
    static public function enabled(string $module) : bool
    {
        $x = self::tryFrom($module);
        if (!$x) return false;
        return $x->isEnabled();
    }

}
