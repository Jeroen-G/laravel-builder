@extends('builder::layouts.master')

@section('content')
    <h1>Build a resource</h1>

    @include('builder::partials.status')
    @include('builder::partials.errors')

    <p>This will create the controller, model, migration, policy, form request and views.</p>
    <p>After building, do not forget to register the policy and routes.</p>

    <form action="/builder/resource" method="POST" class="form-horizontal" role="form">
        {{ csrf_field() }}

        <fieldset class="form-group">
            <label for="content">Entity name (singular, uppercase)</label>
            <input type="text" name="entitySingle" class="form-control" value="{{ old('entitySingle') }}">
        </fieldset>

        <fieldset class="form-group">
            <label for="content">Entity name (plural, uppercase)</label>
            <input type="text" name="entityPlural" class="form-control" value="{{ old('entityPlural') }}">
        </fieldset>

        <fieldset class="form-group">
            <label for="slug">Future route</label>
            <input type="text" name="route" class="form-control" value="{{ old('route') }}">
        </fieldset>

        <button type="submit" class="btn btn-lg btn-primary btn-block">
            <i class="fa fa-plus"></i> Save
        </button>
    </form>
@endsection