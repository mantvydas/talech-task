<?php

namespace App\Http\Controllers;

class ChangeLocale extends Controller
{
    /**
     * Change locale globally
     *
     * @param string $locale
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(string $locale)
    {
        if (!in_array($locale, ['en', 'lt'])) {
            abort(400);
        }

        app()->setlocale($locale);
        session()->put('locale', $locale);
        return redirect()->back();
    }
}
