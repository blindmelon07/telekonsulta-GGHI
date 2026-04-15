<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title('System Settings')]
#[Layout('layouts.admin')]
class SystemSettings extends Component
{
    use WithFileUploads;

    // Application
    #[Validate('required|string|max:100')]
    public string $appName = '';

    // Logo
    #[Validate('nullable|image|max:2048|mimes:png,jpg,jpeg,svg,webp')]
    public $logo = null;

    // PayMongo
    #[Validate('required|string|max:200')]
    public string $paymongoSecretKey = '';

    #[Validate('required|string|max:200')]
    public string $paymongoPublicKey = '';

    #[Validate('nullable|string|max:200')]
    public string $paymongoWebhookSecret = '';

    // Zoom
    #[Validate('required|string|max:200')]
    public string $zoomAccountId = '';

    #[Validate('required|string|max:200')]
    public string $zoomClientId = '';

    #[Validate('required|string|max:200')]
    public string $zoomClientSecret = '';

    public bool $maintenanceMode = false;

    public function mount(): void
    {
        $this->appName = config('app.name');
        $this->maintenanceMode = app()->isDownForMaintenance();

        $this->paymongoSecretKey = config('services.paymongo.secret_key', '');
        $this->paymongoPublicKey = config('services.paymongo.public_key', '');
        $this->paymongoWebhookSecret = config('services.paymongo.webhook_secret', '');

        $this->zoomAccountId = config('services.zoom.account_id', '');
        $this->zoomClientId = config('services.zoom.client_id', '');
        $this->zoomClientSecret = config('services.zoom.client_secret', '');
    }

    public function currentLogoUrl(): ?string
    {
        return Storage::disk('public')->exists('logo.png')
            ? Storage::disk('public')->url('logo.png')
            : null;
    }

    public function save(): void
    {
        $this->validateOnly('appName');

        $this->writeEnv([
            'APP_NAME' => $this->appName,
        ]);

        Artisan::call('config:clear');
        $this->dispatch('notify', message: 'Settings saved.', type: 'success');
    }

    public function savePaymongo(): void
    {
        $this->validateOnly('paymongoSecretKey');
        $this->validateOnly('paymongoPublicKey');
        $this->validateOnly('paymongoWebhookSecret');

        $this->writeEnv([
            'PAYMONGO_SECRET_KEY' => $this->paymongoSecretKey,
            'PAYMONGO_PUBLIC_KEY' => $this->paymongoPublicKey,
            'PAYMONGO_WEBHOOK_SECRET' => $this->paymongoWebhookSecret,
        ]);

        Artisan::call('config:clear');
        $this->dispatch('notify', message: 'PayMongo settings saved.', type: 'success');
    }

    public function saveZoom(): void
    {
        $this->validateOnly('zoomAccountId');
        $this->validateOnly('zoomClientId');
        $this->validateOnly('zoomClientSecret');

        $this->writeEnv([
            'ZOOM_ACCOUNT_ID' => $this->zoomAccountId,
            'ZOOM_CLIENT_ID' => $this->zoomClientId,
            'ZOOM_CLIENT_SECRET' => $this->zoomClientSecret,
        ]);

        // Clear cached Zoom access token so new credentials take effect
        \Illuminate\Support\Facades\Cache::forget('zoom_access_token');
        Artisan::call('config:clear');
        $this->dispatch('notify', message: 'Zoom settings saved.', type: 'success');
    }

    public function saveLogo(): void
    {
        $this->validateOnly('logo');

        if (! $this->logo) {
            return;
        }

        Storage::disk('public')->put('logo.png', file_get_contents($this->logo->getRealPath()));

        $this->logo = null;
        $this->dispatch('notify', message: 'Logo updated successfully.', type: 'success');
    }

    public function removeLogo(): void
    {
        Storage::disk('public')->delete('logo.png');
        $this->dispatch('notify', message: 'Logo removed.', type: 'success');
    }

    public function toggleMaintenance(): void
    {
        if (app()->isDownForMaintenance()) {
            Artisan::call('up');
            $this->maintenanceMode = false;
        } else {
            Artisan::call('down');
            $this->maintenanceMode = true;
        }
    }

    /** @param  array<string, string>  $values */
    private function writeEnv(array $values): void
    {
        $envPath = base_path('.env');
        $contents = file_get_contents($envPath);

        foreach ($values as $key => $value) {
            $escaped = preg_quote($key, '/');

            // Wrap value in quotes if it contains spaces
            $formattedValue = str_contains($value, ' ') ? '"'.$value.'"' : $value;

            if (preg_match("/^{$escaped}=/m", $contents)) {
                $contents = preg_replace(
                    "/^{$escaped}=.*/m",
                    "{$key}={$formattedValue}",
                    $contents
                );
            } else {
                $contents .= PHP_EOL."{$key}={$formattedValue}";
            }
        }

        file_put_contents($envPath, $contents);
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.admin.system-settings', [
            'currentLogoUrl' => $this->currentLogoUrl(),
        ]);
    }
}
