<?php

namespace App\Http\Controllers\Playground;

use App\Services\GoogleDriveManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller; // Add this import

class GDriveController extends Controller
{
    private GoogleDriveManager $googleDriveManager;

    public function __construct(GoogleDriveManager $googleDriveManager)
    {
        $this->googleDriveManager = $googleDriveManager;
    }

    public function gdrive(Request $request)
    {
        $currentPathId = $request->input('path_id', $this->googleDriveManager->getRootFolderId());
        $filesData = [];
        $pathForView = $request->input('path_label', '/');
        $parentPathId = null;
        $parentParentId = null; // Add this line to track grandparent folder

        try {
            $contents = $this->googleDriveManager->listContents($currentPathId);
            foreach ($contents as $content) {
                $filesData[] = (object) [
                    'id' => $content['id'],
                    'name' => $content['name'],
                    'path' => $content['id'], // Add this line for folder navigation
                    'path_id' => $content['id'],
                    'type' => $content['isFolder'] ? 'dir' : 'file',
                    'mimeType' => $content['mimeType'],
                    'size' => !$content['isFolder'] ? $this->formatSizeUnits($content['size'] ?? 0) : '-',
                    'modifiedTime' => $content['modifiedTime'] ? \Carbon\Carbon::parse($content['modifiedTime'])->toDateTimeString() : 'N/A'
                ];
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Could not list files: ' . $e->getMessage());
            Log::error('GDriveController gdrive method error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
        }
        
        if ($currentPathId !== $this->googleDriveManager->getRootFolderId()) {
            try {
                $currentFolderMeta = $this->googleDriveManager->getDriveService()->files->get(
                    $currentPathId, 
                    ['fields' => 'parents,name']
                );
                if ($currentFolderMeta && $currentFolderMeta->getParents()) {
                    $parentPathId = $currentFolderMeta->getParents()[0];
                    // Get parent folder name
                    $parentFolderMeta = $this->googleDriveManager->getDriveService()->files->get(
                        $parentPathId,
                        ['fields' => 'name,parents']
                    );
                    $parentPathLabel = $parentFolderMeta->getName();
                    // Get grandparent folder ID if exists
                    $parentFolderMeta = $this->googleDriveManager->getDriveService()->files->get(
                        $parentPathId,
                        ['fields' => 'parents']
                    );
                    if ($parentFolderMeta && $parentFolderMeta->getParents()) {
                        $parentParentId = $parentFolderMeta->getParents()[0];
                    }
                }
            } catch (\Exception $e) {
                Log::warning("Could not get parent for {$currentPathId}: ".$e->getMessage());
            }
        }

        return view('playground.gdrive', [
            'files' => $filesData,
            'currentPath' => $pathForView, // Change from currentPathLabel to currentPath
            'currentPathId' => $currentPathId,
            'parentPathId' => $parentPathId,
            'parentParentId' => $parentParentId,
            'rootFolderId' => $this->googleDriveManager->getRootFolderId()
        ]);
    }


    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:102400',
            'parent_folder_id' => 'nullable|string', // Changed from folder_id to parent_folder_id
        ]);

        try {
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            // Use parent_folder_id instead of folder_id
            $targetFolderId = $request->input('parent_folder_id', $this->googleDriveManager->getRootFolderId());

            $fileId = $this->googleDriveManager->uploadFile(
                $file->getRealPath(),
                $originalName,
                $file->getMimeType(),
                $targetFolderId
            );

            if ($fileId) {
                // Get the current folder name for the path label
                $folderMeta = $this->googleDriveManager->getDriveService()->files->get($targetFolderId, ['fields' => 'name,parents']);
                $currentPath = '/' . $folderMeta->getName();
                
                // If not root folder and not direct child of root, build the path without including root folder
                if ($targetFolderId !== $this->googleDriveManager->getRootFolderId() && 
                    isset($folderMeta->parents[0]) && 
                    $folderMeta->parents[0] !== $this->googleDriveManager->getRootFolderId()) {
                    $parentId = $folderMeta->getParents()[0];
                    $parentMeta = $this->googleDriveManager->getDriveService()->files->get($parentId, ['fields' => 'name,parents']);
                    // Only add parent name if it's not the root folder
                    if ($parentId !== $this->googleDriveManager->getRootFolderId()) {
                        $currentPath = '/' . $parentMeta->getName() . $currentPath;
                    }
                }
                
                // Get updated file list for the current folder
                $filesData = [];
                $contents = $this->googleDriveManager->listContents($targetFolderId);
                foreach ($contents as $content) {
                    $filesData[] = (object) [
                        'id' => $content['id'],
                        'name' => $content['name'],
                        'path' => $content['id'],
                        'path_id' => $content['id'],
                        'type' => $content['isFolder'] ? 'dir' : 'file',
                        'mimeType' => $content['mimeType'],
                        'size' => !$content['isFolder'] ? $this->formatSizeUnits($content['size'] ?? 0) : '-',
                        'modifiedTime' => $content['modifiedTime'] ? \Carbon\Carbon::parse($content['modifiedTime'])->toDateTimeString() : 'N/A'
                    ];
                }
                
                return redirect()->route('playground.gdrive.index', [
                    'path_id' => $targetFolderId,
                    'path_label' => $currentPath
                ])->with([
                    'success' => "File '{$originalName}' uploaded successfully.",
                    'files' => $filesData
                ]);

            } else {
                throw new Exception("Upload failed, file ID not returned.");
            }
        } catch (Exception $e) {
            Log::error('File upload failed directly: ', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->route('playground.gdrive.index', ['path_id' => $request->input('folder_id', $this->googleDriveManager->getRootFolderId())])
                             ->with('error', 'File upload failed: ' . $e->getMessage());
        }
    }
    
