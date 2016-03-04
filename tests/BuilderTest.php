<?php

use JeroenG\LaravelBuilder\Builder;
use Illuminate\Filesystem\Filesystem;

class BuilderTest extends \Orchestra\Testbench\TestCase
{
	/**
     * Runs before each test.
     */
	public function setUp()
	{
		parent::setUp();

		$this->fs = new Filesystem;
		$this->b = new Builder($this->fs);
        
		if( ! $this->fs->isDirectory(base_path() . '/tests/results')) {
			$this->fs->makeDirectory(base_path() . '/tests/results', 0755, true);
		}

        Artisan::call('build:stubs');
	}

	/**
     * Runs after each test.
     */
    public function tearDown()
    {
    	parent::tearDown();

        $this->fs->deleteDirectory(base_path() . '/tests/results');
    }

    /**
     * For Testbench.
     */
    protected function getPackageProviders($app)
    {
       return ['JeroenG\LaravelBuilder\LaravelBuilderServiceProvider'];
    }

    public function test_running_builder()
    {
        $this->b->stub = 'teststub';
        $this->b->namespace = 'App\\TestResults\\Builder';
        $this->b->class = 'BuilderTestResult';
        $this->b->path = 'tests/results';
        $this->b->run()->save();
        
        $this->assertFileExists(base_path().'/tests/results/BuilderTestResult.php');
    }

	public function test_running_from_array()
	{
		$blueprint = [
        	'stub' => 'teststub',
        	'namespace' => 'App\\TestResults\\Builder',
        	'class' => 'BuilderTestResult',
        	'path' => 'tests/results',
    	];

    	$this->b->fromArray($blueprint);
		$this->assertFileExists(base_path().'/tests/results/BuilderTestResult.php');
	}

	public function test_running_from_json()
	{
		$blueprint = [
        	'stub' => 'teststub',
        	'namespace' => 'App\\TestResults\\Builder',
        	'class' => 'BuilderTestResult',
        	'path' => 'tests/results',
    	];

    	$json = json_encode($blueprint);
    	$this->b->fromJson($json);
		$this->assertFileExists(base_path().'/tests/results/BuilderTestResult.php');
	}

	public function test_namespace_and_class_get_replaced()
	{
		$blueprint = [
        	'stub' => 'teststub',
        	'namespace' => 'App\\TestResults\\Builder',
        	'class' => 'BuilderTestResult',
        	'path' => 'tests/results',
    	];

    	$this->b->fromArray($blueprint);

    	$haystack = $this->fs->get(base_path().'/tests/results/BuilderTestResult.php');
    	$needle1 = 'App\\TestResults\\Builder';
    	$needle2 = 'BuilderTestResult';
		$test1 = strpos($haystack, $needle1);
		$test2 = strpos($haystack, $needle2);

		$this->assertNotFalse($test1, 'Namespace was not replaced');
		$this->assertNotFalse($test2, 'Class was not replaced');
	}

	public function test_inserting_functions()
	{
        $function = '
    public function shouldBePresent()
    {
        return true;
    }
    ';

    	$this->b->stub = 'teststub';
        $this->b->namespace = 'App\\TestResults\\Builder';
        $this->b->class = 'BuilderTestResult';
        $this->b->path = 'tests/results';
        $this->b->run()->insert($function)->save();

        $haystack = $this->fs->get(base_path().'/tests/results/BuilderTestResult.php');
        $test = strpos($haystack, $function);
        $this->assertNotFalse($test, 'Function was not inserted');
	}

	public function test_editing_file()
	{
		$blueprint = [
        	'stub' => 'teststub',
        	'namespace' => 'App\\TestResults\\Builder',
        	'class' => 'BuilderTestResult',
        	'path' => 'tests/results',
    	];

    	$this->b->fromArray($blueprint);

    	$path = 'tests/results/BuilderTestResult.php';
		$haystack = $this->fs->get(base_path($path));
    	$needle1 = 'App\\TestResults\\Builder';
    	$needle2 = 'BuilderTestResult';
		$test1 = strpos($haystack, $needle1);
		$test2 = strpos($haystack, $needle2);

    	$this->b->edit(base_path($path))->replace('BuilderTestResult', 'EditedBuilderTestResult')->save();
        $this->b->reset();

		$this->assertFileExists(base_path($path));
		$this->assertNotFalse($needle1, 'Namespace was not replaced');
		$this->assertNotFalse($needle2, 'Class was not replaced');
	}
}