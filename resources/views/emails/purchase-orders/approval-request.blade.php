<!DOCTYPE html>
<html>
<head>
    <title>Purchase Order Approval Request</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #2d3748;">Purchase Order Approval Request</h2>
        
        <p>Hello,</p>
        
        <p>A new purchase order requires your approval:</p>
        
        <div style="background-color: #f8f9fa; padding: 15px; margin: 20px 0; border-radius: 5px;">
            <p><strong>Purchase Order:</strong> #{{ $purchaseOrder->poNo }}</p>
            <p><strong>Requested By:</strong> {{ $requesterName }}</p>
            <p><strong>Client:</strong> {{ $purchaseOrder->client->company_name }}</p>
            <p><strong>Value:</strong> {{ $purchaseOrder->poCurrency }} {{ number_format($purchaseOrder->poValue, 0) }}</p>
        </div>

        <p>Please review and take action on this request by clicking the button below:</p>
        
        <div style="text-align: center; margin: 30px 0;">
           [NEED TO FIX] <a href="{{ $approvalUrl }}" style="background-color: #4299e1; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block;">Review Purchase Order</a>
        </div>

        <p style="color: #718096; font-size: 0.9em;">
            If you're unable to click the button, you can copy and paste this URL into your browser:<br>
            [NEED TO FIX] {{ $approvalUrl }}
        </p>
        
        <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 20px 0;">
        
        <p style="color: #718096; font-size: 0.8em;">
            This is an automated message. Please do not reply to this email.
        </p>
    </div>
</body>
</html>