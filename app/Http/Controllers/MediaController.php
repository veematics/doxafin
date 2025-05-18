<?php

namespace App\Http\Controllers;

use App\Helpers\FeatureAccess;
use App\Services\GoogleDriveManager;
use Google\Service\Exception as GoogleServiceException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;

class MediaController extends Controller
{
    public function show($encodedPath)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            abort(403);
        }

        try {
            // Decode the base64 encoded path (which is now the Google Drive file ID)
            $fileId = base64_decode($encodedPath, true);
            
            // Check if base64 decode was successful
            if ($fileId === false) {
                abort(400, 'Invalid file ID encoding');
            }

            // For purchase order files, check if user has permission to access
            if (strpos($fileId, 'purchase_orders') !== false) {
                // Check if user has permission to view purchase orders using FeatureAccess helper
                $permission = FeatureAccess::check(auth()->id(), 'Purchase Orders', 'can_view');
                if ($permission != 1) {
                    abort(403, 'You do not have permission to access purchase order files.');
                }
            }

            // Get the Google Drive service instance
            $googleDriveManager = app(GoogleDriveManager::class);

            try {
                // Get file metadata to check existence and get mime type
                $file = $googleDriveManager->getDriveService()->files->get($fileId, ['fields' => 'mimeType, name']);
                
                // Download file content
                $content = $googleDriveManager->downloadFile($fileId);

                // Return response with proper headers
                return new Response($content, 200, [
                    'Content-Type' => $file->getMimeType(),
                    'Content-Disposition' => 'inline; filename="'.$file->getName().'"'
                ]);
            } catch (\Google\Service\Exception $e) {
                if ($e->getCode() === 404) {
                    abort(404, 'File not found');
                }
                throw $e;
            }
        } catch (\Exception $e) {
            abort(500, 'Error processing file: ' . $e->getMessage());
        }
    }
 }
