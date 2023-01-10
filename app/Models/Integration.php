<?php

namespace App\Models;

use App\Enums\Core\IntegrationRegistry;
use App\Enums\Core\IntegrationType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * @property mixed $ident
 * @property mixed $data
 * @property mixed $getImplementation
 * @property mixed $unpacked
 */
class Integration extends Model
{
    protected $guarded = ['id'];
    public    $casts   = ['data' => 'json'];


    /**
     * Initialize our Integrations
     * @return void
     */
    static public function init():void
    {
        foreach (IntegrationRegistry::cases() as $case)
        {
            $class = $case->getIntegration();
            info("Launching $class");
            $x = new $class();
        }
    }

    /**
     * Get registry by category.
     * @param IntegrationType $type
     * @return array
     */
    static public function byCategory(IntegrationType $type) : array
    {
        $array = [];
        foreach (IntegrationRegistry::cases() as $case)
        {
            if ($case->getCategory() == $type)
            {
                $i = self::where('ident', $case->value)->first();
                if (!$i) continue;
                $array[] = $i;
            }
        }
        return $array;
    }

    /**
     * A property to connect into the integration class.
     * @return mixed
     */
    public function getConnectAttribute():mixed
    {
        $id = IntegrationRegistry::tryFrom($this->ident);
        $cl = $id->getIntegration();
        return new $cl;
    }

    /**
     * Based on the ident get the integration from the registry
     * and return the implementing class.
     * @return string
     */
    public function getImplementation(): string
    {
        $id = IntegrationRegistry::tryFrom($this->ident);
        return $id->getIntegration();
    }

    /**
     * Get requirements for an implementation.
     * @return array
     */
    public function getRequirements(): array
    {
        $class = $this->getImplementation();
        $c = new $class();
        return $c->getRequired();
    }
    /**
     * Unpacks the JSON and sets default values if not found.
     *
     * @return object
     */
    public function getUnpackedAttribute(): object
    {
        $data = (object) $this->data;
        foreach ($this->getRequirements() as $req)
        {
            $data->{$req->var} = $data->{$req->var} ?? $req->default;
        }
        return $data;
    }

    /**
     * Pack all request vars into the config
     * @param Request $request
     * @return void
     */
    public function pack(Request $request):void
    {
        $data = $this->unpacked;
        foreach (get_object_vars($data) as $k => $i)
        {
            $data->{$k} = $request->get($k);
        }
        $this->update(['data' => $data]);
    }

    /**
     * Set a requirement var directly.
     * @param string $key
     * @param string $value
     * @return void
     */
    public function setRequirement(string $key, string $value) : void
    {
        $data = $this->unpacked;
        $data->{$key} = $value;
        $this->update(['data' => $data]);
    }


}
