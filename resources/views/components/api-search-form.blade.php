<div class="d-flex mb-3">
    <form method="POST" action="{{$actionUrl}}" style="width: 300px;">
        <h4>{{$header}}</h4>
        @csrf
        <input hidden name="load_with" id="load_with" value="{{$loadWith}}" />
        <input hidden name="redirect" id="redirect" value="{{$redirect}}" />
        @for ($i = 0; $i < count($inputs); $i++)
            <div class="mb-3">
                <label for="{{$inputs[$i]["inputName"]}}" class="form-label">{{$inputs[$i]["displayName"]}}</label>
                <input type="text" class="form-control" name="{{$inputs[$i]["inputName"]}}" id="{{$inputs[$i]["inputName"]}}" required="{{$inputs[$i]["required"]}}" />
            </div>
        @endfor
        <button type="submit" class="btn btn-primary" id="{{$submit_id}}">
            Найти
        </button>
        {{ $slot }}
    </form>
</div>