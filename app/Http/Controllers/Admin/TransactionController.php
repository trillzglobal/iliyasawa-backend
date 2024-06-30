<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MainStoreRequest;
use App\Http\Requests\OutlestStoreRequest;
use App\Http\Requests\TransactionDetailRequest;
use App\Services\DataService;
use App\Services\TransactionService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{
    private $transactionService;
    private $dataService;

    public function __construct(TransactionService $transactionService, DataService $dataService)
    {
        $this->middleware('check.role:3')->only(['createMainStoreData', 'createOutletStoreData', 'createTransactionDetail']);
        $this->middleware('check.role:4')->only(['acceptTxDetail']);
        $this->middleware('check.role:2')->only(['approveTxDetail']);

        $this->transactionService = $transactionService;
        $this->dataService = $dataService;
    }

    public function createMainStoreData(MainStoreRequest $request): JsonResponse
    {
        try {
            $storeData = $request->validated();
            if ($storeData) {
                $storeData['last_active_role'] = Auth::user()->active_role;
                $data = $this->dataService->createData('MainStore', $storeData);
                return jsonResponse('Store data created', $data, Response::HTTP_CREATED);
            }
            return jsonResponse('Failed creating store data', [], Response::HTTP_BAD_REQUEST);
        } catch (Exception $exception) {
            return jsonResponse($exception->getMessage() . '::On Line::' . $exception->getLine(), [], Response::HTTP_BAD_REQUEST);
        }
    }

    public function createOutletStoreData(OutlestStoreRequest $request): JsonResponse
    {
        try {
            $outletStoreData = $request->validated();
            if ($outletStoreData) {
                $outletStoreData['last_active_role'] = Auth::user()->active_role;
                $data = $this->dataService->createData('OutletStore', $outletStoreData);
                return jsonResponse('Outlet store data created', $data, Response::HTTP_CREATED);
            }
            return jsonResponse('Failed creating outlet data', [], Response::HTTP_BAD_REQUEST);
        } catch (Exception $exception) {
            return jsonResponse($exception->getMessage() . '::On Line::' . $exception->getLine(), [], Response::HTTP_BAD_REQUEST);
        }
    }

    public function createTransactionDetail(TransactionDetailRequest $request): JsonResponse
    {
        try {
            $transactionDetailsData = $request->validated();
            if ($transactionDetailsData) {
                $transactionDetailsData['last_active_role'] = Auth::user()->active_role;
                $data = $this->transactionService->storeTransactionDetails($transactionDetailsData);
                return jsonResponse('Transaction details stored successfully', $data, Response::HTTP_CREATED);
            }
            return jsonResponse('Failed creating transaction details data', [], Response::HTTP_BAD_REQUEST);
        } catch (Exception $exception) {
            return jsonResponse($exception->getMessage() . '::On Line::' . $exception->getLine(), [], Response::HTTP_BAD_REQUEST);
        }
    }

    public function acceptTxDetail($id): JsonResponse
    {
        try {
            $data = $this->transactionService->acceptTransactionDetail($id);
            if ($data === true) {
                return jsonResponse('Transaction details approved', $data, Response::HTTP_ACCEPTED);
            }
            return jsonResponse('Failed to approve transaction', $data, Response::HTTP_CONFLICT);
        } catch (Exception $exception) {
            return jsonResponse($exception->getMessage() . '::On Line::' . $exception->getLine(), [], Response::HTTP_BAD_REQUEST);
        }
    }

    public function approveTxDetail($id): JsonResponse
    {
        try {
            $data = $this->transactionService->approveTransactionDetail($id);
            if ($data === true) {
                return jsonResponse('Transaction details approved', true, Response::HTTP_ACCEPTED);
            }
            return jsonResponse('Failed to approve transaction', $data, Response::HTTP_CONFLICT);
        } catch (Exception $exception) {
            return jsonResponse($exception->getMessage() . '::On Line::' . $exception->getLine(), [], Response::HTTP_BAD_REQUEST);
        }
    }

    public function getTransactions(): JsonResponse
    {
        try {
            $transactionsData = $this->transactionService->getTransactions();
            if ($transactionsData) {
                // Return a JSON response with the transactions data
                return jsonResponse('Transactions data fetched', $transactionsData, Response::HTTP_OK);
            }
            return jsonResponse('Failed to fetch transactions data fetched', [], Response::HTTP_BAD_REQUEST);
        } catch (Exception $exception) {
            return jsonResponse($exception->getMessage() . '::On Line::' . $exception->getLine(), [], Response::HTTP_BAD_REQUEST);
        }
    }

    public function getAcceptedTransactions(): JsonResponse
    {
        try {
            $data = $this->transactionService->getAcceptedTransactions();
            return jsonResponse('Accepted transactions data fetched', $data, Response::HTTP_OK);
        } catch (Exception $exception) {
            return jsonResponse($exception->getMessage() . '::On Line::' . $exception->getLine(), [], Response::HTTP_BAD_REQUEST);
        }
    }

    public function getApprovedTransactions(): JsonResponse
    {
        try {
            $data = $this->transactionService->getApprovedTransactions();
            return jsonResponse('Approved transactions data fetched', $data, Response::HTTP_OK);
        } catch (Exception $exception) {
            return jsonResponse($exception->getMessage() . '::On Line::' . $exception->getLine(), [], Response::HTTP_BAD_REQUEST);
        }
    }

    public function getMainStoreProducts(): JsonResponse
    {
        try {
            $data = $this->dataService->getModelData('MainStore');
            return jsonResponse('Main store products fetched', $data, Response::HTTP_OK);
        } catch (Exception $exception) {
            return jsonResponse($exception->getMessage() . '::On Line::' . $exception->getLine(), [], Response::HTTP_BAD_REQUEST);
        }
    }

    public function getOutletStoreProducts(): JsonResponse
    {
        try {
            $data = $this->dataService->getModelData('OutletStore');
            return jsonResponse('Outlet store products fetched', $data, Response::HTTP_OK);
        } catch (Exception $exception) {
            return jsonResponse($exception->getMessage() . '::On Line::' . $exception->getLine(), [], Response::HTTP_BAD_REQUEST);
        }
    }
}
