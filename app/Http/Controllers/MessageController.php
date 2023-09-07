<?php

namespace App\Http\Controllers;

use App\Jobs\MessageJob;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function __construct(
        private Message $message
    ){}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->message->all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $inputs = $request->all();

        $inputs['user_id'] = $request->user()->id;

        if($message = $this->message->create($inputs)){

            MessageJob::dispatch(message: $message);

            return $message;
        }

        return response()->json([
            'error' => 'internal error'
        ], 500);
    }

    /**
     * Display the specified resource.
     */
    public function show(Message $message)
    {
        return $message;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Message $message)
    {
        if($message->update($request->all()))
            return $message;

        return response()->json([
            'error' => 'internal error'
        ], 500);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message)
    {
        if($message->delete())
            return $message;

        return response()->json([
            'error' => 'internal error'
        ], 500);
    }
}
