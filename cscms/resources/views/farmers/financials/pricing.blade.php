@extends('farmers.layouts.app')

@section('title', 'Pricing Management')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <h1><i class="fas fa-tags"></i> Pricing Management</h1>
        <p class="page-subtitle">Set and manage your coffee prices based on market trends and quality grades</p>
        <div class="page-actions">
            <button class="btn btn-primary" onclick="savePricing()">
                <i class="fas fa-save"></i> Save Changes
            </button>
            <button class="btn btn-outline" onclick="resetPricing()">
                <i class="fas fa-undo"></i> Reset
            </button>
        </div>
    </div>

    <!-- Market Trends Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-trend positive">
                    <i class="fas fa-arrow-up"></i>
                    {{ $marketTrends['arabica_trend'] }}
                </div>
            </div>
            <div class="stat-value">Arabica</div>
            <div class="stat-label">Market Trend</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-trend positive">
                    <i class="fas fa-arrow-up"></i>
                    {{ $marketTrends['robusta_trend'] }}
                </div>
            </div>
            <div class="stat-value">Robusta</div>
            <div class="stat-label">Market Trend</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div class="stat-trend positive">
                    <i class="fas fa-check"></i>
                    Stable
                </div>
            </div>
            <div class="stat-value">{{ $marketTrends['market_volatility'] }}</div>
            <div class="stat-label">Market Volatility</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="fas fa-lightbulb"></i>
                </div>
                <div class="stat-trend positive">
                    <i class="fas fa-arrow-up"></i>
                    +5%
                </div>
            </div>
            <div class="stat-value">Recommended</div>
            <div class="stat-label">Price Adjustment</div>
        </div>
    </div>

    <!-- Pricing Form -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Coffee Pricing Configuration</h2>
            <div class="card-actions">
                <button class="btn btn-sm btn-outline" onclick="applyMarketPrices()">
                    <i class="fas fa-sync-alt"></i> Apply Market Prices
                </button>
            </div>
        </div>
        
        <form action="{{ route('farmers.financials.pricing.update') }}" method="POST" class="form-container">
            @csrf
            <div class="pricing-grid">
                @foreach ($pricing as $index => $price)
                    <div class="pricing-card">
                        <div class="pricing-header">
                            <h3>{{ $price['coffee_variety'] }} - {{ $price['grade'] }}</h3>
                            <span class="grade-badge {{ strtolower($price['grade']) }}">
                                {{ $price['grade'] }}
                            </span>
                        </div>
                        
                        <div class="pricing-description">
                            <p>{{ $price['description'] }}</p>
                        </div>
                        
                        <div class="pricing-details">
                            <div class="detail-item">
                                <span class="detail-label">Current Market Price:</span>
                                <span class="detail-value">UGX {{ number_format($price['current_market_price'], 2) }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Last Updated:</span>
                                <span class="detail-value">{{ \Carbon\Carbon::parse($price['last_updated'])->format('M d, Y') }}</span>
                            </div>
                        </div>
                        
                        <div class="pricing-inputs">
                            <input type="hidden" name="prices[{{ $index }}][coffee_variety]" value="{{ $price['coffee_variety'] }}">
                            <input type="hidden" name="prices[{{ $index }}][grade]" value="{{ $price['grade'] }}">
                            
                            <div class="form-group">
                                <label for="unit_price_{{ $index }}" class="form-label">Your Price (UGX/kg)</label>
                                <div class="input-group">
                                    <span class="input-prefix">UGX</span>
                                    <input type="number" 
                                           name="prices[{{ $index }}][unit_price]" 
                                           id="unit_price_{{ $index }}" 
                                           class="form-control" 
                                           step="0.01" 
                                           value="{{ $price['unit_price'] }}" 
                                           min="0"
                                           required>
                                </div>
                                <div class="form-text">
                                    Market: UGX {{ number_format($price['current_market_price'], 2) }} | 
                                    Difference: UGX {{ number_format($price['unit_price'] - $price['current_market_price'], 2) }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="form-actions">
                <a href="{{ route('farmers.financials.index') }}" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i> Back to Financials
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Pricing
                </button>
            </div>
        </form>
    </div>

    <!-- Market Recommendation -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Market Intelligence</h2>
        </div>
        <div class="market-recommendation">
            <div class="recommendation-content">
                <i class="fas fa-lightbulb recommendation-icon"></i>
                <div class="recommendation-text">
                    <h4>Market Recommendation</h4>
                    <p>{{ $marketTrends['recommendation'] }}</p>
                    <div class="recommendation-details">
                        <span class="trend-indicator positive">
                            <i class="fas fa-arrow-up"></i> Arabica prices trending upward
                        </span>
                        <span class="trend-indicator positive">
                            <i class="fas fa-arrow-up"></i> Robusta demand stable
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
// Pricing management functions
function savePricing() {
    // Trigger form submission
    document.querySelector('form').submit();
}

function resetPricing() {
    if (confirm('Are you sure you want to reset all pricing to default values?')) {
        // Reset form to original values
        location.reload();
    }
}

function applyMarketPrices() {
    if (confirm('Apply current market prices to all coffee varieties?')) {
        // In a real app, this would fetch current market prices and apply them
        alert('Market prices applied successfully!');
    }
}

// Auto-calculate price differences
document.addEventListener('DOMContentLoaded', function() {
    const priceInputs = document.querySelectorAll('input[name*="[unit_price]"]');
    
    priceInputs.forEach(input => {
        input.addEventListener('input', function() {
            updatePriceDifference(this);
        });
    });
    
    function updatePriceDifference(input) {
        const card = input.closest('.pricing-card');
        const marketPrice = parseFloat(card.querySelector('.detail-value').textContent.replace('UGX', ''));
        const newPrice = parseFloat(input.value) || 0;
        const difference = newPrice - marketPrice;
        
        const formText = input.parentElement.querySelector('.form-text');
        formText.innerHTML = `Market: UGX${marketPrice.toFixed(2)} | Difference: UGX${difference.toFixed(2)}`;
        
        // Update visual indicator
        if (difference > 0) {
            formText.style.color = 'var(--success)';
        } else if (difference < 0) {
            formText.style.color = 'var(--danger)';
        } else {
            formText.style.color = 'var(--text-muted)';
        }
    }
    
    // Highlight pricing nav item
    const pricingLink = document.querySelector('a[href*="pricing"]');
    if (pricingLink) {
        pricingLink.classList.add('active');
    }
});
</script>
@endpush

<style>
/* Pricing Grid */
.pricing-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 2rem;
    margin-bottom: 2rem;
}

.pricing-card {
    background: var(--bg-secondary);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 1.5rem;
    transition: all 0.3s ease;
}

.pricing-card:hover {
    box-shadow: var(--shadow-lg);
    border-color: var(--coffee-medium);
}

.pricing-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--border-light);
}

