<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Location;
use App\Entity\Measurement;
use App\Repository\LocationRepository;

class WeatherUtil
{
    private LocationRepository $locationRepository;

    public function __construct(LocationRepository $locationRepository)
    {
        $this->locationRepository = $locationRepository;
    }

    /**
     * @return Measurement[]
     */
    public function getWeatherForLocation(Location $location): array
    {
        return $location->getMeasurements()->toArray();
    }

    /**
     * @return Measurement[]
     */
    public function getWeatherForCountryAndCity(string $countryCode, string $city): array
    {
        $location = $this->locationRepository->findByCityAndCountry($city, $countryCode);
        return $location ? $this->getWeatherForLocation($location) : [];
    }
}