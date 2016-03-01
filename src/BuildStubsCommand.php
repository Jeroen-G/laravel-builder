<?php

namespace JeroenG\LaravelBuilder;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class BuildStubsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:stubs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish the default stubs.';

    /**
     * The filesystem handler.
     * @var object
     */
    protected $files;

    /**
     * Create a new instance.
     * @param Illuminate\Filesystem\Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->files->copyDirectory(__DIR__.'/stubs', base_path('stubs'));
        $this->info('Successfully moved the stubs to the root folder of your project.');
    }
}