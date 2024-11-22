<?php

namespace App\Jobs;

use App\Models\Listing;
use Illuminate\Http\File;
use App\Helpers\ImageCompressHelper;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;

class ImageProcessingJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public $model, public File $files)
    {

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach ($this->files as $file_input) {

            $folder = date("Y");
            $subFolders = date("m");

            $url = ImageCompressHelper::compress($file_input, 1080, 100, $folder, $subFolders);

            $this->model->uploads()->create(['url' => $url, 'description' => 'Listing Image']);
        }
    }
}
