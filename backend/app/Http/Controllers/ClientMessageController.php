<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;

class ClientMessageController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $messages = Message::with('sender')
            ->where('recipient_id', $user->id)
            ->paginate(15);

        return response()->json($messages);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $data = $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'content' => 'required|string',
        ]);

        $data['sender_id'] = $user->id;
        $message = Message::create($data);
        $message->load('sender'); // Load sender relationship for event

        // Dispatch event to send notification
        event(new \App\Events\MessageSent($message));

        return response()->json($message, 201);
    }
}
