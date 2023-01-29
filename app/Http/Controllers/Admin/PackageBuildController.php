<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PackageBuild;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Contracts\View\View;

class PackageBuildController extends Controller
{
    /**
     * Show all package builders
     * @return View
     */
    public function index(): View
    {
        return view('admin.package_builds.index');
    }

    /**
     * Show create modal for new package
     * @return View
     */
    public function create(): View
    {
        return view('admin.package_builds.create', ['build' => new PackageBuild]);
    }

    /**
     * Show editor for a package.
     * @param PackageBuild $packageBuild
     * @return View
     */
    public function show(PackageBuild $packageBuild): View
    {
        return view('admin.package_builds.create', ['build' => $packageBuild]);
    }

    /**
     * Store new Package
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'        => 'required',
            'description' => "required",
        ]);
        $build = (new PackageBuild)->create([
            'name'        => $request->name,
            'description' => $request->description,
            'slug'        => Str::slug($request->name)
        ]);
        return redirect()->to("/admin/package_builds/$build->id/sections");
    }

    /**
     * Store new Package
     * @param PackageBuild $packageBuild
     * @param Request      $request
     * @return RedirectResponse
     */
    public function update(PackageBuild $packageBuild, Request $request): RedirectResponse
    {
        $request->validate([
            'name'        => 'required',
            'description' => "required"
        ]);
        $packageBuild->update([
            'name'        => $request->name,
            'description' => $request->description,
            'slug'        => Str::slug($request->name)
        ]);
        return redirect()->to("/admin/package_builds/$packageBuild->id/sections");
    }

    /**
     * Remove the entire build.
     * @param PackageBuild $packageBuild
     * @return string[]
     */
    public function destroy(PackageBuild $packageBuild) : array
    {
        foreach ($packageBuild->sections as $section)
        {
            $section->safeDelete();
        }
        $packageBuild->delete();
        session()->flash('message', "Package Build Removed");
        return ['callback' => 'reload'];
    }

}