    public function download($fileId)
    {
        try {
            $fileContent = $this->googleDriveManager->downloadFile($fileId);
            if ($fileContent === null) {
                return redirect()->route('playground.gdrive.index')->with('error', 'File not found or could not be downloaded.');
            }

            // Need to get file metadata to set correct name and MIME type for download
            $fileMeta = $this->googleDriveManager->getDriveService()->files->get($fileId, ['fields' => 'name, mimeType']);
            $fileName = $fileMeta->getName();
            $mimeType = $fileMeta->getMimeType();

            // Adjust mime type for Google Docs exported as PDF etc.
            if (str_starts_with($mimeType, 'application/vnd.google-apps')) {
                 $exportMimeTypeMap = [
                    'application/vnd.google-apps.document' => 'application/pdf',
                    'application/vnd.google-apps.spreadsheet' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'application/vnd.google-apps.presentation' => 'application/pdf',
                ];
                $mimeType = $exportMimeTypeMap[$mimeType] ?? 'application/octet-stream';
                // Adjust filename extension if needed
                if ($mimeType === 'application/pdf' && !str_ends_with(strtolower($fileName), '.pdf')) $fileName .= '.pdf';
                // Add more for xlsx, pptx if you export to those
            }


            return Response::make($fileContent, 200, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            ]);

        } catch (Exception $e) {
            Log::error("File download failed for ID {$fileId}: ", ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->route('playground.gdrive.index')->with('error', 'File download failed: ' . $e->getMessage());
        }
    }
    
    public function destroy($fileOrFolderId)
    {
        try {
            $this->googleDriveManager->delete($fileOrFolderId);
            return redirect()->back()->with('success', 'Item deleted successfully.');
        } catch (\Exception $e) {
            Log::error("File deletion failed for ID {$fileOrFolderId}: ", ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'Item deletion failed: ' . $e->getMessage());
        }
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'required|string'
        ]);
    
        $successCount = 0;
        $failedCount = 0;
        $errors = [];
        
        // Get the JSON string from first array element and decode it
        $idsJson = $request->input('ids')[0];
        $ids = json_decode($idsJson, true);
        
        if (!is_array($ids)) {
            return redirect()->back()->with('error', 'Invalid IDs format');
        }
        
        foreach ($ids as $id) {
            try {
                $this->googleDriveManager->delete($id);
                $successCount++;
            } catch (\Exception $e) {
                $failedCount++;
                $errors[] = "Failed to delete item {$id}: " . $e->getMessage();
                Log::error("Bulk deletion failed for ID {$id}: ", ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            }
        }

        $message = "Successfully deleted {$successCount} item(s)";
        if ($failedCount > 0) {
            $message .= ", failed to delete {$failedCount} item(s)";
            return redirect()->back()
                ->with('warning', $message)
                ->with('errors', $errors);
        }

        return redirect()->back()->with('success', $message);
    }

    private function formatSizeUnits($bytes) // Your helper
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }
        return $bytes;
    }

    public function createFolder(Request $request)
    {
        $request->validate([
            'folder_name' => 'required|string|max:255',
            'parent_folder_id' => 'nullable|string',
        ]);

        try {
            $folderName = $request->input('folder_name');
            $parentFolderId = $request->input('parent_folder_id', $this->googleDriveManager->getRootFolderId());

            $folderId = $this->googleDriveManager->createFolder($folderName, $parentFolderId);

            if ($folderId) {
                return redirect()->route('playground.gdrive.index', [
                    'path_id' => $parentFolderId
                ])->with('success', "Folder '{$folderName}' created successfully.");
            } else {
                throw new \Exception('Folder creation failed.');
            }
        } catch (\Exception $e) {
            Log::error('Folder creation failed: ', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->route('playground.gdrive.index', [
                'path_id' => $request->input('parent_folder_id', $this->googleDriveManager->getRootFolderId())
            ])->with('error', 'Folder creation failed: ' . $e->getMessage());
        }
    }
}