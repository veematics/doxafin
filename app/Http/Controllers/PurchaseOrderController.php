<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Helpers\FeatureAccess;
use App\Http\Controllers\RequestChangeController;
use App\Mail\PurchaseOrderApprovalRequest;
Use App\Helpers\MailHelper;
use App\Models\InboxMessage;

class PurchaseOrderController extends Controller
{
    protected $debug = 1; // Add this line - set to 1 to enable logging, 0 to disable

    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 20);
        $search = $request->input('search');
        
        $query = PurchaseOrder::with('client');
        
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
        if ($this->debug) {
            \Log::info('Store method called with data:', $request->all());
        }

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
    
            if ($this->debug) {
                \Log::info('Validation passed', $validated);
            }
    
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
                            $filename = pathinfo($originalName, PATHINFO_FILENAME);
                            
                            // Check if file exists and append timestamp if needed
                            $path = $file->storeAs(
                                'purchase_orders/files',
                                $filename . '_' . time() . '.' . $extension,
                                'public'
                            );
                            
                            $filesData[] = [
                                'file' => $path,
                                'original_name' => $originalName,
                                'notes' => $note
                            ];
                        }
                    }
                }
        
                if ($this->debug) {
                    \Log::info('Files processed', $filesData);
                }
                
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
                    'created_by' => auth()->id() // This will manually set the creator
                ]);
    
                if ($this->debug) {
                    \Log::info('Purchase order created', $purchaseOrder->toArray());
                }
                
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
        
            if ($this->debug) {
                \Log::info('Transaction completed successfully');
            }
    
            if (!$purchaseOrder) {
                throw new \Exception('Failed to create purchase order');
            }
    
            return redirect()
                ->route('purchase-orders.index',  ['po' => $purchaseOrder->poNo, 'status' => 'Draft','valid'=>'1'])
                ->with('success', 'Purchase Order created successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($this->debug) {
                \Log::error('Validation failed: ' . $e->getMessage(), $e->errors());
            }
            return back()
                ->withErrors($e->errors())
                ->withInput($request->all()); // Preserve all input data
        } catch (\Exception $e) {
            if ($this->debug) {
                \Log::error('Error creating purchase order: ' . $e->getMessage());
            }
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
        $purchaseOrder->load(['client', 'serviceItems']);
    
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
        $statuses = PurchaseOrder::$poStatus ?? ['Draft', 'Pending', 'Approved', 'Rejected'];
    
        return view('purchase-orders.show', compact('purchaseOrder', 'clients', 'statuses'));
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
        if ($this->debug) {
            \Log::info('Starting approval request for PO: ' . $purchaseOrder->poNo);
        }
       
        $userId = auth()->id();
        $userName = auth()->user()->name;
        $featureId = FeatureAccess::getFeatureID('Purchase Order');
        $clientName = $purchaseOrder->client->company_name;
        $rcCategory="Purchase Order";
        
        $canedit=FeatureAccess::canEditById($userId, $featureId);
      
        // a. Check can_edit permission
        if (!$canedit) {
            if ($this->debug) {
                \Log::warning('User lacks edit permission for PO approval', ['user_id' => $userId]);
            }
            abort(403, 'Unauthorized action');
        }
        
        // b. Validate if current poStatus is 'Draft'
        if ($purchaseOrder->poStatus !== 'Draft') {
            if ($this->debug) {
                \Log::warning('Invalid PO status for approval request', 
                    ['current_status' => $purchaseOrder->poStatus, 'expected_status' => 'Draft']);
            }
            abort(403, 'Invalid Status for approval request');
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
        
        if ($this->debug) {
            \Log::info('Approval users found', $approvalUsers->toArray());
        }   else{
            \Log::info('Approval users not found');
        }
     
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
                    'before' => 'Draft',
                    'after' => 'Request Approval'
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
}


