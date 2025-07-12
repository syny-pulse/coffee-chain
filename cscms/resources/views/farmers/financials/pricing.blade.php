@extends('farmers.layouts.app')

@section('title', 'Pricing Management')
@section('page-title', 'Pricing Management')
@section('page-subtitle', 'Set and manage your coffee prices based on market trends and quality grades')

@section('page-actions')
    <button class="btn btn-primary" onclick="savePricing()">
        <i class="fas fa-save"></i> Save Changes
    </button>
    <button class="btn btn-outline" onclick="resetPricing()">
        <i class="fas fa-undo"></i> Reset
    </button>
    <button class="btn btn-outline" onclick="showAddPricingForm()">
        <i class="fas fa-plus"></i> Add New Pricing
    </button>
@endsection

@section('content')
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

    <!-- Add New Pricing Form (Hidden by Default) -->
    <div class="card" id="add-pricing-form" style="display: none;">
        <div class="card-header">
            <h2 class="card-title">Add New Pricing</h2>
            <div class="card-actions right-actions">
                <button class="btn btn-sm btn-outline" onclick="hideAddPricingForm()">
                    <i class="fas fa-times"></i> Cancel
                </button>
            </div>
        </div>
        <div class="form-container">
            <div class="form-group">
                <label for="new_coffee_variety">Coffee Variety</label>
                <select id="new_coffee_variety" class="form-control" onchange="updateNewDescription()">
                    <option value="">Select Variety</option>
                    <option value="Arabica">Arabica</option>
                    <option value="Robusta">Robusta</option>
                </select>
            </div>
            <div class="form-group">
                <label for="new_grade">Grade</label>
                <select id="new_grade" class="form-control" onchange="updateNewDescription()">
                    <option value="">Select Grade</option>
                    <option value="Grade 1">Grade 1</option>
                    <option value="Grade 2">Grade 2</option>
                    <option value="Grade 3">Grade 3</option>
                    <option value="Grade 4">Grade 4</option>
                    <option value="Grade 5">Grade 5</option>
                </select>
            </div>
            <div class="form-group">
                <label for="new_processing_method">Processing Method</label>
                <select id="new_processing_method" class="form-control" onchange="updateNewDescription()">
                    <option value="">Select Method</option>
                    <option value="Natural">Natural</option>
                    <option value="Washed">Washed</option>
                    <option value="Honey">Honey</option>
                </select>
            </div>
            <div class="form-group">
                <label for="new_unit_price">Unit Price (UGX/kg)</label>
                <div class="input-group">
                    <input type="number" id="new_unit_price" class="form-control" step="0.01" min="0"
                        value="" placeholder="UGX">
                </div>
            </div>
            <div class="form-group">
                <label>Description</label>
                <p id="new_description" class="form-text">Select options to generate description</p>
            </div>
            <button type="button" class="btn btn-primary" onclick="addPricingRow()">
                <i class="fas fa-plus"></i> Add Pricing
            </button>
        </div>
    </div>

    <!-- Pricing Form -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Coffee Pricing Configuration</h2>
            <div class="card-actions right-actions">
                <button class="btn btn-sm btn-outline" onclick="applyMarketPrices()">
                    <i class="fas fa-sync-alt"></i> Apply Market Prices
                </button>
            </div>
        </div>

        <form action="{{ route('farmers.financials.pricing.update') }}" method="POST" class="form-container"
            id="pricing-form">
            @csrf
            <div class="pricing-grid" id="pricing-grid">
                @foreach ($pricing as $index => $price)
                    <div class="pricing-card">
                        <div class="pricing-header">
                            <h3>{{ $price['coffee_variety'] }} - {{ $price['processing_method'] }}</h3>
                            <span class="grade-badge {{ strtolower($price['grade']) }}">{{ $price['grade'] }}</span>
                        </div>

                        <div class="pricing-description">
                            <p>{{ $price['description'] }}</p>
                        </div>

                        <div class="pricing-details">
                            <div class="detail-item">
                                <span class="detail-label">Current Market Price:</span>
                                <span class="detail-value">UGX
                                    {{ is_numeric($price['current_market_price']) ? number_format($price['current_market_price'], 2) : '0.00' }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Last Updated:</span>
                                <span
                                    class="detail-value">{{ \Carbon\Carbon::parse($price['last_updated'])->format('M d, Y') }}</span>
                            </div>
                        </div>

                        <div class="pricing-inputs">
                            <input type="hidden" name="prices[{{ $index }}][coffee_variety]"
                                value="{{ $price['coffee_variety'] }}">
                            <input type="hidden" name="prices[{{ $index }}][grade]"
                                value="{{ $price['grade'] }}">
                            <input type="hidden" name="prices[{{ $index }}][processing_method]"
                                value="{{ $price['processing_method'] }}">
                            <div class="form-group">
                                <label for="unit_price_{{ $index }}" class="form-label">Your Price
                                    (UGX/kg)
                                </label>
                                <div class="input-group">
                                    <span class="input-prefix">UGX</span>
                                    <input type="number" name="prices[{{ $index }}][unit_price]"
                                        id="unit_price_{{ $index }}" class="form-control" step="0.01"
                                        value="{{ $price['unit_price'] }}" min="0" required>
                                </div>
                                <div class="form-text">
                                    Market: UGX
                                    {{ is_numeric($price['current_market_price']) ? number_format($price['current_market_price'], 2) : '0.00' }}
                                    | Difference: UGX
                                    {{ is_numeric($price['unit_price']) && is_numeric($price['current_market_price']) ? number_format($price['unit_price'] - $price['current_market_price'], 2) : '0.00' }}
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
            document.querySelector('#pricing-form').submit();
        }

        function resetPricing() {
            if (confirm('Are you sure you want to reset all pricing to default values?')) {
                location.reload();
            }
        }

        function applyMarketPrices() {
            if (confirm('Apply current market prices to all coffee varieties?')) {
                alert('Market prices applied successfully!');
            }
        }

        function showAddPricingForm() {
            document.getElementById('add-pricing-form').style.display = 'block';
        }

        function hideAddPricingForm() {
            document.getElementById('add-pricing-form').style.display = 'none';
            document.getElementById('new_coffee_variety').value = '';
            document.getElementById('new_grade').value = '';
            document.getElementById('new_processing_method').value = '';
            document.getElementById('new_unit_price').value = '';
            updateNewDescription();
        }

        function updateNewDescription() {
            const variety = document.getElementById('new_coffee_variety').value;
            const grade = document.getElementById('new_grade').value;
            const method = document.getElementById('new_processing_method').value;
            const description = document.getElementById('new_description');
            if (variety && grade && method) {
                description.textContent = `Set price for ${variety} ${method} ${grade}`;
            } else {
                description.textContent = 'Select options to generate description';
            }
        }

        function addPricingRow() {
            const variety = document.getElementById('new_coffee_variety').value;
            const grade = document.getElementById('new_grade').value;
            const method = document.getElementById('new_processing_method').value;
            const unitPrice = document.getElementById('new_unit_price').value;

            if (!variety || !grade || !method) {
                alert('Please select variety, grade, and processing method.');
                return;
            }

            // Check for duplicates
            const existingRows = document.querySelectorAll('.pricing-card');
            for (let row of existingRows) {
                const rowVariety = row.querySelector('input[name*="[coffee_variety]"]').value;
                const rowGrade = row.querySelector('input[name*="[grade]"]').value;
                const rowMethod = row.querySelector('input[name*="[processing_method]"]').value;
                if (rowVariety === variety && rowGrade === grade && rowMethod === method) {
                    alert('This combination already exists.');
                    return;
                }
            }

            // Add new pricing card
            const grid = document.getElementById('pricing-grid');
            const index = existingRows.length;
            const newCard = document.createElement('div');
            newCard.className = 'pricing-card';
            newCard.innerHTML = `
        <div class="pricing-header">
            <h3>${variety} - ${method}</h3>
            <span class="grade-badge ${grade.toLowerCase().replace(' ', '-')}">${grade}</span>
            <button type="button" class="btn btn-sm btn-danger" onclick="removePricingRow(this)">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        <div class="pricing-description">
            <p>Set price for ${variety} ${method} ${grade}</p>
        </div>
        <div class="pricing-details">
            <div class="detail-item">
                <span class="detail-label">Current Market Price:</span>
                <span class="detail-value">UGX 0.00</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Last Updated:</span>
                <span class="detail-value">${new Date().toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' })}</span>
            </div>
        </div>
        <div class="pricing-inputs">
            <input type="hidden" name="prices[${index}][coffee_variety]" value="${variety}">
            <input type="hidden" name="prices[${index}][grade]" value="${grade}">
            <input type="hidden" name="prices[${index}][processing_method]" value="${method}">
            <div class="form-group">
                <label for="unit_price_${index}" class="form-label">Your Price (UGX/kg)</label>
                <div class="input-group">
                    <input type="number" name="prices[${index}][unit_price]" id="unit_price_${index}" class="form-control" step="0.01" value="${unitPrice}" min="0" placeholder="UGX">
                </div>
                <div class="form-text">Market: UGX 0.00 | Difference: UGX ${parseFloat(unitPrice || 0).toFixed(2)}</div>
            </div>
        </div>
    `;
            grid.appendChild(newCard);
            hideAddPricingForm();
        }

        function removePricingRow(button) {
            if (confirm('Are you sure you want to remove this pricing?')) {
                button.closest('.pricing-card').remove();
                // Re-index remaining inputs
                const cards = document.querySelectorAll('.pricing-card');
                cards.forEach((card, index) => {
                    card.querySelector('input[name*="[coffee_variety]"]').name = `prices[${index}][coffee_variety]`;
                    card.querySelector('input[name*="[grade]"]').name = `prices[${index}][grade]`;
                    card.querySelector('input[name*="[processing_method]"]').name =
                        `prices[${index}][processing_method]`;
                    card.querySelector('input[name*="[unit_price]"]').name = `prices[${index}][unit_price]`;
                    card.querySelector('input[name*="[unit_price]"]').id = `unit_price_${index}`;
                    card.querySelector('label[for*="unit_price_"]').setAttribute('for', `unit_price_${index}`);
                });
            }
        }

        // Auto-calculate price differences and handle prefixes
        document.addEventListener('DOMContentLoaded', function() {
    // Auto-calculate price differences
    const priceInputs = document.querySelectorAll('input[name*="[unit_price]"]');
    priceInputs.forEach(input => {
        input.addEventListener('input', function() {
            updatePriceDifference(input);
        });
    });

    function updatePriceDifference(input) {
        const card = input.closest('.pricing-card');
        if (!card) return;
        const marketPrice = parseFloat(card.querySelector('.detail-value').textContent.replace('UGX', '')) || 0;
        const newPrice = parseFloat(input.value) || 0;
        const difference = newPrice - marketPrice;

        const formText = input.parentElement.querySelector('.form-text');
        if (formText) {
            formText.innerHTML = `Market: UGX ${marketPrice.toFixed(2)} | Difference: UGX ${difference.toFixed(2)}`;
            if (difference > 0) {
                formText.style.color = 'var(--success)';
            } else if (difference < 0) {
                formText.style.color = 'var(--danger)';
            } else {
                formText.style.color = 'var(--text-muted)';
            }
        }
    }

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


    .input-group .form-control {
        padding-left: 1rem;
        width: 100%;
    }

    .form-control:focus::placeholder {
        opacity: 0.5;
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

    .right-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        width: 100%;
    }
</style>
