@props(['title', 'value', 'icon', 'trend' => null, 'trendType' => 'positive', 'description' => ''])

<div class="stat-card">
    <div class="stat-header">
        <div class="stat-icon">
            <i class="fas fa-{{ $icon }}"></i>
        </div>
        @if($trend)
            <div class="stat-trend {{ $trendType }}">
                <i class="fas fa-arrow-{{ $trendType === 'positive' ? 'up' : 'down' }}"></i>
                {{ $trend }}
            </div>
        @endif
    </div>
    <div class="stat-value">{{ $value }}</div>
    <div class="stat-label">{{ $title }}</div>
    @if($description)
        <div class="stat-description">{{ $description }}</div>
    @endif
</div>
