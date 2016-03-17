@extends('builder::layouts.master')

@section('content')
    <h1>Laravel Builder</h1>
    <p>Choose an option in the menu to start building.</p>
    <p>
    	You should run <i>php artisan build:stubs</i> before being able to build files.
    </p>
    <p>
    	After that, you can edit the stubs to be the way you want them to be (warning: running the command again will overwrite the files).
    </p>
    <p>
    	If you build a resource, it assumes that there will be an <i>layouts/master.blade.php</i>, <i>partials/errors.blade.php</i> and <i>partials/status.blade.php</i> present in the views folder. You can of course change that in the stubs before building.
    </p>
@endsection