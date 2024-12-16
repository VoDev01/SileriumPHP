<div class="d-flex mb-3">
    <form method="POST" action="{{ $queryInputs->searchActionUrl }}" style="width: 300px;">
        <h5>{{ $header }}</h5>
        @csrf
        <input hidden name="loadWith" id="loadWith" value="{{ $queryInputs->loadWith }}" />
        <input hidden name="redirect" id="redirect" value="{{ $queryInputs->redirect }}" />
        @if ($hiddenInputs != null)
            @foreach ($hiddenInputs as $hiddenInput)
                @if ($hiddenInput->inputId == null)
                    <input hidden name="{{ $hiddenInput->inputName }}" value={{ $hiddenInput->inputValue }} />
                @else
                    <input hidden name="{{ $hiddenInput->inputName }}" id="{{ $hiddenInput->inputId }}"
                        value={{ $hiddenInput->inputValue }} />
                @endif
            @endforeach
        @endif
        @foreach ($inputs as $input)
            @if ($input->required)
                <div class="mb-3">
                    <label for="{{ $input->inputName }}" class="form-label">{{ $input->displayName }}</label>
                    <input type="text" class="form-control" name="{{ $input->inputName }}"
                        id="{{ $input->inputName }}" required />
                </div>
            @else
                <div class="mb-3">
                    <label for="{{ $input->inputName }}" class="form-label">{{ $input->displayName }}</label>
                    <input type="text" class="form-control" name="{{ $input->inputName }}"
                        id="{{ $input->inputName }}" />
                </div>
            @endif
            <x-error field="{{ $input->errorField }}" />
        @endforeach
        @if ($checkboxInputs != null)
            @foreach ($checkboxInputs as $checkboxInput)
                @if ($checkboxInput->required)
                    <div class="mb-3">
                        <label for="{{ $checkboxInput->inputName }}"
                            class="form-check-label">{{ $checkboxInput->displayName }}</label>
                        <input type="checkbox" class="form-check-input" name="{{ $checkboxInput->inputName }}"
                            id="{{ $checkboxInput->inputId }}" value="1" required />
                    </div>
                    <x-error :field="$checkboxInput->errorField" />
                @else
                    <div class="mb-3">
                        <label for="{{ $checkboxInput->inputName }}"
                            class="form-check-label">{{ $checkboxInput->displayName }}</label>
                        <input type="checkbox" class="form-check-input" name="{{ $checkboxInput->inputName }}"
                            id="{{ $checkboxInput->inputId }}" value="1" />
                    </div>
                @endif
            @endforeach
        @endif
        {{ $slot }}
        <button type="submit" class="btn btn-primary" id="{{ $submitId }}">
            Найти
        </button>
    </form>
</div>
