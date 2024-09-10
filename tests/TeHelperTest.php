<?php

namespace Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use DTApi\Helpers\TeHelper;


class TeHelperTest extends TestCase
{
    public static function timeProvider(): array
    {
        $provider = [];
        $due = Carbon::parse('2024-09-15 00:00');
        
        $data = [
            80, 50, 100, 10
        ];
        foreach ($data as $value) {
            $created = $due->addHours($value);
            switch (true) {
                case ($value <= 90):
                    $expected = $due;
                    $provider[] = [$due->format('Y-m-d H:i:s'), $created->format('Y-m-d H:i:s'), $expected->format('Y-m-d H:i:s')];
                    break;

                case ($value <= 24):
                    $expected = $created->addMinutes(90);
                    $provider[] = [$due->format('Y-m-d H:i:s'), $created->format('Y-m-d H:i:s'), $expected->format('Y-m-d H:i:s')];
                    break;

                case ($value > 24 && $value <= 72):
                    $expected = $created->addMinutes(16);
                    $provider[] = [$due->format('Y-m-d H:i:s'), $created->format('Y-m-d H:i:s'), $expected->format('Y-m-d H:i:s')];
                    break;
                
                default:
                    $expected = $due->subHours(48);
                    $provider[] = [$due->format('Y-m-d H:i:s'), $created->format('Y-m-d H:i:s'), $expected->format('Y-m-d H:i:s')];
                    break;
            }
        }
        return $provider;
    }

    #[DataProvider('timeProvider')]
    public function testWillExpireAt($due, $created, $expected)
    {
        $this->assertEquals($expected, TeHelper::willExpireAt($due, $created));
    }
}
