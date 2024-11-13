<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Service\WeatherUtil;
use App\Repository\LocationRepository;

#[AsCommand(
    name: 'weather:country-city',
    description: 'Add a short description for your command',
)]
class WeatherCountryCityCommand extends Command
{
    private WeatherUtil $weatherUtil;
    private LocationRepository $locationRepository;

    public function __construct(WeatherUtil $weatherUtil, LocationRepository $locationRepository)
    {
        $this->weatherUtil = $weatherUtil;
        $this->locationRepository = $locationRepository;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('countryCode', InputArgument::REQUIRED, 'Country code')
            ->addArgument('cityName', InputArgument::REQUIRED, 'City name');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $countryCode = $input->getArgument('countryCode');
        $cityName = $input->getArgument('cityName');
        $location = $this->locationRepository->findByCityAndCountry($cityName, $countryCode);
        if (!$location) {
            $io->error('Location not found.');
            return Command::FAILURE;
        }
        $measurements = $this->weatherUtil->getWeatherForLocation($location);
        $io->writeln(sprintf('Location: %s, %s', $location->getCity(), $location->getCountry()));
        foreach ($measurements as $measurement) {
            $io->writeln(sprintf("\t%s: %s",
                $measurement->getDate()->format('Y-m-d'),
                $measurement->getTemperature()
            ));
        }
        return Command::SUCCESS;
    }
}