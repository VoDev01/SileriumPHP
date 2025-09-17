@if ($error->has())
    <div style="display: flex; flex-direction: column;">
        @foreach ($errors->all() as $error)
            <span class="text-danger"> {{ $error }} </span>
        @endforeach
    </div>
@endif
