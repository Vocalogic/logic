<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailTemplateController extends Controller
{
    /**
     * Show email template inventory.
     * @return View
     */
    public function index(): View
    {
        EmailTemplate::placeholders();
        return view('admin.email_templates.index');
    }

    /**
     * Show email template
     * @param EmailTemplate $template
     * @return View
     */
    public function show(EmailTemplate $template): View
    {
        return view('admin.email_templates.show')->with('template', $template);
    }

    /**
     * Update an email template.
     * @param EmailTemplate $template
     * @param Request       $request
     * @return RedirectResponse
     */
    public function update(EmailTemplate $template, Request $request): RedirectResponse
    {
        $request->validate(['body' => 'required', 'subject' => 'required']);
        $template->update([
            'subject'        => $request->subject,
            'body'           => $request->get('body'),
            'enabled'        => (bool)$request->enabled,
            'ticket_enabled' => (bool)$request->ticket_enabled
        ]);
        return redirect()->to("/admin/email_templates")->with('message', "Template Updated Successfully!");
    }

}
