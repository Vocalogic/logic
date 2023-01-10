<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Files\FileType;
use App\Exceptions\LogicException;
use App\Http\Controllers\Controller;
use App\Models\BillCategory;
use App\Operations\Core\LoFileHandler;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BillItemCategoryController extends Controller
{
    /**
     * Show all categories for a particular type
     * @param string $type
     * @return View
     */
    public function index(string $type): View
    {
        return view('admin.bill_categories.index')->with('type', $type);
    }

    /**
     * Show Create Form
     * @param string $type
     * @return View
     */
    public function create(string $type): View
    {
        return view('admin.bill_categories.create')->with('type', $type)->with('cat', new BillCategory);
    }

    /**
     * Show Edit Form
     * @param string       $type
     * @param BillCategory $cat
     * @return View
     */
    public function show(string $type, BillCategory $cat): View
    {
        return view('admin.bill_categories.edit')->with('type', $type)->with('cat', $cat);
    }

    /**
     * Store the new category
     * @param string  $type
     * @param Request $request
     * @return RedirectResponse
     * @throws LogicException
     */
    public function store(string $type, Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
        ]);
        $cat = (new BillCategory)->create([
            'name'        => $request->name,
            'description' => $request->description,
            'type'        => $type,
            'shop_show'   => $request->shop_show,
            'shop_name'   => $request->shop_name,
            'slug'        => Str::slug($request->shop_name),
            'shop_offer'  => $request->shop_offer
        ]);
        if ($request->hasFile('shop_offer_image_id'))
        {
            $lo = new LoFileHandler();
            $file = $lo->createFromRequest($request, 'shop_offer_image_id', FileType::Image, $cat->id);
            $cat->update(['shop_offer_image_id' => $file->id]);
        }

        if ($request->hasFile('photo_id'))
        {
            $lo = new LoFileHandler();
            $file = $lo->createFromRequest($request, 'photo_id', FileType::Image, $cat->id);
            $cat->update(['photo_id' => $file->id]);
        }
        return redirect()->to("/admin/bill_categories/$type")->with('Message', "New Category Created!");
    }

    /**
     * Update Category
     * @param string       $type
     * @param BillCategory $cat
     * @param Request      $request
     * @return RedirectResponse
     * @throws LogicException
     */
    public function update(string $type, BillCategory $cat, Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
        ]);
        $cat->update([
            'name'        => $request->name,
            'description' => $request->description,
            'shop_show'   => $request->shop_show,
            'shop_name'   => $request->shop_name,
            'slug'        => Str::slug($request->shop_name),
            'shop_offer'  => $request->shop_offer
        ]);
        if ($request->hasFile('shop_offer_image_id'))
        {
            $lo = new LoFileHandler();
            if ($cat->shop_offer_image_id)
            {
                $lo->delete($cat->shop_offer_image_id);
            }
            $file = $lo->createFromRequest($request, 'shop_offer_image_id', FileType::Image, $cat->id);
            $lo->unlock($file);
            $cat->update(['shop_offer_image_id' => $file->id]);
        }

        if ($request->hasFile('photo_id'))
        {
            $lo = new LoFileHandler();
            if ($cat->photo_id)
            {
                $lo->delete($cat->photo_id);
            }
            $file = $lo->createFromRequest($request, 'photo_id', FileType::Image, $cat->id);
            $lo->unlock($file);
            $cat->update(['photo_id' => $file->id]);
        }
        return redirect()->to("/admin/bill_categories/$type")->with('message', "$cat->name updated!");
    }

}
