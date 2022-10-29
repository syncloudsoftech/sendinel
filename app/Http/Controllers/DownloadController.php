<?php

namespace App\Http\Controllers;

use App\Http\Requests\DownloadRequest;
use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use UAParser\Parser;

class DownloadController extends Controller
{
    public function show(string $hash, Request $request)
    {
        /** @var Upload $upload */
        $upload = Upload::query()
            ->where('hash', $hash)
            ->firstOrFail();
        abort_if($upload->expires_at->isPast(), 404);
        $uploaded = $request->has('uploaded');

        return view('download', compact('upload', 'uploaded'));
    }

    public function submit(Upload $upload, DownloadRequest $request)
    {
        $data = $request->validated();
        if ($upload->password && (empty($data['password']))) {
            throw ValidationException::withMessages([
                'password' => trans('validation.required', ['attribute' => 'password']),
            ]);
        }

        if ($upload->password && !Hash::check($data['password'], $upload->password)) {
            throw ValidationException::withMessages([
                'password' => trans('auth.failed'),
            ]);
        }

        $uap = Parser::create();
        $ua = $uap->parse($request->header('User-Agent'));
        $data['ua_ip'] = $request->ip();
        $data['ua_browser'] = $ua->ua->toString();
        $data['ua_os'] = $ua->os->toString();
        $data['ua_device'] = $ua->device->toString();
        $upload->downloads()->create($data);

        return Storage::disk($upload->disk)
            ->download($upload->path, $upload->name);
    }
}
