document.addEventListener('DOMContentLoaded', function() {
    // Initialize active navigation
    setActiveNavigation();
    
    // Form validation and enhancement
    initializeForms();
    
    // Table interactions
    initializeTables();
    
    // Card animations
    initializeAnimations();
    
    // Auto-calculate total amount in order forms
    initializeOrderCalculations();
    
    if (window.localStorage && localStorage.getItem('sidebarHidden') === '1') {
        document.body.classList.add('sidebar-hidden');
    }
});

// Set active navigation based on current route
function setActiveNavigation() {
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.nav-link');
    
    navLinks.forEach(link => {
        link.classList.remove('active');
        const href = link.getAttribute('href');
        
        if (href) {
            // Check if current path matches the route
            if (href.includes('farmers.dashboard') && currentPath === '/farmer/dashboard') {
                link.classList.add('active');
            }
            else if (href.includes('harvests') && currentPath.includes('harvests')) {
                link.classList.add('active');
            }
            else if (href.includes('inventory') && currentPath.includes('inventory')) {
                link.classList.add('active');
            }
            else if (href.includes('orders') && currentPath.includes('orders')) {
                link.classList.add('active');
            }
            else if (href.includes('financials') && currentPath.includes('financials')) {
                link.classList.add('active');
            }
            else if (href.includes('analytics') && currentPath.includes('analytics')) {
                link.classList.add('active');
            }
            else if (href.includes('communication') && currentPath.includes('communication')) {
                link.classList.add('active');
            }
        }
    });
}

// Initialize form validation and enhancement
function initializeForms() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        // Real-time validation
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            });
            
            input.addEventListener('input', function() {
                if (this.classList.contains('is-invalid')) {
                    validateField(this);
                }
            });
        });
        
        // Form submission
        form.addEventListener('submit', function(e) {
            if (!validateForm(this)) {
                e.preventDefault();
                showFormErrors(this);
            } else {
                showSuccessMessage(this);
            }
        });
    });
}

// Validate individual field
function validateField(field) {
    const value = field.value.trim();
    const isRequired = field.hasAttribute('required');
    
    // Remove existing validation classes
    field.classList.remove('is-invalid');
    
    // Check if required field is empty
    if (isRequired && value === '') {
        field.classList.add('is-invalid');
        return false;
    }
    
    // Email validation
    if (field.type === 'email' && value !== '') {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(value)) {
            field.classList.add('is-invalid');
            return false;
        }
    }
    
    // Number validation
    if (field.type === 'number' && value !== '') {
        const numValue = parseFloat(value);
        if (isNaN(numValue) || numValue < 0) {
            field.classList.add('is-invalid');
            return false;
        }
    }
    
    return true;
}

// Validate entire form
function validateForm(form) {
    const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!validateField(input)) {
            isValid = false;
        }
    });
    
    return isValid;
}

// Show form errors
function showFormErrors(form) {
    const invalidFields = form.querySelectorAll('.is-invalid');
    if (invalidFields.length > 0) {
        invalidFields[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
}

// Show success message
function showSuccessMessage(form) {
    const submitBtn = form.querySelector('button[type="submit"]');
    if (submitBtn) {
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-check"></i> Success!';
        submitBtn.classList.add('btn-success');
        
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.classList.remove('btn-success');
        }, 2000);
    }
}

// Initialize table interactions
function initializeTables() {
    const tableRows = document.querySelectorAll('.table tbody tr');
    
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.01)';
            this.style.transition = 'transform 0.2s ease';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
}

// Initialize animations
function initializeAnimations() {
    // Fade in animation for cards
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const fadeObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    document.querySelectorAll('.card, .stat-card, .form-container').forEach(element => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(30px)';
        element.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        fadeObserver.observe(element);
    });
}

// Initialize order calculations
function initializeOrderCalculations() {
    const quantityInput = document.querySelector('input[name="quantity_kg"]');
    const unitPriceInput = document.querySelector('input[name="unit_price"]');
    const totalAmountInput = document.querySelector('input[name="total_amount"]');
    
    if (quantityInput && unitPriceInput && totalAmountInput) {
        const calculateTotal = () => {
            const quantity = parseFloat(quantityInput.value) || 0;
            const unitPrice = parseFloat(unitPriceInput.value) || 0;
            const total = quantity * unitPrice;
            totalAmountInput.value = total.toFixed(2);
        };
        
        quantityInput.addEventListener('input', calculateTotal);
        unitPriceInput.addEventListener('input', calculateTotal);
    }
}

// Add click effects to action cards
document.addEventListener('click', function(e) {
    if (e.target.closest('.action-card')) {
        const card = e.target.closest('.action-card');
        card.style.transform = 'scale(0.98)';
        setTimeout(() => {
            card.style.transform = '';
        }, 150);
    }
});

// Add hover effects for activity items
document.addEventListener('DOMContentLoaded', function() {
    const activityItems = document.querySelectorAll('.activity-item');
    
    activityItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.transform = 'translateX(8px)';
        });
        
        item.addEventListener('mouseleave', function() {
            this.style.transform = 'translateX(0)';
        });
    });
});

// Simulate dynamic data updates for dashboard
function updateStats() {
    const statValues = document.querySelectorAll('.stat-value');
    
    setTimeout(() => {
        const harvestValue = statValues[0];
        if (harvestValue) {
            const currentValue = parseInt(harvestValue.textContent.replace(/[^\d]/g, ''));
            const newValue = currentValue + Math.floor(Math.random() * 10) - 5;
            harvestValue.textContent = newValue.toLocaleString();
        }
    }, 5000);
}

// Initialize stats updates if on dashboard
if (window.location.pathname === '/farmer/dashboard') {
    updateStats();
}

function toggleSidebar() {
    document.body.classList.toggle('sidebar-hidden');
    // Optionally persist state
    if (window.localStorage) {
        localStorage.setItem('sidebarHidden', document.body.classList.contains('sidebar-hidden') ? '1' : '');
    }
}

window.toggleSidebar = toggleSidebar;