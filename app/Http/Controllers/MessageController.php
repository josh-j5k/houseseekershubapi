<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Jobs\ImageProcessingJob;
use Illuminate\Support\Facades\DB;
use App\Helpers\ImageCompressHelper;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{

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
                        'picture' => $recipient->picture,
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
        return $this->response('success', 'all users', $messages);

    }
    public function chats($ref)
    {
        $user = Auth::user();
        $messages = [
            "recipient" => [],
            'messages' => []
        ];
        /**
         * @var User
         */
        $recipient = User::where('ref', $ref)->first();
        $messages['recipient'] = ['name' => $recipient->name, 'ref' => $recipient->ref, 'picture' => $recipient->picture];

        Message::orderBy('created_at', 'asc')->where([['senders_id', $user->id], ['receivers_id', $recipient->id]])->orWhere([['receivers_id', $user->id], ['senders_id', $recipient->id]])->each(function ($message) use (&$messages, $user, $recipient) {

            $created_at = new Carbon($message->created_at);

            $date = now()->toFormattedDateString() === $created_at->toFormattedDateString() ? "Today" : (now()->subDay()->toFormattedDateString() === $created_at->toFormattedDateString() ? 'Yesterday' : (now()->diffInDays($created_at->addDays(6)) > 0 ? $created_at->addDay()->dayName : $created_at->toFormattedDateString()));

            $messages['messages'][$date][] = [
                'date' => $date,
                'content' => [
                    'message' => $message->message,
                    'from' => $message->senders_id === $user->id ? $user->ref : $recipient->ref,
                    'time' => substr(date("H:i:s", $created_at->getTimestamp()), 0, 5),
                    'message_pictures' => $message->uploads()->count() > 0 ? [
                        'url' => config('app.filesystem_disk') == 'local' ?
                            config('app.url') . "/" . $message->uploads()->value('url') : ''

                    ] : null
                ]
            ];
        });
        return $this->response('success', 'all chats', $messages);

    }
    public function store(Request $request)
    {
        $request->validate([
            'message' => ['sometimes', 'string'],
            'receivers_ref' => ['required', 'string'],
            'message_pictures' => ['sometimes', 'array', "reqiured"],
            'message_pictures.*' => [File::types(['jpg', 'webp', 'png'])]
        ]);

        try {
            if (empty($request->message && empty($request->message_pictures))) {
                throw new Exception('can\'t send an empty message');
            }
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
                foreach ($request->message_pictures as $image) {

                    $yearDir = date("Y");
                    $monthDir = date("m");
                    $dir = "images/$yearDir/$monthDir/messages";
                    $path = Storage::disk('public')->putFile($dir, $image);
                    $message->uploads()->create(['url' => $path, 'description' => 'message picture']);
                }
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
