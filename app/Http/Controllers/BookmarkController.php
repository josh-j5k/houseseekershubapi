<?php

namespace App\Http\Controllers;
use App\Models\Listing;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    public function index(): JsonResponse
    {
        $user = Auth::user();
        $bookmarks = $user->bookmarks;
        return $this->response('success', 'All bookmarks', $bookmarks);

    }
    public function store(Listing $listing)
    {
        $user = Auth::user();
        try {
            DB::beginTransaction();
            DB::table('bookmarks')->insert([
                'users_id' => $user->id,
                'listings_id' => $listing->id,
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            return $this->response('error', $th->getMessage(), statusCode: 406);
        }
        return $this->response('success', 'Bookmarked successfully');
    }
    public function update(Listing $listing)
    {
        $user = Auth::user();
        try {
            DB::beginTransaction();
            DB::table('bookmarks')->insert([
                'users_id' => $user->id,
                'listings_id' => $listing->id,
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            return $this->response('error', $th->getMessage(), statusCode: 406);
        }
        return $this->response('success', 'Bookmarked successfully');
    }
}
