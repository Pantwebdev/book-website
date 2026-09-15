<?php

namespace App\Imports;

use App\Models\CheckAvailability;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CheckAvailabilityImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new CheckAvailability([
            'pincode' => $row['pincode'],
            'status' => 1,
        ]);
    }
}
