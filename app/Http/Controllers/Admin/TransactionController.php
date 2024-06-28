<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MainStoreRequest;
use App\Http\Requests\OutlestStoreRequest;
use App\Http\Requests\TransactionDetailRequest;
use App\Models\Transaction;
use App\Services\DataService;
use App\Services\TransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{
    private $transactionService;
    private $dataService;

    public function __construct(TransactionService $transactionService, DataService $dataService)
    {
        $this->middleware('check.role:3')->only(['createMainStoreData', 'createOutletStoreData', 'createTransactionDetail', 'approveTxDetail']);
        $this->middleware('check.role:4')->only(['approveTxDetail']);

        $this->transactionService = $transactionService;
        $this->dataService = $dataService;
    }

    public function createMainStoreData(MainStoreRequest $request): JsonResponse
    {
        $storeData = $request->validated();
        $storeData['last_active_role'] = Auth::user()->active_role;
        $data = $this->dataService->createData('MainStore', $storeData);
        return jsonResponse('Store data created', $data, Response::HTTP_CREATED);
    }

    public function createOutletStoreData(OutlestStoreRequest $request): JsonResponse
    {
        $outletStoreData = $request->validated();
        $outletStoreData['last_active_role'] = Auth::user()->active_role;
        $data = $this->dataService->createData('OutletStore', $outletStoreData);
        return jsonResponse('Outlet store data created', $data, Response::HTTP_CREATED);
    }

    public function createTransactionDetail(TransactionDetailRequest $request): JsonResponse
    {
        $transactionDetailsData = $request->validated();
        $transactionDetailsData['last_active_role'] = Auth::user()->active_role;
        $data = $this->transactionService->storeTransactionDetails($transactionDetailsData);
        return jsonResponse('Transaction details stored successfully', $data, Response::HTTP_CREATED);
    }

    public function approveTxDetail($id): JsonResponse
    {
        $data = $this->transactionService->acceptTransactionDetail($id);
        if ($data === true) {
            return jsonResponse('Transaction details approved', $data, Response::HTTP_OK);
        }
        return jsonResponse('Failed to approve transaction', $data, Response::HTTP_CONFLICT);
    }

    public function getTransactions(): JsonResponse
    {
        // Fetch transactions
        $transactions = Transaction::get();

        // Initialize an array to store transformed data
        $transformedData = [];

        // Loop through each transaction to access details
        foreach ($transactions as $transaction) {
            // Access details relationship for each transaction
            $details = $transaction->details;

            // Optionally, you can load the 'mainStore' relationship on each detail
            $details->load('mainStore');

            // Add the transaction with its details to the transformed data array
            $transformedData[] = [
                'transaction' => $transaction,
                'details' => $details,
            ];
        }

        // Return a JSON response with the transformed data
        return response()->json([
            'message' => 'Transactions data fetched',
            'data' => $transformedData,
        ], Response::HTTP_OK);

    }
    public function getApprovedTransactions(): JsonResponse
    {
        $data = $this->transactionService->getApprovedTransactions();
        return jsonResponse('Transactions data fetched', $data, Response::HTTP_CREATED);
    }

    public function getMainStoreProducts(): JsonResponse
    {
        $data = $this->dataService->getModelData('MainStore');
        return jsonResponse('Main store products fetched', $data, Response::HTTP_OK);
    }

    public function getOutletStoreProducts(): JsonResponse
    {
        $data = $this->dataService->getModelData('OutletStore');
        return jsonResponse('Outlet store products fetched', $data, Response::HTTP_OK);
    }
}
