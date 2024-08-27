<div class="d-flex mb-3">
    <form method="POST" action="{{ $searchActionUrl }}" style="width: 300px;">
        <h5>{{ $header }}</h5>
        @csrf
        <input hidden name="loadWith" id="loadWith" value="{{ $loadWith }}" />
        <input hidden name="redirect" id="redirect" value="{{ $redirect }}" />
        @for ($i = 0; $i < count($hiddenInputs); $i++)
<<<<<<< HEAD
            @if ($hiddenInputs[$i]['inputId'] == null)
                <input hidden name="{{ $hiddenInputs[$i]['inputName'] }}" value={{ $hiddenInputs[$i]['inputValue'] }} />
            @else
                <input hidden name="{{ $hiddenInputs[$i]['inputName'] }}" id="{{ $hiddenInputs[$i]['inputId'] }}"
                    value={{ $hiddenInputs[$i]['inputValue'] }} />
            @endif
=======
            <input hidden name="{{ $hiddenInputs[$i]['inputName'] }}" id="{{ $hiddenInputs[$i]['inputId'] }}"
                value={{ $hiddenInputs[$i]['inputValue'] }} />
>>>>>>> admin_panel
        @endfor
        @for ($i = 0; $i < count($inputs); $i++)
            @if ($inputs[$i]['required'])
                <div class="mb-3">
                    <label for="{{ $inputs[$i]['inputName'] }}"
                        class="form-label">{{ $inputs[$i]['displayName'] }}</label>
                    <input type="text" class="form-control" name="{{ $inputs[$i]['inputName'] }}"
                        id="{{ $inputs[$i]['inputName'] }}" required />
                </div>
            @else
                <div class="mb-3">
                    <label for="{{ $inputs[$i]['inputName'] }}"
                        class="form-label">{{ $inputs[$i]['displayName'] }}</label>
                    <input type="text" class="form-control" name="{{ $inputs[$i]['inputName'] }}"
                        id="{{ $inputs[$i]['inputName'] }}" />
                </div>
            @endif
<<<<<<< HEAD
            <x-error :model="$inputs[$i]['field']" />
=======
>>>>>>> admin_panel
        @endfor
        @for ($i = 0; $i < count($checkboxInputs); $i++)
            @if ($checkboxInputs[$i]['required'])
                <div class="mb-3">
                    <label for="{{ $checkboxInputs[$i]['inputName'] }}"
                        class="form-check-label">{{ $checkboxInputs[$i]['displayName'] }}</label>
                    <input type="checkbox" class="form-check-input" name="{{ $checkboxInputs[$i]['inputName'] }}"
<<<<<<< HEAD
                        id="{{ $checkboxInputs[$i]['inputId'] }}" value="1" required />
                </div>
                <x-error :model="$checkboxInputs[$i]['field']" />
            @else
=======
                        id="{{ $checkboxInputs[$i]['inputName'] }}" value="1" required />
                </div>
            @else
            </div>
>>>>>>> admin_panel
                <div class="mb-3">
                    <label for="{{ $checkboxInputs[$i]['inputName'] }}"
                        class="form-check-label">{{ $checkboxInputs[$i]['displayName'] }}</label>
                    <input type="checkbox" class="form-check-input" name="{{ $checkboxInputs[$i]['inputName'] }}"
<<<<<<< HEAD
                        id="{{ $checkboxInputs[$i]['inputId'] }}" value="1" />
=======
                        id="{{ $checkboxInputs[$i]['inputName'] }}" value="1" />
>>>>>>> admin_panel
                </div>
            @endif
        @endfor
        {{ $slot }}
        <button type="submit" class="btn btn-primary" id="{{ $submit_id }}">
            Найти
        </button>
    </form>
</div>
