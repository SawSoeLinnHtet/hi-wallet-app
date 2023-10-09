@if ($errors->has('fail'))
    <div class="alert alert-danger alert-dismissible fade show mx-2 my-2" role="alert">
        {{ $errors->first('fail') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif