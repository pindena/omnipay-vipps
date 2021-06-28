<?php

namespace Pindena\Omnipay\Vipps\Tests;

use \Omnipay\Tests\TestCase as BaseTestCase;

class ResponseTestCase extends BaseTestCase
{
    /**
     * Get a mock response from tests/Mock, populate with replacements if needed.
     *
     * @param $fileName
     * @param array $replacements
     * @return array
     */
    public function getMockResponse($fileName, $replacements = [])
    {
        $path = __DIR__ . "/Mock/$fileName";

        if (! file_exists($path)) {
            throw new \RuntimeException("$fileName not found: $path");
        }

        return array_merge(json_decode(file_get_contents($path), true), $replacements);;
    }
}