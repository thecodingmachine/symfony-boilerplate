<?php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GooglePlacesService
{
    private $apiKey;
    private $client;

    public function __construct(HttpClientInterface $client = null)
    {
        // Reminder: protect this API Key using environment variables
        $this->apiKey = 'AIzaSyCtsEO-USJzDaAfFcJd30DwHigm4LhLQ5A';
        $this->client = $client ?: HttpClient::create();
    }

    public function searchPlaces(float $lat, float $lng, string $nextPageToken = null): array
    {
        $radius = 1000; // 1km in meters
        $type = 'point_of_interest';
        
        $endpoint = sprintf(
            'https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=%s,%s&radius=%s&type=%s&key=%s',
            $lat,
            $lng,
            $radius,
            $type,
            $this->apiKey
        );
    
        if ($nextPageToken) {
            $endpoint .= '&pagetoken=' . $nextPageToken;
        }
    
        $response = $this->client->request('GET', $endpoint);
        
        if ($response->getStatusCode() !== 200) {
            throw new \RuntimeException('Error fetching data from Google Places API');
        }
    
        return $response->toArray();
    }
    
}
