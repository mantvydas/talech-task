@extends('layouts.app')

@section('content')

@push('scripts')
    <script src="{{ asset('js/image.js')}}"></script>
@endpush

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Edit Product') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('products.update', ['product' => $product->id]) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="name" class="form-control @error('name') is-invalid @enderror" name="name"
                                       value="{{ old('name') ? old('name') : $product->name }}" required autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="ean" class="col-md-4 col-form-label text-md-right">{{ __('EAN') }}</label>

                            <div class="col-md-6">
                                <input id="ean" type="name" class="form-control @error('ean') is-invalid @enderror" name="ean"
                                       value="{{ old('ean') ? old('ean') : $product->ean }}" required>

                                @error('ean')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="type" class="col-md-4 col-form-label text-md-right">{{ __('Type') }}</label>

                            <div class="col-md-6">
                                <select id="type" class="form-control @error('type') is-invalid @enderror" name="type" required>
                                    <option {{ $product->type == "mens" ? "selected" : "" }} value="mens">Mens</option>
                                    <option {{ $product->type == "ladies" ? "selected" : "" }} value="ladies">Ladies</option>
                                </select>
                                @error('type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="weight" class="col-md-4 col-form-label text-md-right">{{ __('Weight') }}</label>

                            <div class="col-md-6">
                                <input id="weight" type="name" class="form-control @error('weight') is-invalid @enderror" name="weight"
                                       value="{{ old('weight') ? old('weight') : $product->weight }}" required>

                                @error('weight')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="color" class="col-md-4 col-form-label text-md-right">{{ __('Color') }}</label>

                            <div class="col-md-6">
                                <select id="color" class="form-control @error('color') is-invalid @enderror" name="color" required>
                                    @foreach ($colors as $colorCode => $colorName)
                                        <option {{ $product->color == $colorCode ? "selected" : "" }} value="{{ $colorCode }}">{{ $colorName }}</option>
                                    @endforeach
                                </select>
                                @error('color')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="price" class="col-md-4 col-form-label text-md-right">{{ __('Price') }}</label>

                            <div class="col-md-6">
                                <input id="price" type="name" class="form-control @error('price') is-invalid @enderror" name="price"
                                       value="{{ old('price') ? old('price') : $product->price }}" required>

                                @error('price')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="quantity" class="col-md-4 col-form-label text-md-right">{{ __('Quantity') }}</label>

                            <div class="col-md-6">
                                <input id="quantity" type="number" class="form-control @error('quantity') is-invalid @enderror" name="quantity"
                                       value="{{ old('quantity') ? old('quantity') : $product->quantity }}" required>

                                @error('quantity')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        @if ($product->images->count() > 0)
                            <div class="row justify-content-center">
                                @foreach ($product->images as $image)
                                    <div class="col-lg-3 col-md-4 col-6">
                                        <div class="d-block mt-2 mb-2 h-100 image-block">
                                            <img class="img-fluid img-thumbnail" src="{{ asset('storage/images/product_images/'.$image->name) }}" alt="">
                                            <button type="submit" class="btn btn-danger btn-sm delete-image">{{ __('Delete') }}</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <div class="user-image mb-3 text-center">
                            <div id="imgPreview"> </div>
                        </div>

                        <div class="form-group row">
                            <label for="images" class="col-md-4 col-form-label text-md-right">{{ __('Images') }}</label>

                            <div class="col-md-6">
                                <div class="custom-file">
                                    <input type="file" name="images[]" class="custom-file-input form-control @error('images.*') is-invalid @enderror" id="images" multiple>
                                    <label class="images-label" for="images">{{ __('Select files') }}</label>

                                    @error('images.*')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" value="1" type="checkbox" name="active" id="active" {{ $product->active ? 'checked' : '' }}>

                                    <label class="form-check-label" for="active">
                                        {{ __('Active') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Submit') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
