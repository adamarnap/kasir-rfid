<?php

namespace App\Http\Services;

use App\Models\RfidCards;
use Illuminate\Support\Facades\Storage;

class TopupService
{
    /**
     * Trait for AES encryption and decryption.
     */
    use \App\Traits\HasAesEncryption;

    /* Topup Create */
    public function createTopup(array $data)
    {
        // Validate and process the top-up data
        $rfidNumber = $data['rfid_number'];
        $amount = $data['amount'];

        // Find Student ID by RFID Number
        $rfidCard = null;
        $dataAllActiveRfidCards = RfidCards::where('status', 'active')->get();
        foreach ($dataAllActiveRfidCards as $card) {
            if ($this->aesDecrypt($card->card_number) === $rfidNumber) {
                $rfidCard = $card;
                break;
            }
        }

        // Check if RFID card exists and is active
        if (!$rfidCard) {
            return redirect()->back()->withErrors('RFID card not found or inactive. Please re-scan the card and ensure the card is not damaged.');
        }

        // Get Student Account
        $studentAccount = $rfidCard->studentAccount;
        if (!$studentAccount) {
            return redirect()->back()->withErrors('Student account not found for this RFID card.');
        }

        // Topup Process
        try{
            // Start transaction
            \DB::beginTransaction();
            
            // Create Top Up record
            $studentAccount->TopUpTransactions()->create([
            'rfid_number' => $rfidNumber,
            'amount' => $amount,
            'cashier_id' => auth()->id(),
            ]);

            // Update Student Account balance
            $studentAccount->balance += $amount;
            $studentAccount->save();

            // Commit transaction
            \DB::commit();

            $studentName = $studentAccount->userData->name ?? 'No Name';
            return redirect()->route('topup.index')->with(
            'success',
            "Top Up RFID Card (Student Name: {$studentName} | Amount: Rp. {$amount}) successful!"
            );
        }catch (\Exception $e) {
            // Rollback transaction on error
            \DB::rollBack();
            return redirect()->back()->withErrors('Top Up failed: ' . $e->getMessage());
        }
    }
}