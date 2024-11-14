<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListingStoreRequest;
use App\Models\Listing;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Helpers\ImageCompressHelper;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ListingsRequest;
use App\Http\Resources\ListingResource;

class ListingController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(ListingsRequest $request)
    {

        $location = $request->location;

        $property_status = $request->property_status ?? 'any';

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

        $per_page = (int) $request->per_page ?? 16;
        $listingsBuilder = Listing::filter($filters);
        $listings = filled($request->limit) ? $listingsBuilder->limit($request->limit)->get() : $listingsBuilder->paginate($per_page);

        $data = ListingResource::collection($listings);

        return $this->response('success', 'All listings', $data, 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(ListingStoreRequest $request)
    {
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
    }



    /**
     * Update the specified resource in storage.
     */
    public function update($request, Listing $listing, )
    {
        $form_fields = $request->validate([
            'title' => 'required',
            'location' => 'required',
            'price' => 'required',
            'property_type' => 'required',
            'property_status' => 'required',
            'description' => 'required',
        ]);
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
        redirect()->route('user.dashboard', $request->user()->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Listing $listing)
    {
        $listing->delete();
        back();
    }
}
