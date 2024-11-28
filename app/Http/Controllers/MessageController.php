<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Jobs\ImageProcessingJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{

    public function getUsers()
    {
        $users = User::all();
        return $this->response('success', 'all users', $users, 200);
    }
    public function index()
    {
        $user = Auth::user();
        $messages = [];
        $recipientIdArr = [];

        Message::orderByDesc('created_at')->where(function ($q) {
            $q->where('senders_id', Auth::user()->id)
                ->orWhere('receivers_id', Auth::user()->id);
        })->each(function (object $message) use (&$recipientIdArr, &$messages): void {
            $recipient_id = $message->senders_id == Auth::user()->id ? $message->receivers_id : $message->senders_id;
            if (in_array($recipient_id, $recipientIdArr) === false) {
                $recipient = User::find($recipient_id);
                $created_at = new Carbon($message->created_at);
                $messages[] = [
                    'id' => $message->id,

                    'latest_message_sender' => User::where('id', $message->senders_id)->value('ref'),
                    'recipient' => [
                        'avatar' => $recipient->avatar,
                        'name' => $recipient->name,
                        'ref' => $recipient->ref
                    ],
                    'message' => $message->message,
                    'unread' => 0,
                    'time' => now()->toFormattedDateString() === $created_at->toFormattedDateString() ? substr(date("H:i:s", $created_at->getTimestamp()), 0, 5) : (now()->subDay()->toFormattedDateString() === $created_at->toFormattedDateString() ? 'Yesterday' : (now()->diffInDays($created_at->addDays(6)) > 0 ? $created_at->addDay()->dayName : $created_at->toDateString()))

                ];

                $recipientIdArr[] = $recipient->id;
            }
        });
        return $this->response('success', 'all users', $messages, 200);

    }
    public function chats($ref)
    {
        $user = Auth::user();
        $messages = [
            "recipient" => [],
            'messages' => []
        ];

        $recipient = User::where('ref', $ref)->first();
        $messages['recipient'] = ['name' => $recipient->name, 'avatar' => $recipient->avatar, 'ref' => $recipient->ref];
        Message::orderBy('created_at', 'asc')->where([['senders_id', $user->id], ['receivers_id', $recipient->id]])->orWhere([['receivers_id', $user->id], ['senders_id', $recipient->id]])->each(function ($message) use (&$messages, $user, $recipient) {
            $created_at = new Carbon($message->created_at);
            $date = now()->toFormattedDateString() === $created_at->toFormattedDateString() ? "Today" : (now()->subDay()->toFormattedDateString() === $created_at->toFormattedDateString() ? 'Yesterday' : (now()->diffInDays($created_at->addDays(6)) > 0 ? $created_at->addDay()->dayName : $created_at->toFormattedDateString()));
            $messages['messages'][$date][] = [
                'date' => $date,
                'content' => [
                    'message' => $message->message,
                    'from' => $message->senders_id === $user->id ? $user->ref : $recipient->ref,
                    'time' => date("H:i:s", $created_at->getTimestamp())
                ]
            ];
        });
        return $this->response('success', 'all chats', $messages, 200);

    }
    public function store(Request $request)
    {

        try {
            DB::beginTransaction();
            $user = Auth::user();
            $receivers_id = User::where('ref', $request->receivers_ref)->value('id');
            $message = Message::create([
                'senders_id' => $user->id,
                'receivers_id' => $receivers_id,
                'message' => $request->message,
                'ref' => Str::uuid()
            ]);
            if ($request->hasFile('message_pictures')) {
                ImageProcessingJob::dispatch($message, 'message_pictures')->onQueue('high');
            }
            $created_at = new Carbon($message->created_at);

            $time = substr(date("H:i:s", $created_at->getTimestamp()), 0, 5);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->response('error', $th->getMessage(), statusCode: 406);
        }
        return $this->response('success', 'message sent successfully', $time, 201);
    }
}
