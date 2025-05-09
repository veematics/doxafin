<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider; // Correct: For extending Laravel's base ServiceProvider
use Illuminate\Support\Facades\Log;
use App\Services\GoogleDriveManager; // Your custom service class
use Google_Client;                     // From google/apiclient
use Google_Service_Drive;            // From google/apiclient
use Exception;                         // Base PHP Exception
// You'll likely need GuzzleHttp\Client if you're instantiating it for setHttpClient
use GuzzleHttp\Client as GuzzleClient; // It's good practice to alias if GuzzleHttp\Client is used


class GoogleDriveServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(GoogleDriveManager::class, function ($app) {
            // Get config for the 'google' disk from filesystems.php
            $config = $app['config']['filesystems.disks.google'] ?? null;

            if (!$config) {
                Log::critical('[GoogleDriveServiceProvider] Google disk configuration not found in filesystems.php.');
                // It's often better to throw a more specific exception type if available/appropriate
                throw new Exception('Google disk configuration not found in filesystems.php.');
            }

            $serviceAccountJsonPath = $config['serviceAccountKeyFile'] ?? null;
            $rootFolderId = $config['folderId'] ?? null;

            if (!$serviceAccountJsonPath) {
                Log::critical('[GoogleDriveServiceProvider] "serviceAccountKeyFile" not set in google disk config.');
                throw new Exception('"serviceAccountKeyFile" not set in google disk config.');
            }
            if (!$rootFolderId) {
                Log::critical('[GoogleDriveServiceProvider] "folderId" not set in google disk config.');
                throw new Exception('"folderId" not set in google disk config.');
            }

            $resolvedServiceAccountKeyFile = base_path($serviceAccountJsonPath);
            if (!file_exists($resolvedServiceAccountKeyFile)) {
                Log::critical("[GoogleDriveServiceProvider] Service account JSON file NOT FOUND at: {$resolvedServiceAccountKeyFile}");
                throw new Exception("Service account JSON file not found at: {$resolvedServiceAccountKeyFile}");
            }

            try {
                $client = new Google_Client();
                $client->setAuthConfig($resolvedServiceAccountKeyFile);

                // Updated scopes for full file management permissions
                // These scopes look good for general Drive access and file operations.
                // DRIVE_FILE grants per-file access created or opened by the app.
                // DRIVE grants broader access, including creating files, folders, managing permissions, listing, etc.
                // For a service account managing a specific folder, DRIVE is usually appropriate.
                $client->setScopes([
                    Google_Service_Drive::DRIVE_FILE, // To access files created or opened by the app.
                                                     // Also needed for some operations even with broader scopes.
                    Google_Service_Drive::DRIVE       // Full access to files and folders (respecting permissions)
                                                     // Often, just Google_Service_Drive::DRIVE is sufficient if the service
                                                     // account has been granted permissions to the target folder.
                                                     // Using both is generally safe.
                ]);

                if (config('app.env') === 'local') {
                    $certPath = "C:/laragon/etc/ssl/cacert.pem"; // Your local cert
                    Log::debug('[GoogleDriveServiceProvider] Local env: SSL cert path.', ['certPath' => $certPath]);
                    $httpClientOptions = file_exists($certPath) ? ['verify' => $certPath] : ['verify' => false];
                     if ($httpClientOptions['verify'] === false) {
                        Log::warning('[GoogleDriveServiceProvider] Local env SSL cert not found, disabling Guzzle SSL verification.');
                    }
                    // Make sure you have `use GuzzleHttp\Client as GuzzleClient;` at the top
                    $client->setHttpClient(new GuzzleClient($httpClientOptions));
                }

                $driveService = new Google_Service_Drive($client);
                Log::info('[GoogleDriveServiceProvider] Google_Service_Drive successfully instantiated.');

                // This is correct: instantiate your manager with the configured service and root folder ID
                return new GoogleDriveManager($driveService, $rootFolderId);

            } catch (Exception $e) {
                // Catching a specific Google_Exception might be better if one exists for auth/setup issues
                Log::critical("[GoogleDriveServiceProvider] Failed to initialize GoogleDriveManager: " . $e->getMessage(), ['exception' => $e]);
                throw $e; // Re-throwing allows Laravel's error handler to catch it
            }
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // This log message confirms the provider's boot method is called.
        // The registration happens in the `register` method.
        Log::info('[GoogleDriveServiceProvider] Booted. GoogleDriveManager should be registered if not deferred.');
    }
}