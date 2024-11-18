<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use App\Service\WeatherUtil;
use Symfony\Component\HttpFoundation\Response;

class WeatherApiController extends AbstractController
{
    private WeatherUtil $weatherUtil;
    public function __construct(WeatherUtil $weatherUtil)
    {
        $this->weatherUtil = $weatherUtil;
    }
    #[Route('/api/v1/weather', name: 'app_weather_api', methods: ['GET'])]
    public function index(
        #[MapQueryParameter] ?string $country = null,
        #[MapQueryParameter] ?string $city = null,
        #[MapQueryParameter] string $format = 'json',
        #[MapQueryParameter('twig')] bool $twig = false
    ): Response {
        $measurements = $this->weatherUtil->getWeatherForCountryAndCity($country, $city);
        if ($twig) {
            $template = $format === 'csv'
                ? 'weather_api/index.csv.twig'
                : 'weather_api/index.json.twig';
            return $this->render($template, [
                'city' => $city,
                'country' => $country,
                'measurements' => $measurements,
            ]);
        }
        if ($format === 'json') {
            return $this->json([
                'city' => $city,
                'country' => $country,
                'measurements' => array_map(fn($m) => [
                    'date' => $m->getDate()->format('Y-m-d'),
                    'celsius' => $m->getTemperature(),
                    'fahrenheit' => $m->getFahrenheit(),
                ], $measurements),
            ]);
        }
        $csvData = ["city,country,date,celsius"];
        foreach ($measurements as $m) {
            $csvData[] = sprintf(
                '%s,%s,%s,%.2f',
                $city,
                $country,
                $m->getDate()->format('Y-m-d'),
                $m->getTemperature(),
                $m->getFahrenheit()
            );
        }
        return new Response(implode("\n", $csvData), 200, [
            'Content-Type' => 'text/plain',
        ]);
    }
}
