<?php

namespace App\Http\Controllers\Shop;

use App\Enums\Core\CommKey;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\BillCategory;
use App\Models\BillItem;
use App\Models\Quote;
use App\Observers\BillItemObserver;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ShopController extends Controller
{
    /**
     * Show main index.
     * @return View|RedirectResponse
     */
    public function index(): View|RedirectResponse
    {
        if (!Account::count())
        {
            return redirect()->to("/install");
        }
        seo()
            ->title("Shop for " . setting('brand.name') . " Products and Services")
            ->description(Str::limit(setting('shop.info'), 50))
            ->twitter()
            ->tag('og:url', sprintf("%s", setting('brand.url')));
        if (setting('brandImage.light') && _file((int)setting('brandImage.light'))?->url)
        {
            seo()
                ->tag('fb:image', _file((int)setting('brandImage.light'))?->url)
                ->tag('twitter:image', _file((int)setting('brandImage.light'))?->url)
                ->image(_file((int)setting('brandImage.light'))?->url);
        }
        return view('shop.index');
    }

    /**
     * Show category items
     * @param string $catslug
     * @return View
     */
    public function showCategory(string $catslug): View
    {
        $cat = BillCategory::where('slug', $catslug)->first();
        if (!$cat) abort(404);


        seo()
            ->title("Shop for " . $cat->shop_name)
            ->description(Str::limit($cat->description, 50))
            ->tag('og:url', sprintf("%s/shop/%s", setting('brand.url'), $cat->slug))
            ->twitter();
        if ($cat->photo_id)
        {
            seo()
                ->tag('fb:image', _file($cat->photo_id)?->url)
                ->tag('twitter:image', _file($cat->photo_id)?->url)
                ->image(_file($cat->photo_id)?->url);
        }

        session([CommKey::LocalFilterSession->value => []]);
        return view('shop.category.index', ['category' => $cat]);
    }

    /**
     * Show single item view.
     * @param string $catslug
     * @param string $itemslug
     * @return View
     */
    public function showItem(string $catslug, string $itemslug): View
    {
        $cat = BillCategory::where('slug', $catslug)->first();
        if (!$cat) abort(404);
        $item = BillItem::where('slug', $itemslug)->first();
        if (!$item) abort(404);
        BillItemObserver::$running = true; // Don't update observers when changing view
        $item->update(['last_viewed' => now()]);
        seo()
            ->title("Shop for " . $item->name)
            ->description(Str::limit($item->description, 150))
            ->twitter()
            ->tag('og:url', sprintf("%s/shop/%s/%s", setting('brand.url'), $cat->slug, $item->slug));
        if ($item->photo_id && _file($item->photo_id)?->relative)
        {
            seo()
                ->tag('fb:image', _file($item->photo_id)?->url)
                ->tag('twitter:image', _file($item->photo_id)?->url)
                ->image(_file($item->photo_id)?->url);
        }
        return view('shop.category.item.index', ['category' => $cat, 'item' => $item]);
    }

    /**
     * Logout of the application
     * @return RedirectResponse
     */
    public function logout(): RedirectResponse
    {
        session()->flush();
        auth()->logout();
        return redirect()->to("/shop");
    }

    /**
     * Show prepared page
     * @param string $hash
     * @return View
     */
    public function prepared(string $hash): View
    {
        $quote = Quote::where('hash', $hash)->where('archived', 0)->first();
        return view('shop.prepared', ['quote' => $quote]);
    }

    /**
     * Download a prepared quote.
     * @param string $hash
     * @return mixed
     */
    public function downloadPrepared(string $hash): mixed
    {
        $quote = Quote::where('hash', $hash)->where('archived', 0)->first();
        if (!$quote) abort(404);
        return $quote->simplePDF();
    }

}
