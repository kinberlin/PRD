<?php

namespace App\Imports;

use App\Models\Users;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class UserImport implements ToModel, WithValidation, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Users([
            'enterprise' => $row['entreprise'],
            'firstname' => $row['nom'], // Adjust the keys to match your Excel column headers
            'lastname' => $row['prenom'],
            'email' => $row['email'],
            'phone' => $row['telephone'],
            'department' => $row['departement'],
            'poste' => $row['poste_occupe'],
            'matricule' => $row['matricule'],
            'password' => bcrypt($row['mot_de_passe']),
        ]);
    }

    public function rules(): array
    {
        return [
            '*.nom' => 'required|string|max:50',
            '*.telephone' => 'required|max:15',
            '*.mot_de_passe' => 'required|max:50',
            '*.poste_occupe' => 'required|string|max:50',
            '*.matricule' => 'required|string|max:50|unique:users,matricule',
            '*.email' => ['required', 'string', 'max:50', 'unique:users,email'],
            '*.entreprise' => ['required', 'max:50', Rule::exists('enterprise', 'id')],
        ];
    }
    public function customValidationMessages()
    {
        return [
            'entreprise.required' => 'Entrez un identifiant entreprise du système PRD.',
            'nom.required' => 'Un nom est requis',
            'prenom.required' => 'Un prenom est requis',
            'telephone.required' => 'Un no. de téléphone est requis',
            'email.required' => 'Un email est requis',
            'email.unique' => 'Cette addresse mail est déja prise',
            'mot_de_passe.required' => 'Un mot de passe est requis',
            'matricule.required' => 'Un matricule est requis',
            'matricule.unique' => 'Ce matricule est déja pris',
        ];
    }
}
