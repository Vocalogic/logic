<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\LogicException;
use App\Models\Setting;
use App\Operations\API\Control;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;

class MarketController
{
    /**
     * Show industries or redirect to industry selected.
     * @return View|RedirectResponse
     * @throws LogicException
     * @throws GuzzleException
     */
    public function index(): View|RedirectResponse
    {
        if (setting('industry'))
            return redirect()->to("/admin/market/" . setting('industry'));
        $c = new Control();
        $industries = $c->getIndustries();
        return view('admin.market.industries', ['industries' => $industries]);
    }

    /**
     * Remove Setting for industry
     * @return RedirectResponse
     */
    public function clear(): RedirectResponse
    {
        Setting::where('ident', 'industry')->delete();
        return redirect()->to("/admin/market");
    }
    /**
     * Set Industry
     * @param string $slug
     * @return RedirectResponse
     */
    public function setIndustry(string $slug) : RedirectResponse
    {
        setting('industry', $slug);
        return redirect()->to("/admin/market/" . setting('industry'));
    }

    /**
     * Show categories by industry.
     * @return View
     * @throws GuzzleException
     * @throws LogicException
     */
    public function categories() : View
    {
        $industry = setting('industry');
        $c = new Control();
        $categories = $c->getCategories($industry);
        return view('admin.market.categories', ['cats' => $categories, 'industry' => $industry]);
    }

    /**
     * Show Items
     * @param string $ind
     * @param string $category
     * @return View
     * @throws GuzzleException
     * @throws LogicException
     */
    public function showCategory(string $ind, string $category) : View
    {
        $industry = setting('industry');
        $c = new Control();
        $items = $c->getItems($industry, $category);
        $tags = $c->getTags($industry, $category);
        $industry = setting('industry');

        return view('admin.market.items', ['items' => $items, 'tags' => $tags, 'category' => $category, 'industry' => $industry]);
    }

    /**
     * Show individual item
     * @param string $ind
     * @param string $category
     * @param string $lid
     * @return View
     * @throws GuzzleException
     * @throws LogicException
     */
    public function showItem(string $ind, string $category, string $lid) : View
    {
        $c = new Control();
        $item = $c->getItem($lid);
        return view('admin.market.item', ['item' => $item, 'category' => $category, 'industry' => $ind]);
    }




}
