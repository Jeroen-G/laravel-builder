<?php

namespace JeroenG\LaravelBuilder\gui;

use Illuminate\Http\Request;

use Builder;
use JeroenG\LaravelBuilder\Builder as LBuilder;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class BuilderController extends Controller
{
    public function storeSingle(Request $request)
    {
    	$this->validate($request, [
    	    'namespace' => 'required',
    		'class' => 'required',
    		'path' => 'required',
    	]);

    	$blueprint = [
			'stub' => $request->stub,
        	'namespace' => $request->namespace,
        	'class' => $request->class,
        	'path' => $request->path,
		];
		Builder::fromArray($blueprint);
		return back()->with('status', $request->namespace.'\\'.$request->class.' has been created!');
    }

    public function storeResource(Request $request, LBuilder $b)
    {
        $this->validate($request, [
            'entitySingle' => 'required',
            'entityPlural' => 'required',
            'route' => 'required',
        ]);

        $ucSingle = $request->entitySingle;
        $ucPlural = $request->entityPlural;
        $lcSingle = strtolower($request->entitySingle);
        $lcPlural = strtolower($request->entityPlural);
        $route = $request->route;

        // Controller
        $b->stub = 'controller-resource';
        $b->namespace = 'App\\Http\\Controllers';
        $b->class = $ucSingle.'Controller';
        $b->path = 'app/Http/Controllers';
        $b->run()
            ->replace('%stub.ucSingle%', $ucSingle)
            ->replace('%stub.ucPlural%', $ucPlural)
            ->replace('%stub.lcSingle%', $lcSingle)
            ->replace('%stub.lcPlural%', $lcPlural)
            ->save();
        $b->reset();

        // Model
        $b->stub = 'model';
        $b->namespace = 'App';
        $b->class = $ucSingle;
        $b->path = 'app';
        $b->run()->save();
        $b->reset();

        // Migration
        $b->stub = 'migration';
        $b->namespace = 'App\\Migrations'; // Only present to not break the builder.
        $b->class = 'Create'.$ucPlural.'Table';
        $b->path = 'database/migrations';
        $b->filename = date('Y_m_d_His').'_create_'.$lcPlural.'_table.php';
        $b->run()
            ->replace('%stub.lcPlural%', $lcPlural)
            ->save();
        $b->reset();

        // Policy
        $b->stub = 'policy';
        $b->namespace = 'App\\Policies';
        $b->class = $ucSingle.'Policy';
        $b->path = 'app/Policies';
        $b->run()
            ->replace('%stub.ucSingle%', $ucSingle)
            ->replace('%stub.ucPlural%', $ucPlural)
            ->replace('%stub.lcSingle%', $lcSingle)
            ->replace('%stub.lcPlural%', $lcPlural)
            ->save();
        $b->reset();

        // Form Request
        $b->stub = 'formrequest';
        $b->namespace = 'App\\Http\\Requests';
        $b->class = $ucSingle.'Request';
        $b->path = 'app/Http/Requests';
        $b->run()->save();
        $b->reset();

        // Views
        $views = ['index', 'create', 'edit', 'show'];
        foreach ($views as $view) {
            $b->stub = 'view-'.$view;
            $b->namespace = 'App\\Views'; // Only present to not break the builder.
            $b->class = $ucSingle.'View'; // Only present to not break the builder.
            $b->filename = $view.'.blade.php';
            $b->path = 'resources/views/'.$lcPlural;
            $b->run()
                ->replace('%stub.ucSingle%', $ucSingle)
                ->replace('%stub.ucPlural%', $ucPlural)
                ->replace('%stub.lcSingle%', $lcSingle)
                ->replace('%stub.lcPlural%', $lcPlural)
                ->replace('%stub.route%', $route)
                ->save();
            $b->reset();
        }

        return back()->with('status', 'The resource has been created! Don\'t forget registering the route and policy.');
    }
}

