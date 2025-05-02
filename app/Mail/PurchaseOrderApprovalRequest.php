<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PurchaseOrderApprovalRequest extends Mailable
{
    use Queueable, SerializesModels;

    public $poNo;
    public $approvalUrl;
    public $requesterName;

    /**
     * Create a new message instance.
     *
     * @param string $poNo
     * @param string $approvalUrl
     * @param string $requesterName
     */
    public function __construct($poNo, $approvalUrl, $requesterName)
    {
        $this->poNo = $poNo;
        $this->approvalUrl = $approvalUrl;
        $this->requesterName = $requesterName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Purchase Order Approval Request: ' . $this->poNo)
                    ->view('emails.purchase_order_approval_request')
                    ->with([
                        'poNo' => $this->poNo,
                        'approvalUrl' => $this->approvalUrl,
                        'requesterName' => $this->requesterName
                    ]);
    }
}