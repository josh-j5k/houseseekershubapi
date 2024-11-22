<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Jobs\ImageProcessingJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{

    public function index()
    {
        $user = Auth::user();

        $messages = Message::orderByDesc('created_at')->where('receivers_id', $user->id)->orWhere('senders_id', $user->id)->distinct();

    }
    public function store(Request $request)
    {

        try {
            DB::beginTransaction();
            $user = Auth::user();
            $receivers_id = User::where('ref', $request->recievers_ref)->value('id');
            $message = Message::create([
                'senders_id' => $user->id,
                'receivers_id' => $receivers_id,
                'message' => $request->message
            ]);
            if ($request->hasFile('message_pictures')) {
                ImageProcessingJob::dispatch($message, 'message_pictures')->onQueue('high');
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->response('error', $th->getMessage(), statusCode: 406);
        }
        return $this->response('success', 'message sent successfully', statusCode: 201);
    }
}
