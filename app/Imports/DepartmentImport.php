<?php

namespace App\Imports;

use App\Models\Department;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class DepartmentImport implements  ToModel, WithValidation, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Department([
            'enterprise' => $row['entreprise'],
            'name' => $row['nom'],
        ]);
    }

        public function rules(): array
    {
        return [
            '*.nom' => 'required|string|max:50|unique:department,name',
            '*.entreprise' => ['required', 'max:50', Rule::exists('enterprise', 'id')],
        ];
    }

        public function customValidationMessages()
    {
        return [
            'entreprise.required' => 'Entrez un identifiant entreprise du système PRD.',
            'nom.required' => 'Un nom est requis',
            'nom.unique' => 'Ce nom existe déja.',
        ];
    }
}
