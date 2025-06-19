@php
    $item = $item ?? null;
    $columns = $columns ?? [];
    $actions = $actions ?? [];
    
    // Helper function to get item value (works with both arrays and objects)
    $getValue = function($item, $field) {
        if (is_array($item)) {
            return $item[$field] ?? null;
        } else {
            return $item->{$field} ?? null;
        }
    };
@endphp

<tr>
    @foreach($columns as $column)
        <td>
            @php
                $value = $getValue($item, $column['field']);
            @endphp
            
            @if(isset($column['type']) && $column['type'] === 'status')
                <span class="status-badge {{ $value }}">
                    {{ ucfirst($value) }}
                </span>
            @elseif(isset($column['type']) && $column['type'] === 'currency')
                UGX{{ number_format($value, 2) }}
            @elseif(isset($column['type']) && $column['type'] === 'date')
                @if($value)
                    {{ \Carbon\Carbon::parse($value)->format('M d, Y') }}
                @else
                    N/A
                @endif
            @elseif(isset($column['type']) && $column['type'] === 'datetime')
                @if($value)
                    {{ \Carbon\Carbon::parse($value)->format('M d, Y H:i') }}
                @else
                    N/A
                @endif
            @elseif(isset($column['type']) && $column['type'] === 'number')
                {{ number_format($value) }}
            @elseif(isset($column['type']) && $column['type'] === 'boolean')
                <i class="fas fa-{{ $value ? 'check text-success' : 'times text-danger' }}"></i>
            @else
                {{ $value ?? 'N/A' }}
            @endif
        </td>
    @endforeach
    
    @if(count($actions) > 0)
        <td>
            <div class="table-actions">
                @foreach($actions as $action)
                    @if($action['type'] === 'link')
                        <a href="{{ $action['url'] }}" class="btn btn-{{ $action['style'] ?? 'outline' }} btn-sm" title="{{ $action['title'] ?? '' }}">
                            <i class="fas fa-{{ $action['icon'] }}"></i>
                        </a>
                    @elseif($action['type'] === 'delete')
                        <form method="POST" action="{{ $action['url'] }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this item?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    @endif
                @endforeach
            </div>
        </td>
    @endif
</tr>
