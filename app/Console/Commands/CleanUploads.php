<?php

namespace App\Console\Commands;

use App\Models\Upload;
use Illuminate\Console\Command;

class CleanUploads extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clean-uploads';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes all uploaded files which have expired.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Upload::query()
            ->where('expires_at', '<', now())
            ->each(function (Upload $upload) {
                $upload->delete();
            });

        return Command::SUCCESS;
    }
}
