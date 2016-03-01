<?php

namespace JeroenG\LaravelBuilder;

use Exception;
use Illuminate\Filesystem\Filesystem;

class Builder
{
    /**
     * The filesystem handler.
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * The newly built file.
     * @var object
     */
    protected $file;

    /**
     * The stub to build the file from.
     * @var string
     */
    public $stub;

    /**
     * The namespace of the new class.
     * @var string
     */
    public $namespace;

    /**
     * The name of the new class.
     * @var string
     */
    public $class;

    /**
     * The place to store the new file.
     * @var string
     */
    public $path;
	
	/**
     * Create a new instance.
     * @param Illuminate\Filesystem\Filesystem $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        $this->files = $files;
    }

    /**
     * Fires the process of building the new class.
     * @return void
     */
    public function run()
    {
        $this->getStub()
        ->replaceNamespace($this->getNamespace())
        ->replaceClass($this->getClass());

        return $this;
    }

    /**
     * Save the newly built class.
     * @return int
     */
    public function save()
    {
        // Remove the stub insert placement if it is still there.
        $this->replace('%stub.insert%', '');
        return $this->files->put($this->getPath($this->getClass()), $this->file);
    }

    /**
     * Reset all the input arguments to null.
     * @return void
     */
    public function reset()
    {
        $this->stub = null;
        $this->namespace = null;
        $this->class = null;
        $this->path = null;
    }

    /**
     * Replace anything else in the stub file.
     * @param  string|array $search  One or more things to find in the stub.
     * @param  string|array $replace The things to replace them with.
     * @return $this
     */
    public function replace($search, $replace)
    {
        $this->file = str_replace($search, $replace, $this->file);
        
        return $this;
    }

    /**
     * Insert a stub function into the class.
     * It is important that the stub has a `%stub.insert%` somewhere in the file. 
     * @param  string $function The function to insert in the file.
     * @return $this
     */
    public function insert($function)
    {
        $replace = $function . "\r\n" . '%stub.insert%';
        $this->replace('%stub.insert%', $replace);
        
        return $this;
    }

    /**
     * Build from JSON configuration instead of setting the class variables.
     * It also saves the file and resets the builder.
     * @param  json $json
     * @return int
     */
    public function fromJson($json)
    {
        $array = json_decode($json, true);
        return $this->fromArray($array);
    }

    /**
     * Build from array configuration instead of setting the class variables.
     * It also saves the file and resets the builder.
     * @param  array $array
     * @return void|Exception
     */
    public function fromArray($array)
    {
        // It is possible to build multiple classes in a row.
        if (is_array($array) && isset($array[0]) && is_array($array[0])) {
            foreach ($array as $blueprint) {
                $this->stub = $blueprint['stub'];
                $this->namespace = $blueprint['namespace'];
                $this->class = $blueprint['class'];
                $this->path = $blueprint['path'];
                $this->run()->save();
                return $this->reset();
            }
        }
        elseif (is_array($array)) {
            $this->stub = $array['stub'];
            $this->namespace = $array['namespace'];
            $this->class = $array['class'];
            $this->path = $array['path'];
            $this->run()->save();
            return $this->reset();
        }
        else
        {
            throw new Exception("FromArray() requires an array to be given.");
        }
    }

    /**
     * Edit a file.
     * @param  string $path    Path to the file.
     * @param  string $search  What to replace.
     * @param  string $replace Text to replace with.
     * @return $this|Exception
     */
    public function edit($path)
    {
        $this->path = $this->files->dirname($path);
        $this->class = $this->files->name($path);
        $this->setFile($path);

        return $this;
    }

    /**
     * Get the namespace for the new class.
     * @return string|Exception Throws an exception if there is none.
     */
    protected function getNamespace()
    {
        if( ! isset($this->namespace) or is_null($this->namespace)) {
            throw new Exception("No namespace provided");
        }
        return $this->namespace;
    }

    /**
     * Get the name of the class.
     * @return string|Exception Throws an exception if there is none.
     */
    protected function getClass()
    {
        if( ! isset($this->class) or is_null($this->class)) {
            throw new Exception("No class provided");
        }
        return $this->class;
    }

    /**
     * Get the path for the new file.
     * @return string|Exception Throws an exception if there is none.
     */
    protected function getPath($class)
    {
        if( ! isset($this->path) or is_null($this->path)) {
            throw new Exception("No path provided");
        }
        return base_path($this->path . '/' . $class . '.php');
    }

    /**
     * Get the stub to build the file from.
     * @return $this|Exception Throws an exception if there is none.
     */
    protected function getStub()
    {
        if( ! isset($this->stub) or is_null($this->stub)) {
            throw new Exception("No stub provided");
        }
        $this->setFile(base_path('stubs/'.$this->stub.'.stub'));

        return $this;
    }

    /**
     * Get, open and set the file to build.
     * @param string $path Path to the file or stub.
     * @return $this
     */
    protected function setFile($path)
    {
        $this->file = $this->files->get($path);

        return $this;
    }

    /**
     * Replace the namespace in the stub file with the actual one.
     * @param  string $namespace The actual namespace.
     * @return $this
     */
    protected function replaceNamespace($namespace)
    {
        $this->replace('%stub.namespace%', $namespace);

        return $this;
    }

    /**
     * Replace the class name in the stub file with the actual one.
     * @param  string $replace The actual class name.
     * @return $this
     */
    protected function replaceClass($replace)
    {
        $this->replace('%stub.class%', $replace);

        return $this;
    }
}