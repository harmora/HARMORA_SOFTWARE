<?php

namespace App\Imports;

use App\Models\Fournisseur;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class FournisseurImport implements ToCollection, WithHeadingRow
{
    protected $entrepriseId;

    /**
     * Create a new import instance.
     *
     * @param int $entrepriseId
     */
    public function __construct(int $entrepriseId)
    {
        $this->entrepriseId = $entrepriseId;
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
            $fournisseur = Fournisseur::where('email', $row['email'])->first();
            if ($fournisseur) {
                $fournisseur->update([
                    'name' => $row['name'],
                    'phone' => $row['phone'],
                    'city' => $row['city'],
                    'country' => $row['country'],
                ]);
            } else {
                Fournisseur::create([
                    'name' => $row['name'],
                    'email' => $row['email'],
                    'phone' => $row['phone'],
                    'city' => $row['city'],
                    'country' => $row['country'],
                    'entreprise_id' => $this->entrepriseId,
                ]);
            }
        }
    }
}
