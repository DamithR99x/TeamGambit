<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    /**
     * Display the general settings form.
     *
     * @return \Illuminate\View\View
     */
    public function general()
    {
        $settings = [
            'store_name' => config('app.name'),
            'store_email' => config('mail.from.address'),
            'currency' => config('app.currency', 'USD'),
            'default_language' => config('app.locale'),
            'store_address' => setting('store_address'),
            'store_phone' => setting('store_phone'),
        ];
        
        return view('admin.settings.general', compact('settings'));
    }

    /**
     * Update the general settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateGeneral(Request $request)
    {
        $request->validate([
            'store_name' => 'required|string|max:255',
            'store_email' => 'required|email|max:255',
            'currency' => 'required|string|size:3',
            'default_language' => 'required|string|max:10',
            'store_address' => 'nullable|string',
            'store_phone' => 'nullable|string|max:20',
        ]);

        // Update .env file
        $this->updateEnv([
            'APP_NAME' => '"' . $request->store_name . '"',
            'MAIL_FROM_ADDRESS' => $request->store_email,
            'APP_CURRENCY' => $request->currency,
            'APP_LOCALE' => $request->default_language,
        ]);

        // Update settings in database or cache
        setting([
            'store_address' => $request->store_address,
            'store_phone' => $request->store_phone,
        ]);

        // Clear config cache
        Artisan::call('config:clear');
        
        return redirect()->route('admin.settings.general')
            ->with('success', 'General settings updated successfully.');
    }

    /**
     * Display the shipping settings form.
     *
     * @return \Illuminate\View\View
     */
    public function shipping()
    {
        $shippingZones = setting('shipping_zones', []);
        $shippingMethods = setting('shipping_methods', []);
        $countries = countries(); // This would be a helper function to get a list of countries
        
        return view('admin.settings.shipping', compact('shippingZones', 'shippingMethods', 'countries'));
    }

    /**
     * Update the shipping settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateShipping(Request $request)
    {
        $request->validate([
            'shipping_zones' => 'required|array',
            'shipping_zones.*.name' => 'required|string|max:100',
            'shipping_zones.*.countries' => 'required|array',
            'shipping_methods' => 'required|array',
            'shipping_methods.*.name' => 'required|string|max:100',
            'shipping_methods.*.cost' => 'required|numeric|min:0',
        ]);

        // Update settings
        setting([
            'shipping_zones' => $request->shipping_zones,
            'shipping_methods' => $request->shipping_methods,
        ]);
        
        return redirect()->route('admin.settings.shipping')
            ->with('success', 'Shipping settings updated successfully.');
    }

    /**
     * Display the email templates form.
     *
     * @param  string  $template
     * @return \Illuminate\View\View
     */
    public function emailTemplates($template = 'order_confirmation')
    {
        $templates = [
            'order_confirmation' => 'Order Confirmation',
            'order_status_update' => 'Order Status Update',
            'new_account' => 'New Account',
            'password_reset' => 'Password Reset',
        ];
        
        $selectedTemplate = $template;
        $templateContent = setting("email_template_{$template}", '');
        
        return view('admin.settings.email-templates', compact('templates', 'selectedTemplate', 'templateContent'));
    }

    /**
     * Update an email template.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $template
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateEmailTemplate(Request $request, $template)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        // Update template content
        setting([
            "email_template_{$template}" => $request->content,
        ]);
        
        return redirect()->route('admin.settings.email-templates', $template)
            ->with('success', 'Email template updated successfully.');
    }

    /**
     * Update the environment file.
     *
     * @param  array  $data
     * @return bool
     */
    protected function updateEnv(array $data)
    {
        $path = app()->environmentFilePath();
        $env = file_get_contents($path);

        foreach ($data as $key => $value) {
            $env = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $env);
        }

        file_put_contents($path, $env);
        
        return true;
    }
}

/**
 * Get or set a setting value.
 *
 * @param  string|array  $key
 * @param  mixed  $default
 * @return mixed
 */
function setting($key = null, $default = null)
{
    if (is_null($key)) {
        return Cache::get('settings', []);
    }

    if (is_array($key)) {
        $settings = Cache::get('settings', []);
        $settings = array_merge($settings, $key);
        Cache::forever('settings', $settings);
        return true;
    }

    $settings = Cache::get('settings', []);
    return $settings[$key] ?? $default;
}

/**
 * Get a list of countries.
 *
 * @return array
 */
function countries()
{
    return [
        'US' => 'United States',
        'CA' => 'Canada',
        'GB' => 'United Kingdom',
        'AU' => 'Australia',
        // Add more countries as needed
    ];
} 