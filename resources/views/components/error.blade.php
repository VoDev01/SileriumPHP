@error($field)
    <span {{ $attributes->merge(['class' => "text-danger error"]) }}>{{ $message }}</span>
@enderror