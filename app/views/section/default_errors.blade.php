@if ($errors->has())
<div class="alert alert-danger" role="alert">
    @foreach ($errors->all() as $error)
        {{ $error }}<br>
    @endforeach
</div>
@elseif (Session::has('global_error'))
<div class="alert alert-danger" role="alert">
    {{ Session::get('global_error') }}
</div>
@elseif (Session::has('global_success'))
<div class="alert alert-success" role="alert">
    {{ Session::get('global_success') }}
</div>
@endif