<?php

namespace App\Observers;

use App\Models\Upload;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadObserver
{
    /**
     * Handle the Upload "created" event.
     *
     * @param  \App\Models\Upload  $upload
     * @return void
     */
    public function creating(Upload $upload)
    {
        $length = 6;
        $tries = 0;
        while (true) {
            $hash = Str::lower(Str::random($length));
            $exists = Upload::query()
                ->where('hash', $hash)
                ->exists();
            if (!$exists) break;

            $tries++;
            if ($tries >= 5) {
                $length++;
                if ($length > 10) {
                    throw new \Exception('Unable to generate unique hash.');
                }

                $tries = 0;
            }
        }

        $upload->hash = $hash;
    }

    /**
     * Handle the Upload "created" event.
     *
     * @param  \App\Models\Upload  $upload
     * @return void
     */
    public function created(Upload $upload)
    {
        //
    }

    /**
     * Handle the Upload "updated" event.
     *
     * @param  \App\Models\Upload  $upload
     * @return void
     */
    public function updated(Upload $upload)
    {
        //
    }

    /**
     * Handle the Upload "deleted" event.
     *
     * @param  \App\Models\Upload  $upload
     * @return void
     */
    public function deleting(Upload $upload)
    {
        $upload->downloads()->delete();
    }

    /**
     * Handle the Upload "deleted" event.
     *
     * @param  \App\Models\Upload  $upload
     * @return void
     */
    public function deleted(Upload $upload)
    {
        Storage::disk($upload->disk)
            ->delete($upload->path);
    }

    /**
     * Handle the Upload "restored" event.
     *
     * @param  \App\Models\Upload  $upload
     * @return void
     */
    public function restored(Upload $upload)
    {
        //
    }

    /**
     * Handle the Upload "force deleted" event.
     *
     * @param  \App\Models\Upload  $upload
     * @return void
     */
    public function forceDeleted(Upload $upload)
    {
        //
    }
}
