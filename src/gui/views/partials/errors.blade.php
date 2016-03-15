@if (count($errors) > 0)
    <div class="alert alert-danger" role="alert">
        <strong>Whoops! Something went wrong!</strong>

            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach

    </div>
@endif