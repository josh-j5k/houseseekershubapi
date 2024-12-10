<?php

namespace App\Jobs;

use App\Models\Listing;
use Illuminate\Http\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Helpers\ImageCompressHelper;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class ImageProcessingJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public $model, public array $files, public string $description)
    {

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Log::info($this->files);
        foreach ($this->files as $file_input) {

            $folder = date("Y");
            $subFolders = date("m");
            // $image = Storage::disk('public')->path();
            // Log::info($image);
            $url = ImageCompressHelper::compress($file_input, 1080, 100, $folder, $subFolders);

            $this->model->uploads()->create(['url' => $url, 'description' => $this->description]);
        }
    }
}
