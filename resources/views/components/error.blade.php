@error($field)
    <span {{$attributes->class('text-danger')->merge(['style' => $style])}}>{{ $message }}</span>
@enderror