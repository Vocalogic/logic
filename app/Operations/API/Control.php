<?php

namespace App\Operations\API;

use App\Enums\Core\CommKey;
use App\Exceptions\LogicException;
use App\Models\Partner;
use GuzzleHttp\Exception\GuzzleException;

class Control extends APICore
{
    public string $base;

    /**
     * Define Control Hostname
     */
    public function __construct()
    {
        $this->base = "https://control.vocalogic.com";
        parent::__construct();
    }

    /**
     * Get list of versions, changelogs and more for dashboard.
     * @return mixed
     * @throws GuzzleException|LogicException
     */
    public function getVersions(): mixed
    {
        $url = sprintf("%s/%s", $this->base, "api/versions");
        return $this->send($url);
    }


    /**
     * Get license data
     * @return mixed
     * @throws GuzzleException|LogicException
     */
    public function getLicense(): mixed
    {
        if (!setting('brand.license')) return null;
        if (cache(CommKey::GlobalLicenseCache->value)) return cache(CommKey::GlobalLicenseCache->value);
        $license = setting('brand.license');
        $url = sprintf("%s/%s", $this->base, "api/license/$license");
        $lic = $this->send($url);
        if ($lic->success)
        {
            cache([CommKey::GlobalLicenseCache->value => $lic], CommKey::GlobalLicenseCache->getLifeTime());
        }
        return $lic;
    }

    /**
     * Get list of industries
     * @return array
     * @throws GuzzleException
     * @throws LogicException
     */
    public function getIndustries(): array
    {
        $url = sprintf("%s/%s", $this->base, "api/market/industries");
        return $this->send($url);
    }

    /**
     * Get categories by industry
     * @param string $industry
     * @return array
     * @throws GuzzleException
     * @throws LogicException
     */
    public function getCategories(string $industry): array
    {
        $url = sprintf("%s/%s", $this->base, "api/market/industries/$industry/categories");
        return $this->send($url);
    }

    /**
     * Get list of items from a category
     * @param string $industry
     * @param string $category
     * @return array
     * @throws GuzzleException
     * @throws LogicException
     */
    public function getItems(string $industry, string $category): array
    {
        $url = sprintf("%s/%s", $this->base, "api/market/industries/$industry/categories/$category/items");
        return $this->send($url);
    }

    /**
     * Get list of tags from a category
     * @param string $industry
     * @param string $category
     * @return object
     * @throws GuzzleException
     * @throws LogicException
     */
    public function getTags(string $industry, string $category): object
    {
        $url = sprintf("%s/%s", $this->base, "api/market/industries/$industry/categories/$category/tags");
        return $this->send($url);
    }

    /**
     * Get Item by LID
     * @param string $lid
     * @return object
     * @throws GuzzleException
     * @throws LogicException
     */
    public function getItem(string $lid): object
    {
        $url = sprintf("%s/%s", $this->base, "api/market/item/$lid");
        return $this->send($url);
    }

    /**
     * Use Control to Send an SMS Message for 2FA or Verification
     * @param string $target
     * @param string $message
     * @return object
     * @throws GuzzleException
     * @throws LogicException
     */
    public function sendSMS(string $target, string $message): object
    {
        $license = setting('brand.license') ?: "none";
        $message = strip_tags($message);
        $url = sprintf("%s/%s", $this->base, "api/verify");
        return $this->send($url, 'post', [
            'license' => $license,
            'target'  => $target,
            'message' => $message
        ]);
    }

    /**
     * Send a base64_encoded sitedata and db
     * @param string $site
     * @param string $db
     * @return object
     * @throws GuzzleException
     * @throws LogicException
     */
    public function submitBackup(string $site, string $db): object
    {
        $license = setting('brand.license') ?: "none";
        $url = sprintf("%s/%s", $this->base, "api/account/$license/backups");
        return $this->send($url, 'post', [
            'site_data' => $site,
            'db_data'   => $db,
        ]);
    }

    /**
     * Attempt to get partner by code.
     * @param string $code
     * @return object
     * @throws GuzzleException
     * @throws LogicException
     */
    public function getPartnerByCode(string $code): object
    {
        $license = setting('brand.license') ?: "none";
        $url = sprintf("%s/%s", $this->base, "api/account/$license/partner/$code");
        return $this->send($url);
    }
}
