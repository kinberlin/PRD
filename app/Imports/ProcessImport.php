<?php

namespace App\Imports;

use App\Models\Processes;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProcessImport implements ToModel, WithValidation, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Processes([
            'name' => $row['nom'],
            'surfix' => $row['abbreviation'],
        ]);
    }
    public function rules(): array
    {
        return [
            '*.nom' => 'required|string|max:50|unique:enterprise,name',
            '*.abbreviation' => ['required', 'max:10', 'unique:enterprise,surfix'],
        ];
    }

    public function customValidationMessages()
    {
        return [
            'abbreviation.required' => "Entrez un identifiant d'entreprise du système PRD.",
            'abbreviation.unique' => "Entrez une abréviation unique pour le nom de cette entreprise.",
            'nom.required' => 'Un nom est requis',
            'nom.unique' => 'Ce nom existe déja.',
        ];
    }
}
