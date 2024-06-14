<?php

use App\Models\Transaction;
use App\Models\TransactionDetails;
use Illuminate\Http\JsonResponse;

class TransactionService
{
    public function storeTransactionDetails(array $data): JsonResponse
    {
        $validatedData = $request->validate([
            'category' => 'required|string',
            'fruits' => 'required|array',
            'fruits.*' => 'required|string|distinct',
        ]);

        $category = $validatedData['category'];
        $fruitNames = $validatedData['fruits'];

        // Create the FruitCat record
        $transaction = Transaction::create([
            'type' => 'procurement',
        ]);

        // Create Fruit records and associate them with the FruitCat
        $txDetailsIds = [];
        foreach ($fruitNames as $fruitName) {
            $txDetails = TransactionDetails::create($data);
            $txDetailsIds[] = $txDetails->id;
        }

        // Update the Transaction with the Transaction Detail IDs
        $transaction->transaction_detail_ids = $txDetailsIds;
        $transaction->save();

        return response()->json([
            'message' => 'Fruits and category stored successfully.',
            'data' => [
                'category' => $category,
                'fruits' => $fruitNames,
            ],
        ], 201);
    }
}
