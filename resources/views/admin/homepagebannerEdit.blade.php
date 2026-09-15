@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Edit Homepage Banner</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home"><a href="#"><i class="icon-home"></i></a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="#">Banners</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="#">Edit Banner</a></li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form action="{{ route('homepagebanner.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="card-header"><div class="card-title">Edit Banner</div></div>

                        <div class="card-body">
                            <div class="row">

                                <!-- Banner Type (Readonly) -->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label>Banner Type</label>
                                        <div class="form-control bg-light">
                                            @if($banner->type == 1)
                                                Banner 1
                                            @elseif($banner->type == 2)
                                                Banner 2
                                            @elseif($banner->type == 3)
                                                Banner 3 (Custom URL)
                                            @endif
                                        </div>
                                        <input type="hidden" name="type" value="{{ $banner->type }}">
                                        <!-- <small class="text-muted">Banner type cannot be changed</small> -->
                                        @error('type')<small class="text-danger">{{ $message }}</small>@enderror
                                    </div>
                                </div>

                                <!-- Category (Only for type 1) -->
                                @if($banner->type == 1)
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label>Select Category</label><br>
                                        @foreach($categories as $category)
                                            <label class="form-check form-check-inline">
                                                <input type="radio" name="url" value="{{ $category->id }}"
                                                    {{ (int)$banner->url == (int)$category->id ? 'checked' : '' }}>
                                                {{ $category->title }}
                                            </label>
                                        @endforeach
                                        @error('url')<small class="text-danger">{{ $message }}</small>@enderror
                                    </div>
                                </div>
                                @endif

                                <!-- Subcategory (Only for type 2) -->
                                @if($banner->type == 2)
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label>Select Sub Category</label><br>
                                        @foreach($subcategories as $subcategory)
                                            <label class="form-check form-check-inline">
                                                <input type="radio" name="url" value="{{ $subcategory->id }}"
                                                    {{ (int)$banner->url == (int)$subcategory->id ? 'checked' : '' }}>
                                                {{ $subcategory->title }}
                                            </label>
                                        @endforeach
                                        @error('url')<small class="text-danger">{{ $message }}</small>@enderror
                                    </div>
                                </div>
                                @endif

                                <!-- Custom URL (Only for type 3) -->
                                @if($banner->type == 3)
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label>Custom URL</label>
                                        <input type="text" name="url" class="form-control" 
                                               value="{{ $banner->url }}" 
                                               placeholder="https://example.com">
                                        @error('url')<small class="text-danger">{{ $message }}</small>@enderror
                                    </div>
                                </div>
                                @endif

                                <!-- Image -->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label>Image</label>
                                        <input type="file" name="image" class="form-control">
                                        @error('image')<small class="text-danger">{{ $message }}</small>@enderror

                                        @if($banner->image)
                                            <img src="{{ asset('userassets/image/' . $banner->image) }}" height="50" class="mt-2"><br>
                                            <a href="{{ route('homepagebanner.remove-image', $banner->id) }}" 
                                               class="btn btn-sm btn-danger mt-2"
                                               onclick="return confirm('Are you sure you want to remove the image?')">
                                               Remove Image
                                            </a>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="card-action">
                            <button type="submit" class="btn btn-success">Update</button>
                            <a href="{{ route('showhomepagebanner') }}" class="btn btn-secondary">Cancel</a>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function () {

    const bannerType = document.getElementById('bannerType');
    const categoryWrapper = document.getElementById('categoryWrapper');
    const subcategoryWrapper = document.getElementById('subcategoryWrapper');
    const customUrlWrapper = document.getElementById('customUrlWrapper');

    function handleTypeChange() {
        const type = bannerType.value;

        // hide all
        categoryWrapper.style.display = 'none';
        subcategoryWrapper.style.display = 'none';
        customUrlWrapper.style.display = 'none';

        // Reset radio buttons
        document.querySelectorAll('input[name="url"]').forEach(radio => {
            radio.checked = false;
        });

        if (type == 1) {
            categoryWrapper.style.display = 'block';
            // Find and check the correct radio button if url exists
            const currentUrl = "{{ $banner->url }}";
            if (currentUrl) {
                const radioToCheck = document.querySelector(`input[name="url"][value="${currentUrl}"]`);
                if (radioToCheck && radioToCheck.closest('#categoryWrapper')) {
                    radioToCheck.checked = true;
                }
            }
        } 
        else if (type == 2) {
            subcategoryWrapper.style.display = 'block';
            // Find and check the correct radio button if url exists
            const currentUrl = "{{ $banner->url }}";
            if (currentUrl) {
                const radioToCheck = document.querySelector(`input[name="url"][value="${currentUrl}"]`);
                if (radioToCheck && radioToCheck.closest('#subcategoryWrapper')) {
                    radioToCheck.checked = true;
                }
            }
        }
        else if (type == 3) {
            customUrlWrapper.style.display = 'block';
        }
    }

    bannerType.addEventListener('change', handleTypeChange);

    // Edit page load ke time
    handleTypeChange();
});
</script>