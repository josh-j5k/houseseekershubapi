<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Listing;

use App\Models\Bookmark;
use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    public function index(): JsonResponse
    {
        /**
         * @var User
         */
        $user = Auth::user();
        $listings = [];
        $user->bookmarks()->with('listing.uploads')->each(function ($bookmark) use (&$listings) {

            $listings[] = [
                'id' => $bookmark->listing->id,
                "ref" => $bookmark->listing->ref,
                "title" => $bookmark->listing->title,
                "property_status" => $bookmark->listing->property_status,
                "property_type" => $bookmark->listing->property_type,
                "location" => $bookmark->listing->location,
                "price" => $bookmark->listing->price,
                "description" => $bookmark->listing->description,
                'images' => config('app.url') . "/" . $bookmark->listing->uploads[0]['url']
            ];
        });

        return $this->response('success', 'All bookmarks', $listings);

    }
    public function store($listing_id)
    {
        $user = Auth::user();
        try {
            DB::beginTransaction();
            $listing = Listing::find($listing_id);
            $bookmark = Bookmark::where('user_id', $user->id)->where('listing_id', $listing->id)->first();
            if ($bookmark) {
                $bookmark->delete();
            } else {
                Bookmark::create([
                    'user_id' => $user->id,
                    'listing_id' => $listing->id
                ]);
            }
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
