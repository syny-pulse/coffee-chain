@php
    $name = $name ?? '';
    $label = $label ?? '';
    $type = $type ?? 'text';
    $value = $value ?? '';
    $required = $required ?? false;
    $placeholder = $placeholder ?? '';
    $help = $help ?? '';
@endphp

<div class="form-group">
    <label for="{{ $name }}" class="form-label">
        {{ $label }}
        @if($required)
            <span class="text-danger">*</span>
        @endif
    </label>
    
    @if($type === 'textarea')
        <textarea 
            name="{{ $name }}" 
            id="{{ $name }}" 
            class="form-control @error($name) is-invalid @enderror"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ isset($rows) ? 'rows=' . $rows : '' }}
        >{{ old($name, $value) }}</textarea>
    @elseif($type === 'select')
        <select 
            name="{{ $name }}" 
            id="{{ $name }}" 
            class="form-control @error($name) is-invalid @enderror"
            {{ $required ? 'required' : '' }}
        >
            @if(isset($options))
                @foreach($options as $optionValue => $optionLabel)
                    <option value="{{ $optionValue }}" {{ (old($name, $value) == $optionValue) ? 'selected' : '' }}>
                        {{ $optionLabel }}
                    </option>
                @endforeach
            @endif
        </select>
    @else
        <input 
            type="{{ $type }}" 
            name="{{ $name }}" 
            id="{{ $name }}" 
            value="{{ old($name, $value) }}"
            class="form-control @error($name) is-invalid @enderror"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ isset($step) ? 'step=' . $step : '' }}
            {{ isset($min) ? 'min=' . $min : '' }}
        >
    @endif
    
    @if($help)
        <div class="form-text">{{ $help }}</div>
    @endif
    
    @error($name)
        <div class="form-error">{{ $message }}</div>
    @enderror
</div>
