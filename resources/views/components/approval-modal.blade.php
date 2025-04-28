<div class="modal fade" id="approvalModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Approval</h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Once submitted for approval, You will not be able to edit this item directly. Changes must be made through the "Change Request" feature. Are you sure you want to proceed?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="approveButton">Submit for Approval</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
        <script>
            function confirmApproval(event, id, routeName = 'approval.submit') {
                event.preventDefault();
                const modal = new coreui.Modal(document.getElementById('approvalModal'));
                
                document.getElementById('approveButton').onclick = function() {
                    window.location.href = routeName;
                };
                
                modal.show();
            }
        </script>
    @endpush

{{-- 
Developer Guide:

1. Include this component in your blade file:
   @component('components.approval-modal')
   @endcomponent

2. Implementation Options:
   - For buttons: Add data attributes to trigger modal
     <button type="button" class="btn btn-primary" 
       data-coreui-toggle="modal" 
       data-coreui-target="#approvalModal"
       data-item-id="{{ $item->id }}">
       Submit for Approval
     </button>

   - For links: Add click handler with preventDefault
     <a href="#" class="btn btn-link" onclick="confirmApproval(event, {{ $item->id }})">
       Submit for Approval
     </a>

3. JavaScript Setup:
   // Set up approval submission handler
   document.getElementById('approveButton').onclick = function() {
       const itemId = document.querySelector('[data-coreui-target="#approvalModal"]').dataset.itemId;
       const routeName = document.querySelector('[data-coreui-target="#approvalModal"]').dataset.route || 'approval.submit';
       window.location.href = route(routeName, { id: itemId });
   };

   // Or use this function for link implementation
   function confirmApproval(event, itemId, routeName = 'approval.submit') {
       event.preventDefault();
       const modal = new coreui.Modal(document.getElementById('approvalModal'));
       document.getElementById('approveButton').onclick = function() {
           window.location.href = route(routeName, { id: itemId });
       };
       modal.show();
   }


--}}