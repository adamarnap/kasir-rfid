<?php

namespace App\Http\Services;

use App\Models\RfidCards;

class BalanceService
{
    use \App\Traits\HasAesEncryption;

    // Get Data Balance RFID by RFID Number - via (Ajax)
    public function getBalanceByRfidNumber(string $rfidNumber)
    {
        $data = RfidCards::with('studentAccount.userData')
            ->get() // Ambil semua dulu
            ->first(function ($card) use ($rfidNumber) {
                return $this->aesDecrypt($card->card_number) === $rfidNumber;
            });

        if (!$data) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }
        return response()->json([
            'rfid_number' => $rfidNumber,
            'balance' => optional($data->studentAccount)->balance ?? 0,
            'student_name' => optional(optional($data->studentAccount)->userData)->name ?? 'Unknown',
        ]);
    }
}
