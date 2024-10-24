<?php

namespace App\Services;

class UserSessionData
{
    // Eligibility Data
    public string $name;
    public string $dob;
    public string $street_address;
    public string $city;
    public string $state;
    public string $zipcode;
    public float $income;
    public string $gender;
    public bool $uses_tobacco;
    public int $coverage_year;

    // Usage Data
    public ?array $services = null;
    public ?array $additionalServices = null;

    public function setEligibilityData(array $data)
    {
        $this->name = $data['name'];
        $this->dob = $data['dob'];
        $this->street_address = $data['street_address'];
        $this->city = $data['city'];
        $this->state = $data['state'];
        $this->zipcode = $data['zipcode'];
        $this->income = $data['income'];
        $this->gender = $data['gender'];
        $this->uses_tobacco = $data['uses_tobacco'];
        $this->coverage_year = $data['coverage_year'];
    }

    public function getEligibilityData(): array
    {
        return [
            'name' => $this->name,
            'dob' => $this->dob,
            'street_address' => $this->street_address,
            'city' => $this->city,
            'state' => $this->state,
            'zipcode' => $this->zipcode,
            'income' => $this->income,
            'gender' => $this->gender,
            'uses_tobacco' => $this->uses_tobacco,
            'coverage_year' => $this->coverage_year,
        ];
    }

    public function toArray(): array
    {
        return [
            'eligibilityData' => [
                'name' => $this->name,
                'dob' => $this->dob,
                'street_address' => $this->street_address,
                'city' => $this->city,
                'state' => $this->state,
                'zipcode' => $this->zipcode,
                'income' => $this->income,
                'gender' => $this->gender,
                'uses_tobacco' => $this->uses_tobacco,
                'coverage_year' => $this->coverage_year,
            ],
            'usageData' => [
                'services' => $this->services,
                'additionalServices' => $this->additionalServices,
            ],
        ];
    }
}
