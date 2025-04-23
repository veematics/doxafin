<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use App\Helpers\FeatureAccess;

class MediaController extends Controller
{
    public function show($encodedPath)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            abort(403);
        }

        try {
            // Decode the base64 encoded path
            $path = base64_decode($encodedPath, true);
            
            // Check if base64 decode was successful
            if ($path === false) {
                abort(400, 'Invalid file path encoding');
            }

            // Validate the decoded path
            if (!preg_match('/^[\w\/\-\.]+$/', $path)) {
                abort(400, 'Invalid file path format');
            }
        
            // Verify file exists
            if (!Storage::exists($path)) {
                abort(404);
            }
            
            // For purchase order files, check if user has permission to access
            if (strpos($path, 'purchase_orders/files') !== false) {
                // Check if user has permission to view purchase orders using FeatureAccess helper
                $permission = FeatureAccess::check(auth()->id(), 'Purchase Orders', 'can_view');
                if ($permission != 1) {
                    abort(403, 'You do not have permission to access purchase order files.');
                }
            }

            // Get file content and mime type
            $file = Storage::get($path);
            $type = Storage::mimeType($path);

            // Return response with proper headers
            return new Response($file, 200, [
                'Content-Type' => $type,
                'Content-Disposition' => 'inline; filename="'.basename($path).'"'
            ]);
        } catch (\Exception $e) {
            abort(500, 'Error processing file: ' . $e->getMessage());
        }
    }
 }
