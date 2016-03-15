@extends('builder::layouts.master')

@section('content')
    <h1>Build a single file</h1>

    @include('builder::partials.status')
    @include('builder::partials.errors')

    <form action="/builder/single" method="POST" class="form-horizontal" role="form">
        {{ csrf_field() }}

        <fieldset class="form-group">
            <label for="stub">Stub</label>
            <select name="stub" class="form-control">
                @foreach($stubs as $option)
                    <option value="{{ $option }}">{{ $option }}</option>
                @endforeach
            </select>
        </fieldset>

        <fieldset class="form-group">
            <label for="slug">Namespace</label>
            <input type="text" name="namespace" class="form-control" value="{{ old('namespace') ? old('namespace') : 'App' }}">
        </fieldset>

        <fieldset class="form-group">
            <label for="content">Class name</label>
            <input type="text" name="class" class="form-control" value="{{ old('class') }}">
        </fieldset>

        <fieldset class="form-group">
            <label for="content">Path</label>
            <input type="text" name="path" class="form-control" value="{{ old('path') ? old('path') : 'app' }}">
        </fieldset>

        <button type="submit" class="btn btn-lg btn-primary btn-block">
            <i class="fa fa-plus"></i> Save
        </button>
    </form>
@endsection