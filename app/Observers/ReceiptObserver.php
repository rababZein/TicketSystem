<?php

namespace App\Observers;

use App\Receipt;
use \Illuminate\Http\Request;
use App\Jobs\Receipt\ReceiptPaidJob;

class ReceiptObserver
{
    private $input;

    public function __construct(Request $request)
    {
        $this->input = $request->all();
    }
    /**
     * Handle the receipt "created" event.
     *
     * @param  \App\Receipt  $receipt
     * @return void
     */
    public function created(Receipt $receipt)
    {
        if ($this->input['is_paid']) {
            ReceiptPaidJob::dispatch($receipt);
        }
    }

    /**
     * Handle the receipt "updated" event.
     *
     * @param  \App\Receipt  $receipt
     * @return void
     */
    public function updated(Receipt $receipt)
    {
        if (isset($input['is_paid']) && $input['is_paid']) {
            ReceiptPaidJob::dispatch($receipt);
        }
    }

    /**
     * Handle the receipt "deleted" event.
     *
     * @param  \App\Receipt  $receipt
     * @return void
     */
    public function deleted(Receipt $receipt)
    {
        //
    }

    /**
     * Handle the receipt "restored" event.
     *
     * @param  \App\Receipt  $receipt
     * @return void
     */
    public function restored(Receipt $receipt)
    {
        //
    }

    /**
     * Handle the receipt "force deleted" event.
     *
     * @param  \App\Receipt  $receipt
     * @return void
     */
    public function forceDeleted(Receipt $receipt)
    {
        //
    }
}
