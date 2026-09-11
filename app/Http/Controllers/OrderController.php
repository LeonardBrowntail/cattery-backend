<?php

namespace App\Http\Controllers;

use App\Http\ApiTraits\OrderAPIResponses;
use App\Http\Requests\CreateOrderRequest;
use App\Http\Requests\IndexOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    use OrderAPIResponses;

    /**
     * Display a listing of the resource.
     */
    public function index(IndexOrderRequest $request): JsonResponse
    {
        $orders = Order::query()
            ->with(['orderDetails.cat', 'user'])
            ->when($request->user()->isAdmin(), function ($query) use ($request) {
                return $query
                    ->status($request->status())
                    ->search($request->search());
            }, function ($query) use ($request) {
                return $query->where('user_id', $request->user()->id);
            })
            ->latest('id')
            ->paginate($request->integer('perPage', 10))
            ->withQueryString();

        return $this->orderIndexSuccessResponse($orders->toResourceCollection());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateOrderRequest $request): JsonResponse
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return $this->orderUpdateInvalidResponse($request->validator->errors());
        }

        $user = $request->user();

        $baskets = $user->baskets()->with('cat')->get();

        if ($baskets->isEmpty()) {
            return $this->orderCreateFailedResponse(['basket' => ['Basket is empty, add a cat first.']]);
        }

        $order = DB::transaction(function () use ($user, $baskets, $request) {
            $order = Order::create([
                'user_id' => $user->id,
                'province' => $request->safe()->string('province'),
                'regency' => $request->safe()->string('regency'),
                'district' => $request->safe()->string('district'),
                'village' => $request->safe()->string('village'),
                'address' => $request->safe()->string('address'),
                'status' => 'pending',
            ]);

            foreach ($baskets as $basket) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'cat_id' => $basket->cat_id,
                    'price' => $basket->cat->price,
                ]);

                $basket->cat->update(['status' => 'Reserved']);
                $basket->delete();
            }

            return $order;
        });

        $order->load(['orderDetails.cat', 'user']);

        return $this->orderCreateSuccessResponse(new OrderResource($order));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $order = Order::with(['orderDetails.cat', 'transaction', 'user'])->find($id);

        if (! $order) {
            return $this->orderNotFoundResponse();
        }

        if (! $request->user()->isAdmin() && $order->user_id !== $request->user()->id) {
            return $this->forbiddenResponse();
        }

        return $this->orderShowSuccessResponse(new OrderResource($order));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, int $id): JsonResponse
    {
        if (! $request->user()->isAdmin()) {
            return $this->forbiddenResponse();
        }

        if (isset($request->validator) && $request->validator->fails()) {
            return $this->orderUpdateInvalidResponse($request->validator->errors());
        }

        $order = Order::with('orderDetails.cat')->find($id);

        if (! $order) {
            return $this->orderNotFoundResponse();
        }

        $status = $request->validated('status');

        if ($status === 'cancelled') {
            if ($order->transaction()->exists()) {
                return $this->orderUpdateInvalidResponse(['status' => ['Cannot cancel an order that already has a recorded transaction.']]);
            }
        }

        DB::transaction(function () use ($order, $status) {
            $order->update(['status' => $status]);

            foreach ($order->orderDetails as $detail) {
                if ($status === 'complete') {
                    $detail->cat?->update(['status' => 'Sold']);
                } elseif ($status === 'cancelled') {
                    $detail->cat?->update(['status' => 'Available']);
                }
            }
        });

        return $this->orderUpdateSuccessResponse(new OrderResource($order->fresh(['orderDetails.cat', 'transaction', 'user'])));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        if (! $request->user()->isAdmin()) {
            return $this->forbiddenResponse();
        }

        $order = Order::with('orderDetails.cat')->find($id);

        if (! $order) {
            return $this->orderNotFoundResponse();
        }

        if ($order->transaction()->exists()) {
            return $this->orderUpdateInvalidResponse(['order' => ['Cannot delete an order that already has a recorded transaction.']]);
        }

        DB::transaction(function () use ($order) {
            foreach ($order->orderDetails as $detail) {
                $detail->cat?->update(['status' => 'Available']);
            }

            $order->delete();
        });

        return $this->orderDestroySuccessResponse();
    }
}
