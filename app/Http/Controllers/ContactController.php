<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;
use App\Models\Inquiry;

class ContactController extends Controller
{
    /**
     * Show the contact form
     */
    public function index()
    {
        return view('contact-us');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Save the inquiry to the database
        $inquiry = Inquiry::create([
            'type' => 'contact',
            'data' => [
                'name' => $request->name,
                'email' => $request->email,
                'subject' => $request->subject,
                'message' => $request->message,
                'created_at' => now()
            ]
        ]);

        // In a real application, you would send the email here
        // Mail::send(new ContactMail($request->all()));

        return response()->json([
            'success' => true,
            'message' => 'Thank you for your message! We will get back to you soon.'
        ]);
    }
}
