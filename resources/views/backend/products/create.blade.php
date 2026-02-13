@extends('backend.master')

@section('title', 'Create Product')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Create Product</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('backend.admin.products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Enter title" required>
                                @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sku">Sku <span class="text-danger">*</span></label>
                                <input type="text" name="sku" id="sku" class="form-control @error('sku') is-invalid @enderror" value="{{ old('sku') }}" placeholder="Enter sku" required>
                                @error('sku')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="brand_id">Brand <span class="text-danger">*</span></label>
                                <select name="brand_id" id="brand_id" class="form-control select2 @error('brand_id') is-invalid @enderror" required>
                                    <option value="">Select Brand</option>
                                    @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                                @error('brand_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="category_id">Category <span class="text-danger">*</span></label>
                                <select name="category_id" id="category_id" class="form-control select2 @error('category_id') is-invalid @enderror" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="price">Price <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="price" id="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" placeholder="Enter price" required>
                                @error('price')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="unit_id">Unit <span class="text-danger">*</span></label>
                                <select name="unit_id" id="unit_id" class="form-control @error('unit_id') is-invalid @enderror" required>
                                    <option value="">Select Unit</option>
                                    @foreach($units as $unit)
                                    <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->name }} ({{ $unit->short_name }})</option>
                                    @endforeach
                                </select>
                                @error('unit_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="discount_type">Discount Type</label>
                                <select name="discount_type" id="discount_type" class="form-control">
                                    <option value="">Select Discount Type</option>
                                    <option value="fixed" {{ old('discount_type', 'fixed') == 'fixed' ? 'selected' : '' }}>
                                        Fixed
                                    </option>
                                    <option value="percentage" {{ old('discount_type', 'fixed') == 'percentage' ? 'selected' : '' }}>
                                        Percentage
                                    </option>
                                </select>
                            </div>


                            <div class="form-group">
                                <label for="discount">Discount Amount</label>
                                <input
                                    type="number"
                                    step="0.01"
                                    name="discount"
                                    id="discount"
                                    class="form-control @error('discount') is-invalid @enderror"
                                    value="{{ old('discount', 0) }}"
                                    placeholder="Enter discount">
                                @error('discount')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="purchase_price">Purchase Price <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="purchase_price" id="purchase_price" class="form-control @error('purchase_price') is-invalid @enderror" value="{{ old('purchase_price') }}" placeholder="Enter purchase Price" required>
                                @error('purchase_price')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="thumbnailInput">Image</label>
                                <div class="image-upload-container" id="imageUploadContainer">
                                    <input type="file" name="product_image" id="thumbnailInput" class="d-none" accept="image/*">
                                    <div class="upload-area" id="thumbPreviewContainer">
                                        <img src="" alt="Preview" class="d-none" id="thumbnailPreview">
                                        <div class="upload-content text-center">
                                            <i class="fas fa-plus-circle text-primary mb-2" style="font-size: 24px;"></i>
                                            <p class="mb-0">Upload Image</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea name="description" id="description" rows="3" class="form-control" placeholder="Enter description">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="expire_date">Expire date</label>
                                <div class="input-group">
                                    <input type="date" name="expire_date" id="expire_date" class="form-control" value="{{ old('expire_date') }}" placeholder="Enter product expire date">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="hidden" name="status" value="0">
                                    <input type="checkbox" name="status" class="custom-control-input" id="status" value="1" checked>
                                    <label class="custom-control-label" for="status">Active</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('style')
<style>
    .image-upload-container {
        width: 100%;
        height: 150px;
        border: 2px dashed #d2d6de;
        border-radius: 5px;
        position: relative;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa;
        overflow: hidden;
    }

    .image-upload-container:hover {
        border-color: #007bff;
    }

    .upload-area img {
        max-width: 100%;
        max-height: 140px;
        object-fit: contain;
    }

    .upload-content p {
        color: #6c757d;
        font-weight: 500;
    }

    .select2-container--default .select2-selection--single {
        height: 38px !important;
        padding: 6px 12px;
        border: 1px solid #ced4da !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px !important;
    }
</style>
@endpush

@push('script')
<script>
    $(document).ready(function() {
        // Trigger file input click
        $('#imageUploadContainer').on('click', function() {
            $('#thumbnailInput').click();
        });

        // Image preview
        $('#thumbnailInput').on('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#thumbnailPreview').attr('src', e.target.result).removeClass('d-none');
                    $('.upload-content').addClass('d-none');
                }
                reader.readAsDataURL(file);
            }
        });
    });
</script>
@endpush