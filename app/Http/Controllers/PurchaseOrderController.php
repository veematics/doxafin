<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\Client;
use App\Models\RequestChange;
use App\Models\CsvData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Helpers\FeatureAccess;
use App\Http\Controllers\RequestChangeController;
use App\Mail\PurchaseOrderApprovalRequest;
Use App\Helpers\MailHelper;
use App\Models\InboxMessage;
use App\Services\GoogleDriveManager;
use Illuminate\Support\Facades\Log;

class PurchaseOrderController extends Controller
{
    private $debug=1;
    protected function ensureClientFolderExists($clientId)
    {
        $googleDrive = app(GoogleDriveManager::class);
        $rootFolderId = env('GOOGLE_DRIVE_FOLDER_PO_ID');
        
        // Check if folder exists for this client
        $contents = $googleDrive->listContents($rootFolderId);
        foreach ($contents as $content) {
            if ($content['isFolder'] && $content['name'] == $clientId) {
                return $content['id'];
            }
        }
        
        // Create folder if not exists
        return $googleDrive->createFolder($clientId, $rootFolderId);
    }
    

    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 20);
        $search = $request->input('search');
        
        $query = PurchaseOrder::with('client');

        $user = Auth::user();
        $roleName = session('role_name');

        if (!str_contains($roleName, 'SA')) {
            $query->where('created_by', $user->id);
        }
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('poNo', 'like', "%{$search}%")
                  ->orWhereHas('client', function($q) use ($search) {
                      $q->where('company_name', 'like', "%{$search}%");
                  });
            });
        }
        
        $purchaseOrders = $query->paginate($perPage);
        
        return view('purchase-orders.index', compact('purchaseOrders'));
    }

    public function create()
    {
        $userId = auth()->id();
        $userName = auth()->user()->name;
        $featureId = FeatureAccess::getFeatureID('Clients');
        
  
        
        // Get user's view permission level from cache
        $permissions = Cache::get('user_permissions_' . $userId);

        if (!isset($permissions[$featureId])) {
             abort(403);
        }
        
        $viewLevel = $permissions[$featureId]->first()->can_view;
        
        // Level 0: No access
        if ($viewLevel == 0) {
           abort(403);
        }
        
        // Get clients based on view level
        if ($viewLevel == 1) {
            // Show all clients
            $clients = Client::all();
        } 
        else if ($viewLevel == 2) {
            // Get user's roles
            $userRoleIds = DB::table('role_user')
                ->where('user_id', $userId)
                ->pluck('role_id');
            
            // Get users with same roles
            $userIds = DB::table('role_user')
                ->whereIn('role_id', $userRoleIds)
                ->pluck('user_id');
            
            // Get clients assigned to users with same roles
            $clients = Client::whereIn('assign_to', $userIds)->get();
        }
        else if ($viewLevel == 3) {
            // Show only clients assigned to current user
            $clients = Client::where('assign_to', $userId)->get();
        }

        $statuses = ['Draft', 'Pending', 'Approved', 'Rejected'];
        
        return view('purchase-orders.create', compact('clients', 'statuses'));
    }

    public function store(Request $request)
    {

       
        try {
            $validated = $request->validate([
                'poNo' => 'required|string|unique:purchase_orders,poNo',
                'poClient' => 'required|exists:clients,id',
                'poTerm' => 'required|string',
                'poValue' => 'required|numeric',
                'poCurrency' => 'required|string',
                'poStartDate' => 'required|date',
                'poEndDate' => 'nullable|date|after_or_equal:poStartDate',
                'services' => 'required|array',
                'services.*.name' => 'required|string',
                'services.*.value' => 'required|numeric',
                'poFiles' => 'nullable|array',
                'fileNotes' => 'nullable|array',
                'fileNotes.*' => 'nullable|string'
            ]);
    

    
            $purchaseOrder = null;
    
            DB::transaction(function () use ($validated, $request, &$purchaseOrder) {
                // Process files and notes into JSON
                $filesData = [];
                if ($request->has('fileNotes')) {
                    foreach ($request->fileNotes as $index => $note) {
                        if ($request->hasFile("poFiles.$index")) {
                            $file = $request->file("poFiles.$index");
                            $originalName = $file->getClientOriginalName();
                            $extension = $file->getClientOriginalExtension();
                            $filename = pathinfo($originalName, PATHINFO_FILENAME). '_' . time() . '.' . $extension;
                            
                            
                            // Check if file exists and append timestamp if needed
                            $clientFolderId = $this->ensureClientFolderExists($validated['poClient']);
                            $fileId = app(GoogleDriveManager::class)->uploadFile(
                                $file->getRealPath(),
                                $filename,
                                $file->getMimeType(),
                                $clientFolderId
                            );
                            
                            $filesData[] = [
                                'file' => $fileId,
                                'original_name' => $originalName,
                                'notes' => $note,
                                'filename' => $filename
                            ];
                        }
                    }
                }

                Log::debug('Files Data before JSON encode in store method', ['filesData' => $filesData]);

                // Create default poLog entry
                $currentUser = auth()->user();
                $defaultPoLog = [
                    [
                        'Date' => now()->format('Y-m-d H:i:s'),
                        'By' => $currentUser->name . ' (User ID: ' . $currentUser->id . ')',
                        'Action' => 'Create',
                        'Notes' => 'PO Created'
                    ]
                ];

                $purchaseOrder = PurchaseOrder::create([
                    'poNo' => $validated['poNo'],
                    'poClient' => $validated['poClient'],
                    'poTerm' => $validated['poTerm'],
                    'poValue' => $validated['poValue'],
                    'poCurrency' => $validated['poCurrency'],
                    'poStartDate' => \Carbon\Carbon::createFromFormat('d-m-Y', $validated['poStartDate'])->format('Y-m-d'),
                    'poEndDate' => \Carbon\Carbon::createFromFormat('d-m-Y', $validated['poEndDate'])->format('Y-m-d'),
                    'poFiles' => json_encode($filesData),
                    'poStatus' => 'Draft',
                    'created_by' => auth()->id(), // This will manually set the creator
                    'poLog' => $defaultPoLog
                ]);
    

                
                // Save related service items
                foreach ($validated['services'] as $serviceItem) {
                    $purchaseOrder->serviceItems()->create([
                        'serviceName' => $serviceItem['name'],
                        'serviceValue' => $serviceItem['value'],
                        'is_recurring' => $serviceItem['is_recurring'] ?? 0,
                        'serviceStartDate' => \Carbon\Carbon::createFromFormat('d-m-Y', $validated['poStartDate'])->format('Y-m-d'),
                        'serviceEndDate' => \Carbon\Carbon::createFromFormat('d-m-Y', $validated['poEndDate'])->format('Y-m-d')
                    ]);
                }
            });
        

    
            if (!$purchaseOrder) {
                throw new \Exception('Failed to create purchase order');
            }
    
            return redirect()
                ->route('purchase-orders.index',  ['po' => $purchaseOrder->poNo, 'status' => 'Draft','valid'=>'1'])
                ->with('success', 'Purchase Order created successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput($request->all()); // Preserve all input data
        } catch (\Exception $e) {
            return back()
                ->withInput($request->all()) // Preserve all input data
                ->withErrors(['error' => 'An error occurred while creating the purchase order.']);
        }
    }

     /**
     * Display the specified resource.
     *
     * @param  \App\Models\PurchaseOrder  $purchaseOrder  (Route Model Binding)
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(PurchaseOrder $purchaseOrder)
    {
        // Remove debug print statement
        // print_r($purchaseOrder);
        // die();
    
        // Eager load necessary relationships
        $purchaseOrder->load(['client', 'serviceItems', 'notes', 'logs']);
    
        // Decode poFiles JSON
        $filesArray = json_decode($purchaseOrder->poFiles, true) ?? [];
        $purchaseOrder->poFiles = $filesArray;
    
        // Get clients based on user's permission level
        $userId = auth()->id();
        $userName = auth()->user()->name;
        $featureId = FeatureAccess::getFeatureID('Clients');
        $permissions = Cache::get('user_permissions_' . $userId);
    
        if (!isset($permissions[$featureId])) {
            abort(403, 'Unauthorized access');
        }
    
        $viewLevel = $permissions[$featureId]->first()->can_view;
    
        // Get clients based on view level
        $clients = $this->getClientsBasedOnViewLevel($viewLevel, $userId);
    
        // Get statuses
        $statuses = PurchaseOrder::$poStatus;

        // Load request changes data
        $requestChanges = RequestChange::where('changeable_id', $purchaseOrder->poID)
                                     ->where('changeable_type', PurchaseOrder::class)
                                     ->get();
    
        // Load request change status colors from CsvData
        $rcStatusData = CsvData::where('data_name', 'RC Status')->first();
        $rcStatusColors = [];
        if ($rcStatusData) {
            $lines = explode("\n", $rcStatusData->data_value);
            foreach ($lines as $line) {
                $parts = str_getcsv($line);
                if (count($parts) >= 2) {
                    $rcStatusColors[trim($parts[0])] = trim($parts[1]);
                }
            }
        }
          
        return view('purchase-orders.show', compact('purchaseOrder', 'clients', 'statuses', 'requestChanges', 'rcStatusColors'));
    }

    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {


        try {
            $validated = $request->validate([
                'poNo' => 'required|string|unique:purchase_orders,poNo,' . $purchaseOrder->poID . ',poID',
                'poClient' => 'required|exists:clients,id',
                'poTerm' => 'required|string',
                'poValue' => 'required|numeric',
                'poCurrency' => 'required|string',
                'poStartDate' => 'required|date',
                'poEndDate' => 'nullable|date|after_or_equal:poStartDate',
                'services' => 'required|array',
                'services.*.name' => 'required|string',
                'services.*.value' => 'required|numeric',
                'poFiles' => 'nullable|array',
                'fileNotes' => 'nullable|array',
                'fileNotes.*' => 'nullable|string'
            ]);

            // Calculate remaining budget on server-side
            $poValue = (float)$validated['poValue'];
            $totalServiceValue = 0;
            foreach ($validated['services'] as $serviceItem) {
                $totalServiceValue += (float)$serviceItem['value'];
            }

            $remainingBudget = $poValue - $totalServiceValue;

            if (abs($remainingBudget) > 0.001) { // Using a small epsilon for float comparison
                return back()->withInput()->withErrors(['error' => 'The sum of service values must be equal to the PO Value. Remaining budget: ' . number_format($remainingBudget, 2)]);
            }






            DB::transaction(function () use ($validated, $request, $purchaseOrder) {
                // Start with existing files as base
                $existingFiles = json_decode($purchaseOrder->poFiles, true) ?? [];
                $filesData = $existingFiles;
               
                // Get list of existing file IDs that should be kept (from form)
                $keepFileIds = $request->input('keepFiles', []);
               
                // Filter existing files to only keep those marked to be kept
                $filesData = array_filter($existingFiles, function($file) use ($keepFileIds) {
                    return in_array($file['file'], $keepFileIds);
                });
              
                // Re-index the array to avoid gaps
                $filesData = array_values($filesData);
               
                // Process new uploaded files
                if ($request->has('fileNotes')) {
                    foreach ($request->fileNotes as $index => $note) {
                        if ($request->hasFile("poFiles.$index")) {
                            $file = $request->file("poFiles.$index");
                            $originalName = $file->getClientOriginalName();
                            $extension = $file->getClientOriginalExtension();
                            $filename = pathinfo($originalName, PATHINFO_FILENAME). '_' . time() . '.' . $extension;
                            
                            $clientFolderId = $this->ensureClientFolderExists($validated['poClient']);
                            $fileId = app(GoogleDriveManager::class)->uploadFile(
                                $file->getRealPath(),
                                $filename,
                                $file->getMimeType(),
                                $clientFolderId
                            );
                            
                            $filesData[] = [
                                'file' => $fileId,
                                'original_name' => $originalName,
                                'notes' => $note,
                                'filename' => $filename
                            ];
                        }
                    }
                }
              //Generate List of files that need to delete in Google Drive
              

              $existingFileIds = array_column($existingFiles, 'file');
              $currentFileIds = array_column($filesData, 'file');

              $filesToDelete = array_diff($existingFileIds, $currentFileIds);
           

              Log::debug('Files to delete from Google Drive', ['purchase_order_id' => $purchaseOrder->id, 'files_to_delete_count' => count($filesToDelete), 'files_to_delete_ids' => $filesToDelete]);
              
              foreach ($filesToDelete as $fileId) {
                
                  Log::debug('Attempting to delete file from Google Drive', ['purchase_order_id' => $purchaseOrder->id, 'file_id' => $fileId]);
                  try {
                      $googleDriveManager = app(GoogleDriveManager::class);
                      
                      $deleteResult = $googleDriveManager->deleteFile($fileId);
                      Log::debug('File deletion result', ['purchase_order_id' => $purchaseOrder->id, 'file_id' => $fileId, 'result' => $deleteResult]);
                  } catch (\Exception $e) {
                      Log::error('Error deleting file from Google Drive', ['purchase_order_id' => $purchaseOrder->id, 'file_id' => $fileId, 'error' => $e->getMessage()]);
                  }
              }
                Log::debug('Files Data before JSON encode in update method', ['filesData' => $filesData]);

                $purchaseOrder->update([
                    'poNo' => $validated['poNo'],
                    'poClient' => $validated['poClient'],
                    'poTerm' => $validated['poTerm'],
                    'poValue' => $validated['poValue'],
                    'poCurrency' => $validated['poCurrency'],
                    'poStartDate' => \Carbon\Carbon::parse($validated['poStartDate'])->format('Y-m-d'),
                    'poEndDate' => \Carbon\Carbon::parse($validated['poEndDate'])->format('Y-m-d'),
                    'poFiles' => json_encode($filesData),
                ]);


                // Sync service items
                $purchaseOrder->serviceItems()->delete(); // Remove existing service items
                foreach ($validated['services'] as $serviceItem) {
                    $purchaseOrder->serviceItems()->create([
                        'serviceName' => $serviceItem['name'],
                        'serviceValue' => $serviceItem['value'],
                        'is_recurring' => $serviceItem['is_recurring'] ?? 0,
                        'serviceStartDate' => \Carbon\Carbon::parse($validated['poStartDate'])->format('Y-m-d'),
                        'serviceEndDate' => \Carbon\Carbon::parse($validated['poEndDate'])->format('Y-m-d')
                    ]);
                }
            });



            return redirect()
                ->route('purchase-orders.index', ['po' => $purchaseOrder->poNo, 'status' => $purchaseOrder->poStatus, 'valid' => 1])
                ->with('success', 'Purchase Order updated successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput($request->all());
        } catch (\Exception $e) {
            return back()
                ->withInput($request->all())
                ->withErrors(['error' => 'An error occurred while updating the purchase order.']);
        }
    }

    public function edit(PurchaseOrder $purchaseOrder)
    {
        $processStatusData = \App\Models\CsvData::where('data_name', 'Process Status')->first();
        $statuses = [];
        if ($processStatusData) {
            $statuses = explode("\n", $processStatusData->data_value);
            $statuses = array_map('trim', $statuses);
            $statuses = array_filter($statuses);
        }

        return view('purchase-orders.edit', compact('purchaseOrder', 'statuses'));
    }

    private function getClientsBasedOnViewLevel($viewLevel, $userId)
    {
        switch ($viewLevel) {
            case 1:
                return Client::orderBy('company_name')->get();
            case 2:
                $userRoleIds = DB::table('role_user')->where('user_id', $userId)->pluck('role_id');
                $userIds = DB::table('role_user')->whereIn('role_id', $userRoleIds)->distinct()->pluck('user_id');
                return Client::whereIn('assign_to', $userIds)->orderBy('company_name')->get();
            case 3:
                return Client::where('assign_to', $userId)->orderBy('company_name')->get();
            default:
                return collect();
        }
    }

    public function checkPoUnique(Request $request)
    {
        $exists = PurchaseOrder::where('poNo', $request->poNo)->exists();

        return response()->json(['exists' => $exists]);
    }

    public function services(PurchaseOrder $purchaseOrder)
    {
        return response()->json([
            'poNo' => $purchaseOrder->poNo,
            'poCurrency' => $purchaseOrder->poCurrency,
            'services' => $purchaseOrder->serviceItems->map(function($item) {
                return [
                    'serviceName' => $item->serviceName,
                    'value' => $item->serviceValue,
                    'is_recurring' => $item->is_recurring
                ];
            })
        ]);
    }

    public function approvalRequest(Request $request, PurchaseOrder $purchaseOrder)
    {
       
       
        // Step 1 - Initiate approval request

       
        $userId = auth()->id();
        $userName = auth()->user()->name;
        $featureId = FeatureAccess::getFeatureID('Purchase Order');
        $clientName = $purchaseOrder->client->company_name;
        $rcCategory="Purchase Order";
        
        $canedit=FeatureAccess::canEditById($userId, $featureId);
      
        
        
        // b. Validate if current poStatus is 'Draft'
        if ($purchaseOrder->poStatus !== 'Draft') {
            abort(403, 'Invalid Status for approval request');
        }else{
            //Check if $userId==created_by or session("role_name") contain "SA" 
            if($userId!=$purchaseOrder->created_by && strpos(session("role_name"),"SA")===false){
                abort(403, 'Unauthorized action: PO is not belong to you');
            }
        }
        
        // Step 2 - Find approval users and Email
        $approvalUsers = FeatureAccess::findApprovalUsers($featureId);
        $approvalUrl = route('purchase-orders.approve', ['po' => $purchaseOrder->poNo]);
        $subject = 'PO Approval Request #'.$purchaseOrder->poNo." By ".$userName;


        $inbox_title = "PO#".$purchaseOrder->poNo." Approval Request by ".$userName;
        $inbox_message= "Client: ".$clientName."\nClick <a href='".$approvalUrl."'>here</a> to view the PO#".$purchaseOrder->poNo." Approval Request by ".$userName;
        $inbox_message_category= "system";
        $inbox_sent_from=1; // 1=system, 1=user
        $inbox_priority_status=3;
        


     
        // Step 2b - Send email notification and internal notification
        if ($approvalUsers->count() > 0) {
            
         
            
            foreach ($approvalUsers as $approver) {
                // Render email template
                $message = view('emails.purchase-orders.approval-request', [
                    'purchaseOrder' => $purchaseOrder,
                    'approvalUrl' => $approvalUrl,
                    'requesterName' => auth()->user()->name
                ])->render();
                
                // Send email using MailHelper
                MailHelper::sendEmail(
                    $approver->name,
                    $approver->email,
                    $subject,
                    $message,
                    'html'
                );

                //Send internal notification
                InboxMessage::create([
                    'sent_to' => $approver->id,
                    'subject' => $inbox_title,
                    'message' => $inbox_message,
                    'message_category' => $inbox_message_category,
                    'sent_from' => $inbox_sent_from,
                    'priority_status' => $inbox_priority_status,
                    'is_read' => false
                ]);
            }
        } else {
            \Log::warning('No approval users found for Purchase Order: ' . $purchaseOrder->poNo);
        }
        
      
 
       
        
        
        // Step 3 - Prepare JSON data for change tracking
        
        $changeData = [
            'changeable_type' => 'App\Models\PurchaseOrder',
            'featureId' => $featureId,
            'title' => 'PO#'.$purchaseOrder->poNo." Approval Request by ".$userName,   
            'notes' => $inbox_message,
            'category'=>$rcCategory,
            'table' => 'purchase_orders',
            'client_id' => $purchaseOrder->poClient,         
            'idField' => 'poID',
            'changeable_id' => $purchaseOrder->poID,
            'changeable_code' => $purchaseOrder->poNo,
            'user_id' => $userId,
            'created_by'=>$userId,
            'changes' => json_encode([
                [
                    'label' => 'Status',
                    'field' => 'poStatus',
                    'before' => 'Request Approval',
                    'after' => 'Approved'
                ]
            ])
        ];

        if ($this->debug) {
            \Log::info('Change data prepared', $changeData);
        }
       
         // Step 3 - Store Change Tracking
         $requestChangeController = new RequestChangeController();
         $requestChangeController->storeDB(new Request($changeData));
         if ($this->debug) {
             \Log::info('Change tracking stored successfully');
         }

         
         // Step 4- Store New Status
        $purchaseOrder->update([
            'poStatus' => 'Request Approval',
        ]);

        if ($this->debug) {
            \Log::info('PO status updated successfully');
        }
       
     
        return redirect()
            ->route('purchase-orders.index', ['po' => $purchaseOrder->poNo, 'status' => $purchaseOrder->poStatus, 'valid' => 1])
            ->with('success', 'Purchase Order status updated successfully');
    }

    public function approve(PurchaseOrder $po)
    {
        $userId = auth()->id();
        $userName = auth()->user()->name;
        $featureId = FeatureAccess::getFeatureID('Purchase Order');
        
        // Check if user has approval permission
        if (!FeatureAccess::canApproveById($userId, $featureId)) {
            if ($this->debug) {
                \Log::warning('User lacks approval permission', ['user_id' => $userId]);
            }
            abort(403, 'Unauthorized action');
        }

        // Validate current status
        if ($po->poStatus !== 'Request Approval') {
            if ($this->debug) {
                \Log::warning('Invalid PO status for approval', 
                    ['current_status' => $po->poStatus, 'expected_status' => 'Request Approval']);
            }
            return back()->with('error', 'Invalid Purchase Order status for approval');
        }

        // Prepare change tracking data
        $changeData = [
            'controller' => 'PurchaseOrderController',
            'title' => 'PO#'.$po->poNo." Approved by ".$userName,
            'table' => 'purchase_orders',
            'idField' => 'poID',
            'id' => $po->poID,
            'user_id' => $userId,
            'changes' => [
                [
                    'label' => 'Status',
                    'field' => 'poStatus',
                    'before' => 'Request Approval',
                    'after' => 'Approved'
                ]
            ]
        ];

        // Store change tracking
        $requestChangeController = new RequestChangeController();
        $requestChangeController->store(new Request($changeData));

        // Update PO status
        $po->update([
            'poStatus' => 'Approved',
            'approved_by' => $userId,
            'approved_at' => now()
        ]);

        if ($this->debug) {
            \Log::info('Purchase Order approved successfully', ['po_no' => $po->poNo]);
        }

        return redirect()
            ->route('purchase-orders.index', ['po' => $po->poNo, 'status' => $po->poStatus, 'valid' => 1])
            ->with('success', 'Purchase Order has been approved successfully');
    }

    public function destroy(PurchaseOrder $purchaseOrder)
    {
        if (!$purchaseOrder->canDelete()) {
            $errorMessage = '';
            
            if ($purchaseOrder->invoices()->count() > 0) {
                $errorMessage = 'Cannot delete Purchase Order: There are invoices associated with this PO.';
            } elseif ($purchaseOrder->serviceItems()->count() > 0) {
                $errorMessage = 'Cannot delete Purchase Order: There are services attached to this PO.';
            }

            return back()->with('error', $errorMessage);
        }

        $purchaseOrder->delete();
        return redirect()->route('purchase-orders.index')->with('success', 'Purchase Order deleted successfully.');
    }

    public function requestChange(PurchaseOrder $purchaseOrder, $requestChangeId)
    {
       
        $requestChangeController = new RequestChangeController();
        $requestChange = $requestChangeController->getById($requestChangeId);
        
      
        // Load necessary relationships
        $purchaseOrder->load(['client', 'serviceItems']);
        
        // Check user permissions
        $userId = auth()->id();
        $featureId = FeatureAccess::getFeatureID('Purchase Order');
        $permissions = Cache::get('user_permissions_' . $userId);
       
 
        if (!isset($permissions[$featureId])) {
            abort(403, 'Unauthorized access');
        }
        
        $canEdit = $permissions[$featureId][0]->can_edit;
        $canApprove = $permissions[$featureId][0]->can_approve;
        
        return view('purchase-orders.request-change', [
            'purchaseOrder' => $purchaseOrder,
            'requestChange' => $requestChange,
            'canEdit' => $canEdit,
            'canApprove' => $canApprove,
        ]);
    }
}


