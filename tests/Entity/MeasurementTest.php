<?php

namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Measurement;

class MeasurementTest extends TestCase
{
    public function dataGetFahrenheit(): array
    {
        return [
            [0, 32],
            [-100, -148],
            [100, 212],
            [0.5, 32.9],
            [-0.5, 31.1],
            [37, 98.6],
            [0.1, 32.18],
            [20.5, 68.9],
            [-15.3, 4.46],
            [50.8, 123.44],
        ];
    }

    /**
     * @dataProvider dataGetFahrenheit
     */
    public function testGetFahrenheit($celsius, $expectedFahrenheit): void
    {
        $measurement = new Measurement();
        $measurement->setTemperature($celsius);
        $this->assertEquals(
            $expectedFahrenheit,
            round($measurement->getFahrenheit(), 2),
            sprintf('Expected %.2f°F for %.2f°C, got %.2f°F', $expectedFahrenheit, $celsius, $measurement->getFahrenheit())
        );
    }
}
