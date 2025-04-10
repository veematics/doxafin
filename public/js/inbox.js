// Add this JavaScript function to your inbox.js file (create if it doesn't exist)
function openMessageDialog(messageId) {
    // Mark as read via AJAX
    $.ajax({
        url: `/inbox/${messageId}/mark-as-read`,
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                // Update UI to show message as read (remove unread indicator if needed)
                $(`#message-item-${messageId}`).removeClass('unread-message');
            }
        }
    });
    
    // Open the message dialog (using your existing code to show the dialog)
    showMessageDetails(messageId);
}

// Add this function if you don't already have one to show message details
function showMessageDetails(messageId) {
    $.ajax({
        url: `/inbox/${messageId}`,
        type: 'GET',
        success: function(response) {
            // Populate your dialog with the response content
            $('#messageDetailDialog .modal-body').html(response);
            $('#messageDetailDialog').modal('show');
        }
    });
}