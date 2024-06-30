<?php

namespace App\Services;

use App\Models\MainStore;
use App\Models\Transaction;
use App\Models\TransactionDetails;
use Illuminate\Support\Facades\Auth;

class TransactionService
{
    public function storeTransactionDetails(array $data): array
    {
        // Create Fruit records and associate them with the FruitCat
        $txDetailsIds = [];
        foreach ($data as $dataValue) {
            $transactionDetailArray = [
                'product_id' => $dataValue['product_id'],
                'price' => $dataValue['price'],
                'quantity' => $dataValue['quantity'],
            ];


            $txDetails = TransactionDetails::create($transactionDetailArray);
            //use the product_id to deduct the quantity of the main_store table for that product
            $txDetailsIds[] = $txDetails->id;
        }

        $transaction = new Transaction();
        // Update the Transaction with the Transaction Detail IDs
        $transaction->created_by = Auth::user()->id;
        $transaction->transaction_detail_ids = json_encode($txDetailsIds);
        $transaction->save();
        return $data;
    }

    public function acceptTransactionDetail(int $id)
    {
        $transaction = Transaction::find($id);
        if (empty($transaction['accepted_by'])) {
            $txDetailsIds = json_decode($transaction->transaction_detail_ids, 2);
            foreach ($txDetailsIds as $txDetailsId) {
                $txDetails = TransactionDetails::find($txDetailsId);

                // Deduct the quantity from the main_store table for the corresponding product
                MainStore::whereId($txDetails['product_id'])->decrement('quantity', $txDetails['quantity']);
            }
            $transaction->accepted_by = Auth::user()->id;
            $transaction->update();
            return true;
        }
        return false;
    }

    public function approveTransactionDetail(int $id)
    {
        $transaction = Transaction::find($id);
        if (!empty($transaction['accepted_by'])) {
            $transaction->approved_by = Auth::user()->id;
            $transaction->update();
            return true;
        }
        return false;
    }

    public function getTransactions()
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
        return $transformedData;
    }

    public function getAcceptedTransactions()
    {
        return Transaction::whereNotNull('accepted_by')->get();
    }

    public function getApprovedTransactions()
    {
        return Transaction::whereNotNull('approved_by')->get();
    }
}
