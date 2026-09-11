<?php

namespace App\Http\Controllers;

use App\Http\ApiTraits\TransactionAPIResponses;
use App\Http\Requests\CreateTransactionRequest;
use App\Http\Requests\IndexTransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    use TransactionAPIResponses;

    /**
     * Display a listing of the resource.
     */
    public function index(IndexTransactionRequest $request): JsonResponse
    {
        if (! $request->user()->isAdmin()) {
            return $this->forbiddenResponse();
        }

        $transactions = Transaction::query()
            ->with('order')
            ->when($request->orderId(), fn ($query, $orderId) => $query->where('order_id', $orderId))
            ->latest('id')
            ->paginate($request->integer('perPage', 10))
            ->withQueryString();

        return $this->transactionIndexSuccessResponse($transactions->toResourceCollection());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateTransactionRequest $request): JsonResponse
    {
        if (! $request->user()->isAdmin()) {
            return $this->forbiddenResponse();
        }

        if (isset($request->validator) && $request->validator->fails()) {
            return $this->transactionCreateFailedResponse($request->validator->errors());
        }

        $order = Order::find($request->safe()->integer('order_id'));

        if (! $order) {
            return $this->transactionCreateFailedResponse(['order_id' => ['Order not found.']]);
        }

        if (Transaction::where('order_id', $order->id)->exists()) {
            return $this->transactionCreateFailedResponse(['order_id' => ['A transaction is already recorded for this order.']]);
        }

        $transaction = DB::transaction(function () use ($request, $order) {
            $transaction = Transaction::create([
                'order_id' => $order->id,
                'transaction_code' => $request->safe()->string('transaction_code'),
                'transaction_id' => $request->safe()->string('transaction_id'),
                'transaction_time' => $request->safe()->string('transaction_time'),
                'amount' => $order->total,
                'recorded_by' => $request->user()->id,
            ]);

            if ($order->status === 'pending') {
                $order->update(['status' => 'sending']);
            }

            return $transaction;
        });

        $transaction->load('order');

        return $this->transactionCreateSuccessResponse(new TransactionResource($transaction));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, int $id): JsonResponse
    {
        if (! $request->user()->isAdmin()) {
            return $this->forbiddenResponse();
        }

        $transaction = Transaction::with('order')->find($id);

        if (! $transaction) {
            return $this->transactionNotFoundResponse();
        }

        return $this->transactionShowSuccessResponse(new TransactionResource($transaction));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        if (! $request->user()->isAdmin()) {
            return $this->forbiddenResponse();
        }

        $transaction = Transaction::find($id);

        if (! $transaction) {
            return $this->transactionNotFoundResponse();
        }

        $transaction->delete();

        return $this->transactionDestroySuccessResponse();
    }
}
