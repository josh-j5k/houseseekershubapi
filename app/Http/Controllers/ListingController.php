<?php

namespace App\Http\Controllers;

use App\Helpers\ImageCompressHelper;
use App\Http\Requests\ListingsRequest;
use App\Models\Listing;
use Illuminate\Http\Request;
use App\Http\Resources\ListingResource;
use App\Traits\ResponseTrait;
class ListingController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(ListingsRequest $request)
    {
        dd($request->status);
        $location = $request->location;

        $status = $request->status ?? 'any';

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
            'status' => $status,
            'price' => $price,
            'property_type' => $property_type
        ];

        $per_page = (int) $request->per_page ?? 16;
        $listings = ListingResource::collection(Listing::filter($filters)->paginate($per_page));

        return $this->response('success', 'All listings', $listings, 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = $request->user();
        $form_fields = $request->all();
        $form_fields['user_id'] = $user->id;
        $listing = Listing::create($form_fields);

        foreach ($request->inputFiles as $file_input) {
            $folder = date("Y");
            $subFolders = date("m");
            ;
            $url = ImageCompressHelper::compress($file_input, 1080, 100, $folder, $subFolders);


        }
    }

    /**
     * Display the specified resource.
     */
    public function show($ref)
    {

        $listing = new ListingResource(Listing::where('ref', $ref)->firstOrFail());
        // return Inertia::render('Listings/show', [
        //     'listing' => $listing
        // ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Listing $listing, )
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

                $listing->listingImage()->updateOrCreate([
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
