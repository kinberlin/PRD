<?php

namespace App\Imports;

use App\Models\Users;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;

class UserImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Users([
            'enterprise' => $row['ENTREPRISE'],
            'firstname' => $row['NOM'], // Adjust the keys to match your Excel column headers
            'lastname' => $row['PRENOM'],
            'email' => $row['EMAIL'],
            'phone' => $row['TÉLÉPHONE'],
            'department' => $row['DÉPARTEMENT'],
            'poste' => $row['POSTE OCCUPÉ'],
            'matricule' => $row['MATRICULE'],
            'password' => bcrypt($row['MOT DE PASSE']),
        ]);
    }

    public function rules(): array
    {
        return [
            '*.NOM' => 'required|string|max:50',
            '*.TÉLÉPHONE' => 'required|string|max:15',
            '*.MOT DE PASSE' => 'required|string|max:50',
            '*.POSTE OCCUPÉ' => 'required|string|max:50',
            '*.MATRICULE' => 'required|string|max:50',
            '*.EMAIL' => ['required', 'string', 'max:50', 'unique:users,email'],
            '*.ENTREPRISE' => ['required', 'string', 'max:50', Rule::exists('enterprise', 'name')],
        ];
    }
    public function customValidationMessages()
    {
        return [
            'ENTREPRISE.required' => 'Entrez un identifiant entreprise du systeme PRD.',
            'NOM.required' => 'Un nom est requis',
            'PRENOM.required' => 'Un prenom est requis',
            'TÉLÉPHONE.required' => 'Un no. de téléphone est requis',
            'EMAIL.required' => 'Un email est requis',
            'EMAIL.unique' => 'Cette addresse mail est déja prise',
            'MOT DE PASSE.required' => 'Un mot de passe est requis',
            'MATRICULE.required' => 'Un matricule est requis',
            'MATRICULE.unique' => 'Ce matricule est déja pris',
        ];
    }
}
