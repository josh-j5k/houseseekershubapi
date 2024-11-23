<?php

namespace App\Http\Controllers;

use App\Mail\Contact;
use Inertia\Inertia;
use App\Models\Listing;
use Illuminate\Http\Request;
use App\Notifications\ContactMail;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\ListingResource;
use Illuminate\Support\Facades\Notification;


class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required'],
            'email' => ['required', 'lowercase', 'email', 'max:255'],
            'category' => ['required'],
            'description' => ['required']
        ]);
        Mail::to('joshdejong5k@gmail.com')->send(new Contact($validated));
        return $this->response('success', 'mail sent successfully', statusCode: 200);
    }

}
