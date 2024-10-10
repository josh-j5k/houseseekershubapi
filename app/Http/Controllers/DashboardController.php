<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Listing;
use Illuminate\Http\Request;
use App\Http\Resources\ListingResource;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $listings = ListingResource::collection(Listing::where('user_id', $request->user()->id)->get());

        return Inertia::render('Dashboard/index', [
            'listings' => $listings
        ]);
    }
    public function messages()
    {
        return Inertia::render('Dashboard/Messages');
    }
    public function saved()
    {
        return Inertia::render('Dashboard/Saved');
    }
}
