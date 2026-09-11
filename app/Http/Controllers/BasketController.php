<?php

namespace App\Http\Controllers;

use App\Http\ApiTraits\BasketAPIResponses;
use App\Http\Requests\CreateBasketRequest;
use App\Models\Basket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BasketController extends Controller
{
    use BasketAPIResponses;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        if ($request->user()->isAdmin()) {
            $baskets = Basket::with('cat')->latest()->paginate(10)->withQueryString();

            return $this->basketAdminIndexSuccessResponse($baskets->toResourceCollection());
        }

        $baskets = $request->user()->baskets()->with('cat')->latest()->paginate(10)->withQueryString();

        return $this->basketUserIndexSuccessResponse($baskets->toResourceCollection());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateBasketRequest $request): JsonResponse
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return $this->basketInvalidResponse($request->validator->errors());
        }

        $user = $request->user();
        $catId = $request->safe()->integer('cat_id');

        $alreadyInBasket = $user->baskets()->where('cat_id', $catId)->exists();

        if ($alreadyInBasket) {
            return $this->basketCreateFailedResponse($catId);
        }

        DB::transaction(function () use ($user, $catId) {
            return Basket::create([
                'user_id' => $user->id,
                'cat_id' => $catId,
            ]);
        });

        return $this->basketCreateSuccessResponse($catId);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $basket = Basket::with('cat')->find($id);

        if (! $basket) {
            return $this->basketNotFoundResponse();
        }

        if (! $request->user()->isAdmin() && $basket->user_id !== $request->user()->id) {
            return $this->forbiddenResponse();
        }

        return $this->basketUserIndexSuccessResponse($basket->toResource());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $basket = Basket::find($id);

        if (! $basket) {
            return $this->basketNotFoundResponse();
        }

        if (! $request->user()->isAdmin() && $basket->user_id !== $request->user()->id) {
            return $this->forbiddenResponse();
        }

        $basket->delete();

        return $this->basketDestroySuccessResponse();
    }
}
