<?php

namespace App\Imports;

use App\Models\Enterprise;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class EnterpriseImport implements ToModel, WithValidation, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Enterprise([
            'name' => $row['nom'],
            'surfix' => $row['abbreviation'],
        ]);
    }
    public function rules(): array
    {
        return [
            '*.nom' => 'required|string|max:50|unique',
            '*.surfix' => ['required', 'max:10', 'unique'],
        ];
    }

    public function customValidationMessages()
    {
        return [
            'surfix.required' => "Entrez un identifiant d'entreprise du système PRD.",
            'surfix.unique' => "Entrez une abréviation unique pour le nom de cette entreprise.",
            'nom.required' => 'Un nom est requis',
            'nom.unique' => 'Ce nom existe déja.',
        ];
    }
}
