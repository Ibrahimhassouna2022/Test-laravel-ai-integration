<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Prism\Prism\Prism;
use Prism\Prism\Enums\Provider;
use Prism\Prism\ValueObjects\Messages\UserMessage;
use Prism\Prism\ValueObjects\Messages\AssistantMessage;

class ChatController extends Controller
{
    public function index()
    {
        $messages = ChatMessage::latest()->get();
        return view('chat', compact('messages'));
    }

    public function send(Request $request, Prism $prism)
{
    $request->validate([
        'message' => 'required|string'
    ]);

    $chat = [];

    $messages = ChatMessage::latest()->take(10)->get()->reverse();

    foreach ($messages as $message) {
        $chat[] = new UserMessage($message->question);
        $chat[] = new AssistantMessage($message->answer);
    }

    $chat[] = new UserMessage($request->message);

    $response = $prism
        ->text()
        ->using(Provider::OpenAI, 'gpt-4o-mini')
        ->withMessages($chat)
        ->asText();

    $answer = $response->text;

    ChatMessage::create([
        'question' => $request->message,
        'answer' => $answer
    ]);

    return response()->json([
        'question' => $request->message,
        'answer' => $answer
    ]);
}
}