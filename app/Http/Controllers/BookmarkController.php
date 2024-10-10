<?php

namespace App\Http\Controllers;
use Illuminate\Http\Client\Response;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    public function store(Listing $listing)
    {
        $user = Auth::user();
        DB::table('bookmarks')->insert([
            'users_id' => $user->id,
            'listings_id' => $listing->id,
        ]);

    }
}
