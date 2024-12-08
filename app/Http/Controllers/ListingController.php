<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Listing;
use Illuminate\Support\Str;

use Illuminate\Http\Request;
use Laravel\Sanctum\Sanctum;
use App\Jobs\ImageProcessingJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Helpers\ImageCompressHelper;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ListingsRequest;
use App\Http\Resources\ListingResource;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Requests\ListingStoreRequest;
use App\Models\Bookmark;
use Illuminate\Auth\Access\AuthorizationException;

class ListingController extends Controller
{
    /**
     * Get all listing of the resource.
     */
    public function index(ListingsRequest $request)
    {

        try {
            $location = $request->location;
            $property_status = $request->status ?? 'any';

            $price = [
            ];
            $property_type = filled($request->property_type) ? explode('|', $request->property_type) : [];

            if (filled($request->price)) {
                $temPrice = explode('|', $request->price);
                foreach ($temPrice as $item) {
                    if (str_starts_with($item, 'over')) {
                        $price['min'] = substr($item, 4);
                    } else {
                        $price['max'] = substr($item, 5);
                    }
                }
            }
            $filters = [
                'location' => $location,
                'status' => $property_status,
                'price' => $price,
                'property_type' => $property_type
            ];

            $limit = (int) $request->limit ?: 16;
            $page = (int) $request->page ?: 1;
            $total = Listing::count();
            $offset = ($page - 1) * $limit;
            $hasMorePages = ($limit * $page) < $total ? true : false;
            $listings = Listing::orderByDesc('created_at')->filter($filters)
                ->when($page > 1, function (Builder $builder) use (&$offset) {
                    $builder->offset($offset);
                })->limit($limit)->get();

            $data = ListingResource::collection($listings);

        } catch (\Throwable $th) {
            return $this->response('error', $th->getMessage(), statusCode: 406);
        }
        return $this->response('success', 'All listings', ['listings' => $data, 'hasMorePages' => $hasMorePages]);
    }
    /**
     * Get a listing of the resource.
     */
    public function show(Request $request, $slug)
    {
        try {

            $listing = Listing::where('slug', $slug)->get();
            if (!$listing) {
                throw new AuthorizationException();
            }
            $userRef = $listing[0]->user->ref;
            $data = ListingResource::collection($listing);

            $payload = [
                'listing' => $data[0],

            ];

            if ($request->bearerToken()) {
                $model = Sanctum::$personalAccessTokenModel;
                $accessToken = $model::findToken($request->bearerToken());

                if ($accessToken) {
                    $payload['canShare'] = $userRef;
                    $bookmark = Bookmark::where('user_id', $accessToken->tokenable_id)->where('listing_id', $listing[0]->id)->first();
                    $payload['bookmark'] = $bookmark ? true : false;
                }
            }
        } catch (\Throwable $th) {
            return $this->response('error', $th->getMessage(), $th->getTrace(), statusCode: 406);
        }
        return $this->response('success', 'Listing', $payload);
    }
    /**
     * Get User Listigs
     */
    public function userListings(Request $request)
    {
        try {
            /**
             * @var User
             */
            $user = Auth::user();

            $limit = (int) $request->limit ?: 8;
            $page = (int) $request->page ?: 1;
            $total = $user->listings()->count();

            $hasMorePages = ($limit * $page) < $total ? true : false;
            $listings = Listing::where('user_id', $user->id)->orderByDesc('created_at')
                ->when($page > 1, function (Builder $builder) use ($page, $limit) {
                    $builder->offset($page * $limit);
                })->limit($limit)->get();

            $data = ListingResource::collection($listings);
        } catch (\Throwable $th) {
            return $this->response('error', $th->getMessage(), statusCode: 406);
        }
        return $this->response('success', 'All User\'s listings', ['listings' => $data, 'hasMorePages' => $hasMorePages, 'user' => $user]);

    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(ListingStoreRequest $request): JsonResponse
    {
        $slugEndingArr = ['fa5', 'eb4', 'dc3', 'cd2', 'be1', 'af9', '2fc', '1ce', '3bf', '4da'];
        try {
            DB::beginTransaction();
            $user = Auth::user();

            $listing = Listing::create([
                'user_id' => $user->id,
                'title' => $request->title,
                'description' => $request->description,
                'location' => $request->location,
                'price' => $request->price,
                'property_status' => $request->property_status,
                'property_type' => $request->property_type
            ]);

            $ending = '';
            $id = (string) $listing->id;
            for ($i = 0; $i < strlen($id); $i++) {
                $num = (int) $id[$i];
                $ending .= $slugEndingArr[$num];
            }
            $characters = array('.', '@', '!', '$', '%', '#', '^', '*', '(', ')', );
            $title = (string) str_replace($characters, '', $listing->title);
            $rTitle = str_replace(['+', '_', '=', " "], '-', $title);
            $location = str_replace([',', ';', '_'], "", explode(" ", $listing->location)[0]);
            $slug = strtolower("$rTitle-$location-$ending");
            $listing->update(['slug' => $slug]);

            // ImageProcessingJob::dispatch($listing, $request->inputFiles)->onQueue('high');
            foreach ($request->inputFiles as $file_input) {

                $folder = date("Y");
                $subFolders = date("m");

                $url = ImageCompressHelper::compress($file_input, 1080, 100, $folder, $subFolders);

                $listing->uploads()->create(['url' => $url, 'description' => 'Listing Image']);
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->response('error', $th->getMessage(), statusCode: 406);
        }
        return $this->response('success', 'Listing created successfully', statusCode: 201);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(ListingStoreRequest $request, $id, ): JsonResponse
    {
        try {
            DB::beginTransaction();
            $user = Auth::user();
            $listing = Listing::where('id', $id)->where('user_id', $user->id)->first();
            if (!$listing) {
                throw new AuthorizationException();
            }
            $form_fields = [
                'title' => $request->title,
                'location' => $request->location,
                'price' => $request->price,
                'property_type' => $request->property_type,
                'property_status' => $request->property_status,
                'description' => $request->description,
            ];
            $listing->update($form_fields);
            if ($request->deletedImages !== null) {
                foreach ($request->deletedImages as $image) {
                    $listing->uploads()->where('url', $image)->delete();
                    if (is_dir(public_path($image))) {
                        unlink(public_path($image));
                    }
                }
            }
            ImageProcessingJob::dispatch($listing, $request->inputFiles)->onQueue('high');

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->response('error', $th->getMessage(), statusCode: 406);
        }
        return $this->response('success', 'Listing updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($ref): JsonResponse
    {
        try {
            DB::beginTransaction();
            $user = Auth::user();
            $listing = Listing::where('ref', $ref)->where('user_id', $user->id)->first();
            if (!$listing) {
                throw new AuthorizationException();
            }
            $listing->delete();
            DB::commit();
        } catch (\Throwable $th) {
            return $this->response('error', $th->getMessage(), statusCode: 406);
        }
        return $this->response('success', 'Listing deleted successfully');
    }


}
