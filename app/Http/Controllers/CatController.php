<?php

namespace App\Http\Controllers;

use App\Http\ApiTraits\CatAPIResponses;
use App\Http\Requests\IndexCatRequest;
use App\Http\Requests\CreateCatRequest;
use App\Http\Requests\UpdateCatRequest;
use App\Http\Resources\CatResource;
use App\Models\Cat;
use App\Models\CatAudit;
use App\Models\CatImage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CatController extends Controller
{
    use AuthorizesRequests, CatAPIResponses;
    /**
     * Display a listing of the resource.
     */
    public function index(IndexCatRequest $request)
    {
        $cats = Cat::query()
            ->with(['primaryImage', 'father', 'mother'])
            ->search($request->search())
            ->breed($request->breed())
            ->sex($request->sex())
            ->status($request->status())
            ->priceBetween(
                $request->minPrice() !== null ? (float) $request->minPrice() : null,
                $request->maxPrice() !== null ? (float) $request->maxPrice() : null
            )
            ->latest()
            ->paginate($request->perPage())
            ->withQueryString();
        
        return $this->catIndexSuccessResponse(CatResource::collection($cats));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCatRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return $this->catCreateFailedValidationResponse($request->validator->errors());
        }

        $cat = DB::transaction(function() use ($request) {
            $cat = Cat::create($request->safe()->except(['images', 'primary_image_index']));

            $this->storeImages($cat, $request);

            return $cat;
        });

        if (!$cat instanceof Cat) {
            return;
        }

        $cat->load(['images', 'father', 'mother']);

        return $this->catCreateSuccessResponse(new CatResource($cat));
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $cat = Cat::with(['images','father','mother'])->find($id);
        if (!$cat) {
            return $this->catNotFoundResponse();
        }
        $cat->load(['images','father','mother']);
        return $this->catShowSuccessResponse(new CatResource($cat));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCatRequest $request, int $id)
    {
        $cat = Cat::find($id);

        if (!$cat) {
            return $this->catNotFoundResponse();
        }

        DB::transaction(function() use ($cat, $request) {
            $cat->update($request->safe()->except(['images', 'primary_image_index', 'delete_image_ids']));
            if ($request->filled('delete_image_ids')) {
                $this->deleteImages($cat, $request->input('delete_images_ids'));
            }

            $this->storeImages($cat, $request);
        });

        return $this->catUpdateSuccessResponse(new CatResource($cat->fresh(['images', 'father', 'mother'])));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $cat = Cat::find($id);

        if (!$cat) {
            return $this->catNotFoundResponse();
        }

        $cat->delete();

        return $this->catDestroySuccessResponse();
    }

    protected function storeImages(Cat $cat, Request $request) {
        if (!$request->hasFile('images')) {
            return;
        }

        $primaryIndex = $request->input('primary_image_index');
        $hasExistingPrimary = $cat->images()->where('is_primary', true)->exists();
 
        foreach ($request->file('images') as $index => $file) {
            $path = $file->store('cats', 'public');
 
            CatImage::create([
                'cat_id' => $cat->id,
                'path' => $path,
                'is_primary' => ! $hasExistingPrimary && (
                    $primaryIndex !== null ? (int) $primaryIndex === $index : $index === 0
                ),
            ]);
        }
    }

    protected function deleteImages(Cat $cat, array $imageIds): void
    {
        $images = $cat->images()->whereIn('id', $imageIds)->get();
 
        foreach ($images as $image) {
            Storage::disk('public')->delete($image->path);
            $image->delete();
        }
    }

}
