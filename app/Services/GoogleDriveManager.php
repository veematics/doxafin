<?php

namespace App\Services;

use Google_Service_Drive;
use Google_Client; // Important to use this if you pass it, or re-instantiate here
use Google_Service_Drive_DriveFile;
use Illuminate_Support_Facades_Log;
use Exception; // For throwing exceptions
use Illuminate\Support\Facades\Log;

class GoogleDriveManager
{
    private Google_Service_Drive $driveService;
    private string $rootFolderId;

    // The Google_Service_Drive instance and rootFolderId will be injected by the Service Provider
    public function __construct(Google_Service_Drive $driveService, string $rootFolderId)
    {
        $this->driveService = $driveService;
        $this->rootFolderId = $rootFolderId;
        Log::info("[GoogleDriveManager] Initialized. Root Folder ID: {$this->rootFolderId}");
    }

    /**
     * Lists files and folders within a specified Drive folder.
     * Defaults to the rootFolderId if no specific folderId is provided.
     */
    public function listContents(string $folderId = null, int $pageSize = 100): array
    {
        $targetFolderId = $folderId ?: $this->rootFolderId;
        $filesList = [];
        $pageToken = null;

        Log::debug("[GoogleDriveManager] Listing contents for folder ID: {$targetFolderId}");

        try {
            do {
                $response = $this->driveService->files->listFiles([
                    'q' => "'{$targetFolderId}' in parents and trashed=false",
                    'pageSize' => $pageSize,
                    'fields' => 'nextPageToken, files(id, name, mimeType, size, modifiedTime, webViewLink, capabilities)', // Added capabilities
                    'pageToken' => $pageToken,
                    'supportsAllDrives' => true, // Good practice
                ]);

                foreach ($response->getFiles() as $file) {
                    $filesList[] = [
                        'id' => $file->getId(),
                        'name' => $file->getName(),
                        'mimeType' => $file->getMimeType(),
                        'size' => $file->getSize(),
                        'modifiedTime' => $file->getModifiedTime(),
                        'isFolder' => $file->getMimeType() === 'application/vnd.google-apps.folder',
                        'canDownload' => $file->getCapabilities() ? $file->getCapabilities()->getCanDownload() : false,
                        'webViewLink' => $file->getWebViewLink(),
                    ];
                }
                $pageToken = $response->getNextPageToken();
            } while ($pageToken);
        } catch (Exception $e) {
            Log::error("[GoogleDriveManager] Error listing files in folder {$targetFolderId}: " . $e->getMessage());
            throw $e; // Re-throw or handle more gracefully
        }
        return $filesList;
    }

