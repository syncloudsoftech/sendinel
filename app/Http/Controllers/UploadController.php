<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadRequest;
use App\Mail\UploadShared;
use App\Models\Upload;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use UAParser\Parser;

class UploadController extends Controller
{
    public function index(): Renderable
    {
        return view('upload');
    }

    public function upload(UploadRequest $request)
    {
        $data = $request->validated();
        $password = null;
        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($password = $data['password']);
        }

        /** @var UploadedFile $attachment */
        $attachment = $data['attachment'];
        unset($data['attachment']);
        $data['disk'] = config('filesystems.default');
        $data['name'] = $attachment->getClientOriginalName();
        $data['size'] = (int)($attachment->getSize() / 1000);
        $data['path'] = Storage::put('uploads', $attachment);
        $data['expires_at'] = Carbon::now()->addDays($data['expiry']);
        unset($data['expiry']);
        $uap = Parser::create();
        $ua = $uap->parse($request->header('user-agent'));
        $data['ua_ip'] = $request->ip();
        $data['ua_browser'] = $ua->ua->toString();
        $data['ua_os'] = $ua->os->toString();
        $data['ua_device'] = $ua->device->toString();
        /** @var Upload $upload */
        $upload = Upload::query()->create($data);
        if (isset($data['recipient'])) {
            Mail::to($data['recipient'])
                ->send(new UploadShared($upload, $password));
        }

        if ($request->isXmlHttpRequest()) {
            return response()->json(['redirect' => $upload->url]);
        }

        return redirect($upload->url);
    }
}
