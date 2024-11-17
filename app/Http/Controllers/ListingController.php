<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Helpers\ImageCompressHelper;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ListingsRequest;
use App\Http\Resources\ListingResource;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Requests\ListingStoreRequest;

class ListingController extends Controller
{
    use ResponseTrait;
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

            $hasMorePages = ($limit * $page) < $total ? true : false;
            $listings = Listing::orderByDesc('created_at')->filter($filters)
                ->when($page > 1, function (Builder $builder) use ($page, $limit) {
                    $builder->offset($page * $limit);
                })->limit($limit)->get();

            $data = ListingResource::collection($listings);

        } catch (\Throwable $th) {
            return $this->response('error', $th->getMessage(), statusCode: 406);
        }
        return $this->response('success', 'All listings', ['listings' => $data, 'hasMorePages' => $hasMorePages], 200);
    }
    /**
     * Get a listing of the resource.
     */
    public function show($ref)
    {
        try {
            $listing = Listing::where('ref', $ref)->get();
            if (!$listing) {
                throw new AuthorizationException();
            }
            $userRef = $listing[0]->user->ref;
            $data = ListingResource::collection($listing);
        } catch (\Throwable $th) {
            return $this->response('error', $th->getMessage(), statusCode: 406);
        }
        return $this->response('success', 'Listing', ['listing' => $data[0], 'canShare' => $userRef], 200);
    }
    /**
     * Get User Listigs
     */
    public function userListings(Request $request)
    {
        try {
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
        return $this->response('success', 'All User\'s listings', ['listings' => $data, 'hasMorePages' => $hasMorePages], 200);

    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(ListingStoreRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $user = Auth::user();

            $listing = Listing::create([
                'id' => $user->id,
                'title' => $request->title,
                'description' => $request->description,
                'location' => $request->location,
                'price' => $request->price,
                'propery_status' => $request->property_status
            ]);

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
    public function update(ListingStoreRequest $request, $ref, ): JsonResponse
    {
        try {
            DB::beginTransaction();
            $user = Auth::user();
            $listing = Listing::where('ref', $ref)->where('user_id', $user->id)->first();
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
            if ($request->deletedImages !== null) {
                foreach ($request->deletedImages as $image) {
                    $listing->listingImage()->where('listing_image', $image)->delete();
                    if (is_dir(public_path($image))) {
                        unlink(public_path($image));
                    }
                }
            }
            if ($request->hasFile('inputFiles')) {
                foreach ($request->inputFiles as $file_input) {
                    $folder = date("Y");
                    $subFolders = date("m");

                    $url = ImageCompressHelper::compress($file_input, 1080, 100, $folder, $subFolders);

                    $listing->uploads()->updateOrCreate([
                        'listing_image' => $url,
                        'listing_id' => $listing->id
                    ]);
                }
            }
            $listing->update($form_fields);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->response('error', $th->getMessage(), statusCode: 406);
        }
        return $this->response('success', 'Listing updated successfully', statusCode: 200);
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
        return $this->response('success', 'Listing deleted successfully', statusCode: 200);
    }


}
