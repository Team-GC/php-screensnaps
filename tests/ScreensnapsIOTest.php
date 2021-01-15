<?php

namespace TeamGC\ScreensnapsIO\Tests;

use PHPUnit\Framework\TestCase;

class ScreensnapsIOTest extends TestCase
{
    protected function setUp(): void
    {
        print_r($_SERVER);
        if (!getenv("API_KEY")) {
            $dotenv = \Dotenv\Dotenv::createUnsafeImmutable(__DIR__);
            $dotenv->load();
        }
    }

    public function testLibrary(): void
    {
        try {
            if (getenv("API_KEY") && getenv("USER_ID")) {
                $this->assertTrue(true);
            } else {
                $this->fail("You are missing USER_ID OR API_KEY from your .env file");
            }
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    public function testStatus(): void
    {
        $screensnapsIO = new \TeamGC\ScreensnapsIO\ScreensnapsIO(["apiKey" => getenv("API_KEY"), "userId" => getenv("USER_ID")]);

        $statusData = $screensnapsIO->status();

        if (isset($statusData->status) && $statusData->status === 'OK') {
            $this->assertTrue(true);
        } else {
            $this->fail(json_encode($statusData));
        }
    }

    public function testScreenshots(): void
    {
        $screensnapsIO = new \TeamGC\ScreensnapsIO\ScreensnapsIO(["apiKey" => getenv("API_KEY"), "userId" => getenv("USER_ID")]);

        $statusData = $screensnapsIO->screenshots(["offset" => 0, "limit" => 15]);

        if (isset($statusData->items)) {
            $this->assertTrue(true);
        } else {
            $this->fail(json_encode($statusData));
        }
    }
}