    /**
     * Uploads a file to a specified parent folder in Google Drive.
     * Defaults to the rootFolderId if no parentFolderId is provided.
     */
    public function uploadFile(string $localFilePath, string $fileName, string $mimeType, string $parentFolderId = null): ?string
    {
        $targetParentFolderId = $parentFolderId ?: $this->rootFolderId;
        if (!file_exists($localFilePath)) {
            Log::error("[GoogleDriveManager] Local file not found for upload: {$localFilePath}");
            throw new Exception("Local file not found: {$localFilePath}");
        }

        Log::debug("[GoogleDriveManager] Uploading '{$fileName}' to folder ID: {$targetParentFolderId}");

        try {
            $driveFile = new Google_Service_Drive_DriveFile([
                'name' => $fileName,
                'parents' => [$targetParentFolderId]
            ]);

            $content = file_get_contents($localFilePath);
            if ($content === false) {
                Log::error("[GoogleDriveManager] Could not read local file content: {$localFilePath}");
                throw new Exception("Could not read local file content: {$localFilePath}");
            }

            $createdFile = $this->driveService->files->create($driveFile, [
                'data' => $content,
                'mimeType' => $mimeType,
                'uploadType' => 'media',
                'supportsAllDrives' => true,
                'fields' => 'id,name' // Request only needed fields
            ]);
            Log::info("[GoogleDriveManager] File uploaded: {$createdFile->getName()} (ID: {$createdFile->getId()})");
            return $createdFile->getId();
        } catch (Exception $e) {
            Log::error("[GoogleDriveManager] Error uploading file '{$fileName}': " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Creates a new folder within a specified parent folder.
     * Defaults to the rootFolderId if no parentFolderId is provided.
     */
    public function createFolder(string $folderName, string $parentFolderId = null): ?string
    {
        $targetParentFolderId = $parentFolderId ?: $this->rootFolderId;
        Log::debug("[GoogleDriveManager] Creating folder '{$folderName}' in parent ID: {$targetParentFolderId}");

        try {
            $folderMetadata = new Google_Service_Drive_DriveFile([
                'name' => $folderName,
                'mimeType' => 'application/vnd.google-apps.folder',
                'parents' => [$targetParentFolderId]
            ]);
            $folder = $this->driveService->files->create($folderMetadata, ['fields' => 'id,name', 'supportsAllDrives' => true]);
            Log::info("[GoogleDriveManager] Folder created: {$folder->getName()} (ID: {$folder->getId()})");
            return $folder->getId();
        } catch (Exception $e) {
            Log::error("[GoogleDriveManager] Error creating folder '{$folderName}': " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Deletes a file or folder by its ID.
     */
    public function delete(string $fileOrFolderId): bool
    {
        Log::debug("[GoogleDriveManager] Deleting item ID: {$fileOrFolderId}");
        try {
            $this->driveService->files->delete($fileOrFolderId, ['supportsAllDrives' => true]);
            Log::info("[GoogleDriveManager] Item deleted: {$fileOrFolderId}");
            return true;
        } catch (Exception $e) {
            Log::error("[GoogleDriveManager] Error deleting item {$fileOrFolderId}: " . $e->getMessage());
            // Check if it's a "not found" error, which might mean it's already deleted
            if ($e instanceof \Google\Service\Exception && $e->getCode() == 404) {
                Log::warning("[GoogleDriveManager] Item {$fileOrFolderId} not found during delete, possibly already deleted.");
                return true; // Or false depending on desired behavior for "not found"
            }
            throw $e;
        }
    }

    /**
     * Downloads a file by its ID.
     * Returns file content as a string or null on error.
     */
    public function downloadFile(string $fileId): ?string
    {
        Log::debug("[GoogleDriveManager] Downloading file ID: {$fileId}");
        try {
            // Check if the file is a Google Doc type that needs export
            $file = $this->driveService->files->get($fileId, ['fields' => 'mimeType, name']);
            $googleDocsMimeTypes = [
                'application/vnd.google-apps.document',
                'application/vnd.google-apps.spreadsheet',
                'application/vnd.google-apps.presentation',
            ];

            $exportMimeTypeMap = [
                'application/vnd.google-apps.document' => 'application/pdf', // or 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                'application/vnd.google-apps.spreadsheet' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/vnd.google-apps.presentation' => 'application/pdf', // or 'application/vnd.openxmlformats-officedocument.presentationml.presentation'
            ];

            if (in_array($file->getMimeType(), $googleDocsMimeTypes)) {
                $exportMimeType = $exportMimeTypeMap[$file->getMimeType()] ?? 'application/pdf';
                Log::debug("[GoogleDriveManager] Exporting Google Doc '{$file->getName()}' (ID: {$fileId}) as {$exportMimeType}");
                $response = $this->driveService->files->export($fileId, $exportMimeType, ['alt' => 'media']);
            } else {
                Log::debug("[GoogleDriveManager] Downloading binary file '{$file->getName()}' (ID: {$fileId})");
                $response = $this->driveService->files->get($fileId, ['alt' => 'media']);
            }
            
            return $response->getBody()->getContents();

        } catch (Exception $e) {
            Log::error("[GoogleDriveManager] Error downloading file {$fileId}: " . $e->getMessage());
            throw $e;
        }
    }

    public function getRootFolderId(): string
    {
        return $this->rootFolderId;
    }

    public function getDriveService(): Google_Service_Drive
    {
        return $this->driveService;
    }
}