.pricing-header h3 {
    margin: 0;
    color: var(--text-primary);
    font-size: 1.125rem;
    font-weight: 700;
}

.grade-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.grade-badge.premium {
    background: rgba(107, 142, 35, 0.1);
    color: var(--success);
}

.grade-badge.standard {
    background: rgba(112, 128, 144, 0.1);
    color: var(--info);
}

.pricing-description {
    margin-bottom: 1.5rem;
}

.pricing-description p {
    color: var(--text-secondary);
    font-size: 0.875rem;
    line-height: 1.5;
    margin: 0;
}

.pricing-details {
    margin-bottom: 1.5rem;
}

.pricing-inputs {
    border-top: 1px solid var(--border-light);
    padding-top: 1rem;
}

/* Input Group */
.input-group {
    position: relative;
    display: flex;
    align-items: center;
}

.input-prefix {
    position: absolute;
    left: 1rem;
    color: var(--text-muted);
    font-weight: 500;
    z-index: 1;
}

.input-group .form-control {
    padding-left: 2rem;
}

/* Market Recommendation */
.market-recommendation {
    padding: 1.5rem;
}

.recommendation-content {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
}

.recommendation-icon {
    font-size: 1.5rem;
    color: var(--warning);
    margin-top: 0.25rem;
}

.recommendation-text h4 {
    margin: 0 0 0.5rem 0;
    color: var(--text-primary);
    font-size: 1rem;
    font-weight: 600;
}

.recommendation-text p {
    margin: 0 0 1rem 0;
    color: var(--text-secondary);
    font-size: 0.875rem;
    line-height: 1.5;
}

.recommendation-details {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.trend-indicator {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.75rem;
    font-weight: 500;
}

.trend-indicator.positive {
    color: var(--success);
}

.trend-indicator.negative {
    color: var(--danger);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .pricing-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .pricing-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .recommendation-content {
        flex-direction: column;
        text-align: center;
    }
    
    .recommendation-details {
        align-items: center;
    }
}
</style>