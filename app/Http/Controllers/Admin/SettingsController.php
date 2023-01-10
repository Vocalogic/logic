<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Files\FileType;
use App\Exceptions\LogicException;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Operations\Core\LoFileHandler;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    /**
     * Show settings page and preset a tab based on url clicked.
     * @param Request $request
     * @return View
     */
    public function index(Request $request) : View
    {
        $tab = $request->tab ?: 'brand';
        return view('admin.settings.index')->with('tab', $tab);
    }

    /**
     * Save Settings
     * @param Request $request
     * @return RedirectResponse
     * @throws LogicException
     */
    public function save(Request $request): RedirectResponse
    {
        foreach ($request->all() as $k => $v)
        {
            if (str_contains($k, "s_"))
            {
                $set = explode("s_", $k);
                $setting = Setting::find($set[1]);
                $setting->update(['value' => $v]);
            }
            if (str_contains($k, "sf_"))
            {
                $set = explode("sf_", $k);
                $setting = Setting::find($set[1]);
                // Our value for this is going to be the lofile id.
                $lo = new LoFileHandler();
                $lo->disableAuth();
                $file = $lo->createFromRequest($request, $k, FileType::Image, $setting->id);
                $setting->update(['value' => $file->id]);
            }
        }
        return redirect()->back()->withMessage("Settings updated successfully.");
    }
}
