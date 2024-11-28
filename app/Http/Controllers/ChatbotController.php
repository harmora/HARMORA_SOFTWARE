<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

class ChatbotController extends Controller
{
    protected $user;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            // fetch session and use it in entire class with constructor
            $this->user = getAuthenticatedUser();
            return $next($request);
        });
    }
    public function index()
    {


        return view('chatbot');
    }
    // public function handleQuery(Request $request)
    // {
    //     $clientQuery = $request->input('query');
    //     // Call the LangChain + Google Gemini model here using HTTP request
    //     $response = Http::withHeaders([
    //         'Authorization' => 'Bearer ' . env('GOOGLE_API_KEY'), // Set your API key in .env
    //     ])->post('https://api.yourgemini.com/v1/query', [
    //         'query' => $clientQuery,
    //     ]);

    //     return response()->json(['response' => $response->json()['response']]);
    // }
    public function getResponse(Request $request)
    {
        // Input from the chatbot input
        $userInput = $request->input('message');

        // Prepare the request to the Gemini API
        $client = new Client();
        $response = $client->post('https://gemini-api-url.com/chat', [
            'headers' => [
                'Authorization' => 'Bearer ' . env('GEMINI_API_KEY'), // Your API Key
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'prompt' => $userInput,
                'other_params' => 'value', // Adjust based on the API docs
            ]
        ]);

        // Get the response body
        $body = json_decode($response->getBody(), true);

        // Return response to the frontend
        return response()->json([
            'response' => $body['data'], // Assuming the response contains a "data" field
        ]);
    }

    public function send(Request $request)
    {
        $userMessage = $request->input('message');
        $chatHistory = $request->input('chat_history');

        // Envoyer la requête POST à l'API Flask
        $response = Http::post('http://127.0.0.1:5000/chat', [
            'message' => $userMessage,
            'chat_history' => $chatHistory
        ]);

        return response()->json($response->json());
    }

    public function chat(Request $request)
    {
        $message = $request->input('message');
        
        // Send request to Flask API
        $response = Http::post('http://localhost:5001/chat', [
            'message' => $message,
        ]);

        if ($response->successful()) {
            return response()->json([
                'response' => $response->json('response'),
            ]);
        } else {
            return response()->json([
                'error' => 'Failed to get AI response',
            ], 500);
        }
    }

    public function chat2(Request $request)
    {
        $message = $request->input('message');
        
        // Send request to Flask API
        $response = Http::post('http://localhost:5002/chat', [
            'message' => $message,
            'entreprise_id'=> $this->user->entreprise_id
        ]);

        if ($response->successful()) {
            return response()->json([
                'response' => $response->json('response'),
            ]);
        } else {
            return response()->json([
                'error' => 'Failed to get AI response',
            ], 500);
        }
    }


}
