<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class EnsureStorageDirectories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:ensure-directories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ensure required storage directories exist';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $directories = [
            'rps',
        ];

        foreach ($directories as $directory) {
            if (!Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory);
                $this->info("Created directory: storage/app/public/{$directory}");
            } else {
                $this->info("Directory already exists: storage/app/public/{$directory}");
            }
        }

        $this->info('Storage directories check completed.');
    }
}
