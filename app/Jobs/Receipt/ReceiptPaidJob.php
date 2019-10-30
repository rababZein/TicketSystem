<?php

namespace App\Jobs\Receipt;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Notifications\Receipt\ReceiptPaidNotification;
use App\Models\Receipt;

class ReceiptPaidJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $receipt;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Receipt $receipt)
    {
        $this->receipt = $receipt;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $this->receipt->task->project->owner->notify(new ReceiptPaidNotification($this->receipt));
        } catch (\Exception $ex) {
            throw new \Exception($ex);
        }
    }
}
