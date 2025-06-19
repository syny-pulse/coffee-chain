@props(['actions' => []])

<div class="card-actions">
    @foreach($actions as $action)
        @if($action['type'] === 'link')
            <a href="{{ $action['url'] }}" class="btn btn-{{ $action['style'] ?? 'primary' }} btn-{{ $action['size'] ?? 'sm' }}">
                @if(isset($action['icon']))
                    <i class="fas fa-{{ $action['icon'] }}"></i>
                @endif
                {{ $action['text'] }}
            </a>
        @elseif($action['type'] === 'button')
            <button type="button" class="btn btn-{{ $action['style'] ?? 'primary' }} btn-{{ $action['size'] ?? 'sm' }}" onclick="{{ $action['onclick'] ?? '' }}">
                @if(isset($action['icon']))
                    <i class="fas fa-{{ $action['icon'] }}"></i>
                @endif
                {{ $action['text'] }}
            </button>
        @elseif($action['type'] === 'submit')
            <button type="submit" class="btn btn-{{ $action['style'] ?? 'primary' }} btn-{{ $action['size'] ?? 'sm' }}">
                @if(isset($action['icon']))
                    <i class="fas fa-{{ $action['icon'] }}"></i>
                @endif
                {{ $action['text'] }}
            </button>
        @elseif($action['type'] === 'delete')
            <form method="POST" action="{{ $action['url'] }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this item?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-{{ $action['size'] ?? 'sm' }}">
                    <i class="fas fa-trash"></i>
                    {{ $action['text'] ?? 'Delete' }}
                </button>
            </form>
        @endif
    @endforeach
</div>
