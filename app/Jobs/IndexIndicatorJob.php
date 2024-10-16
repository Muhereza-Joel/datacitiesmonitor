<?php

namespace App\Jobs;

use App\Models\Indicator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class IndexIndicatorJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $indicator;

    public function __construct(Indicator $indicator)
    {
        $this->indicator = $indicator;
    }

    public function handle()
    {
        $this->indicator->searchable(); // Index the indicator
    }
}
