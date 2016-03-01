<?php

use Illuminate\Filesystem\Filesystem;

class CommandTest extends TestCase
{
	/**
     * Runs before each test.
     */
	public function setUp()
	{
		parent::setUp();

		$this->fs = new Filesystem;

		if( ! $this->fs->isDirectory(base_path() . '/tests/results')) {
			$this->fs->makeDirectory(base_path() . '/tests/results');
		}
	}

	/**
     * Runs after each test.
     */
    public function tearDown()
    {
    	parent::tearDown();

        $this->fs->deleteDirectory(base_path() . '/tests/results');
    }

	public function test_that_stubs_are_published()
	{
		Artisan::call('build:stubs');
		$this->assertFileExists(base_path().'/stubs/teststub.stub');
	}

	public function test_building_from_console()
	{
		Artisan::call('build', [
        	'stub' => 'teststub',
        	'namespace' => 'App\\TestResults\\Builder',
        	'class' => 'BuilderTestResult',
        	'path' => 'tests/results',
    	]);

		$this->assertFileExists(base_path().'/tests/results/BuilderTestResult.php');
	}
}