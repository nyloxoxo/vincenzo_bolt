<?php
// HealthcareApiService.php
namespace App\Services;

use App\Services\UserSessionData;
use Illuminate\Support\Facades\Http;

class HealthcareApiService
{
    private $apiKey;
    private $baseUrl = 'https://marketplace.api.healthcare.gov/api/v1/';

    public function __construct()
    {
        $this->apiKey = '2oAmo3OoFWMmH6h25U2zMOKTTWUof6qi';

        // @todo Put this in config
        //config('services.healthcare.api_key');
    }

    public function processUserData(UserSessionData $userData): HealthcareApiResponse
    {
        $response = new HealthcareApiResponse();
        $age = $this->calculateAge($userData->dob);
        $allResults = [];
        $offset = 0;
        $limit = 10;
        $totalResults = null;

        try {
            $countyFIPS = $this->getCountyFIPS($userData->zipcode);

            $requestData = [
                "household" => [
                    "income" => (int)$userData->income,
                    "people" => [
                        [
                            "age" => $age,
                            "aptc_eligible" => true,
                            "gender" => $userData->gender,
                            "uses_tobacco" => $userData->uses_tobacco
                        ]
                    ]
                ],
                "market" => "Individual",
                "place" => [
                    "countyfips" => $countyFIPS,
                    "state" => $userData->state,
                    "zipcode" => $userData->zipcode
                ],
                "year" => (int)$userData->coverage_year,
                "limit" => $limit,
                "offset" => $offset,
            ];

            do {
                $requestData['offset'] = $offset;
                $apiResponse = $this->makeApiRequest('plans/search', $requestData, 'POST');

                if (isset($apiResponse['plans'])) {
                    $allResults = array_merge($allResults, $apiResponse['plans']);
                }

                if ($totalResults === null && isset($apiResponse['total'])) {
                    $totalResults = $apiResponse['total'];
                }

                $offset += $limit;
            } while ($offset < $totalResults);

            $response->data = ['plans' => $allResults];
        } catch (ApiException $e) {
            $response->errorMessage = $e->getMessage();
            $response->errorCode = $e->getCode();
        }

        return $response;
    }

    private function calculateAge($dob)
    {
        return \Carbon\Carbon::parse($dob)->age;
    }

    private function getCountyFIPS(string $zipcode)
    {
        $response = $this->makeApiRequest("counties/by/zip/{$zipcode}", [], 'GET');

        if (isset($response['error'])) {
            throw new \Exception($response['error']);
        }

        return $response['counties'][0]['fips'];
    }

    private function makeApiRequest($endpoint, $data = [], $method = 'POST')
    {
        $url = $this->baseUrl . $endpoint;

        if ($method === 'GET') {
            $data['apikey'] = $this->apiKey;
        } else {
            $url .= '?apikey=' . $this->apiKey;
        }

        $response = Http::withHeaders(['Content-Type' => 'application/json'])
            ->$method($url, $data);

        if ($response['error'] ?? false) {
            throw new ApiException($response['error'], $response['code']);
        }

        return $response->json();
    }
}
// HealthcareApiService.php
