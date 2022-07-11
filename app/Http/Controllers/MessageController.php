<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index($id)
    {
        return response()->json(['messages' => Message::where('dialog_id', $id)->get()]);
    }

    public function create(Request $request)
    {
        Message::create([
            'dialog_id' => $request->dialog_id,
            'user_id' => Auth::id(),
            'user_ip' => '0.0.0.0',
            'is_read' => 0,
            'text' => $request->message,
        ]);
        return response()->json(['messages' => $request->all()]);
    }
}
