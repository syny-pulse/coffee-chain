@extends('retailers.layouts.app')

@section('title', 'Create Product Recipe')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Create Product Recipe</h1>
        <p class="page-subtitle">Add a new product recipe</p>
    </div>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('retailer.product_recipes.store') }}" method="POST" id="productRecipeForm">
        @csrf
        <div class="form-group">
            <label for="product_name">Product Name<span style="color:red">*</span></label>
            <input type="text" name="product_name" id="product_name" class="form-control" value="{{ old('product_name') }}" required>
        </div>
        <div class="form-group">
            <label for="price">Price<span style="color:red">*</span></label>
            <input type="number" step="0.01" name="price" id="price" class="form-control" value="{{ old('price') }}" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
        </div>
        <hr>
        <h3>Components</h3>
        <div id="componentsContainer">
            <div class="component-item">
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label>Coffee Breed<span style="color:red">*</span></label>
                        <select name="components[0][coffee_breed]" class="form-control" required>
                            <option value="">Select Breed</option>
                            <option value="arabica">Arabica</option>
                            <option value="robusta">Robusta</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Roast Grade<span style="color:red">*</span></label>
                        <select name="components[0][roast_grade]" class="form-control" required>
                            <option value="">Select Grade</option>
                            <option value="Grade 1">Grade 1</option>
                            <option value="Grade 2">Grade 2</option>
                            <option value="Grade 3">Grade 3</option>
                            <option value="Grade 4">Grade 4</option>
                            <option value="Grade 5">Grade 5</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Percentage<span style="color:red">*</span></label>
                        <input type="number" name="components[0][percentage]" class="form-control component-percentage" min="0" max="100" step="0.01" required>
                    </div>
                    <div class="form-group col-md-3 d-flex align-items-end">
                        <button type="button" class="btn btn-danger remove-component-btn">Remove</button>
                    </div>
                </div>
            </div>
        </div>
        <button type="button" id="addComponentBtn" class="btn btn-info mt-2">Add Component</button>
        <div class="form-group mt-3">
            <button type="submit" class="btn">Save Product Recipe</button>
        </div>
    </form>
@endsection
@section('scripts')
@parent
<script>
document.addEventListener('DOMContentLoaded', function () {
    let componentIndex = 1;
    document.getElementById('addComponentBtn').addEventListener('click', function () {
        const container = document.getElementById('componentsContainer');
        const newComponent = document.querySelector('.component-item').cloneNode(true);
        newComponent.querySelectorAll('select, input').forEach(input => {
            input.value = '';
            if (input.name) {
                input.name = input.name.replace(/components\[\d+\]/, 'components[' + componentIndex + ']');
            }
        });
        container.appendChild(newComponent);
        componentIndex++;
        attachRemoveListeners();
    });
    function attachRemoveListeners() {
        document.querySelectorAll('.remove-component-btn').forEach(button => {
            button.removeEventListener('click', removeComponent);
            button.addEventListener('click', removeComponent);
        });
    }
    function removeComponent(event) {
        const componentItems = document.querySelectorAll('.component-item');
        if (componentItems.length > 1) {
            event.target.closest('.component-item').remove();
        } else {
            alert('At least one component is required.');
        }
    }
    attachRemoveListeners();
    document.getElementById('productRecipeForm').addEventListener('submit', function (e) {
        const percentages = Array.from(document.querySelectorAll('.component-percentage')).map(input => parseFloat(input.value) || 0);
        const total = percentages.reduce((a, b) => a + b, 0);
        if (total !== 100) {
            e.preventDefault();
            alert('Total percentage of components must be exactly 100%. Current total: ' + total + '%.');
        }
    });
});
</script>
@endsection