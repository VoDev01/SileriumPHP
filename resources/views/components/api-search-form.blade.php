<div class="d-flex mb-3">
    <form method="POST" action="{{$actionUrl}}" style="width: 300px;">
        <h4>{{$header}}</h4>
        @csrf
        <input hidden name="load_with" id="load_with" value="{{$loadWith}}" />
        <input hidden name="redirect" id="redirect" value="{{$redirect}}" />
        {{ $slot }}
    </form>
</div>