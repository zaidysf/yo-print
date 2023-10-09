<?php

namespace App\Jobs;

use App\Events\FileStatusUpdated;
use App\Models\File;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Redis\LimiterTimeoutException;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class ProcessProduct implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $data;
    protected int $file_id;

    /**
     * Create a new job instance.
     */
    public function __construct(array $data, int $file_id)
    {
        $this->data = $data;
        $this->file_id = $file_id;
    }

    /**
     * Get the unique ID for the job.
     */
    public function uniqueId(): string
    {
        return $this->file_id;
    }

    /**
     * Execute the job.
     * @throws LimiterTimeoutException
     */
    public function handle(): void
    {
        Redis::throttle('key')->block(0)->allow(1)->every(5)->then(function () {
            info('Lock obtained...');

            $file = File::find($this->file_id);
            $file->status = 1;
            $file->save();
            $productHeader = array_flip((new Product)->getCsvHeader());

            DB::beginTransaction();
            try {
                $input = [];
                $i = 1;
                foreach ($this->data as $product) {
                    foreach ($product as $header => $value) {
                        $input[$productHeader[$header]] = mb_convert_encoding($value, 'UTF-8');
                    }
                    Product::updateOrCreate(['unique_key' => $input['unique_key']], $input);
                    $file->row_processed = $i;
                    $file->save();

                    // This will use for updating progress bar in status
                    // FileStatusUpdated::dispatch($file);

                    $i++;
                }
                $file->status = 3;
                $file->save();
                FileStatusUpdated::dispatch($file);
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                $file->status = 2;
                $file->save();
                Log::error($e->getMessage());
            }
        }, function () {
            Log::error('Could not obtain lock');
            return $this->release(5);
        });

    }
}
