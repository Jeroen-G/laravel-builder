<?php

namespace JeroenG\LaravelBuilder;

use JeroenG\LaravelBuilder\Builder;
use Illuminate\Console\Command;

class BuildCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build
                            {stub : The file.stub placed in stubs/}
                            {namespace : The namespace of the new class}
                            {class : The name of the class}
                            {path : Location to store the new class}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build a new class from a stub.';

    /**
     * Builder class.
     * @var object
     */
    protected $b;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Builder $builder)
    {
        parent::__construct();
        $this->b = $builder;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $blueprint = [
            'stub' => $this->argument('stub'),
            'namespace' => $this->argument('namespace'),
            'class' => $this->argument('class'),
            'path' => $this->argument('path'),
        ];

        $result = $this->b->fromArray($blueprint);
        if ($result = true) {
            $this->info('Class ' .  $this->argument('class') . ' is created successfully.');
        } else {
            $this->info('Failed to create ' .  $this->argument('class') . ' in ' . addslashes($this->argument('path')));
        }
    }
}