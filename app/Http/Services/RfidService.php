<?php

namespace App\Http\Services;

use App\Models\RfidCards;
use App\Models\StudentAccounts;
use Illuminate\Support\Facades\Storage;


class RfidService
{
    use \App\Traits\HasAesEncryption;

    /* Get all data RFID Cards */
    public function getAllRfidCards()
    {
        return RfidCards::with('studentAccount.userData')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /* Get all students */
    public function getAllStudents()
    {
        return StudentAccounts::with('userData')
            ->where('status', 'active')
            ->whereHas('userData', function ($query) {
                $query->orderBy('name', 'asc');
            })
            ->get();
    }

    /* Create RFID Card */
    public function createRfidCard(array $data)
    {
        // validate length of RFID
        if (strlen($data['rfid_number']) !== 10){
            return redirect()->back()->with('error', 'Sorry, please rescan your RFID Card.');
        }
        
        // validate length of PIN
        if (strlen($data['rfid_pin']) !== 6){
            return redirect()->back()->with('error', 'Sorry, please re-enter your PIN.');
        }

        // Student just can have one RFID Card
        $existingCard = RfidCards::where('student_id', $data['student_id'])
            ->where('status', 'active')
            ->first();
        if ($existingCard) {
            return redirect()->back()->with('error', 'This student already has an active RFID card. Please deactivate the existing card before creating a new one.');
        }

        // Check if the RFID Number already exists
        $activeCards = RfidCards::where('status', 'active')->get();
        foreach ($activeCards as $card) {
            if ($this->aesDecrypt($card->card_number) === $data['rfid_number']) {
                return redirect()->back()->with('error', 'RFID Number already exists. Please use a different RFID Number.');
            }
        }

        try {
            // Create new RFID card
            RfidCards::create([
                'student_id' => $data['student_id'],
                'card_number' => $this->aesEncrypt($data['rfid_number']),
                'card_pin' => $this->aesEncrypt($data['rfid_pin']),
                'status' => $data['status'],
                'description' => $data['description'] ?? null,
            ]);
            return redirect()->route('rfid.index')->with('success', 'RFID card created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create RFID card. Please try again. Error: ' . $e->getMessage());
        }
    }

    /* Update RFID Card */
    public function updateRfidCard(int $id, array $data){
        // If Has PIN (Student will update RFID PIN), then validate RFID Number
        if (isset($data['rfid_pin']) && !empty($data['rfid_pin'])) {
            // validate length of RFID Number
            if (strlen($data['rfid_number']) !== 10) {
                return redirect()->back()->with('error', 'Sorry, please rescan your RFID Card.');
            }

            // validate length of PIN
            if (strlen($data['rfid_pin']) !== 6){
                return redirect()->back()->with('error', 'Sorry, please re-enter your PIN.');
            }

            // Validate RFID Number 
            $card = RfidCards::find($id);
            if ($card && $data['rfid_number'] !== $this->aesDecrypt($card->card_number)) {
                return redirect()->back()->with('error', 'RFID Number does not match the existing card. Please rescan card and check again.');
            }
        }

        // Check status, if status is inactive, then description attached with "This card is inactived by Admin"
        if ($data['status'] === 'inactive') {
            $data['description'] = 'This card is inactived by Admin.' . ($data['description'] ?? '');
        }

        try {
            // Update RFID card
            $rfidCard = RfidCards::findOrFail($id);
            $rfidCard->update([
                'card_number' => isset($data['rfid_number']) ? $this->aesEncrypt($data['rfid_number']) : $rfidCard->card_number,
                'card_pin' => isset($data['rfid_pin']) ? $this->aesEncrypt($data['rfid_pin']) : $rfidCard->card_pin,
                'status' => $data['status'],
                'description' => $data['description'] ?? null,
            ]);
            return redirect()->route('rfid.index')->with('success', 'RFID card updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update RFID card. Please try again. Error: ' . $e->getMessage());
        }
    }

}