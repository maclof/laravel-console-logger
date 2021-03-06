<?php

namespace Illuminated\Console\ConsoleLogger\Tests;

use Illuminate\Contracts\Console\Kernel as KernelContract;
use Illuminate\Support\Facades\File;
use Illuminated\Testing\TestingTools;
use Kernel;
use Symfony\Component\Finder\Finder;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    use TestingTools;

    protected $date;

    public function setUp()
    {
        parent::setUp();

        $this->setUpDate();
        $this->setUpDatabase();
        $this->setUpSendmail();
        $this->setUpStorage();
    }

    private function setUpDate()
    {
        $this->date = date('Y-m-d');
    }

    protected function setUpDatabase()
    {
        config(['database.default' => 'testing']);
    }

    protected function setUpSendmail()
    {
        config(['mail.sendmail' => '/usr/sbin/sendmail -bs']);
    }

    private function setUpStorage()
    {
        $this->app->useStoragePath(__DIR__ . '/fixture/storage');
    }

    protected function resolveApplicationConsoleKernel($app)
    {
        $app->singleton(KernelContract::class, Kernel::class);

        app(KernelContract::class);
    }

    public function tearDown()
    {
        $this->cleanLogsDirectory();

        parent::tearDown();
    }

    private function cleanLogsDirectory()
    {
        $objects = (new Finder)->in(storage_path('logs'))->depth(0);
        foreach ($objects as $object) {
            if (File::isDirectory($object)) {
                File::deleteDirectory($object);
            } else {
                File::delete($object);
            }
        }
    }
}
