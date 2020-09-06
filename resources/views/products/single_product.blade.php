@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-body">
                    <div class="row justify-content-center">
                        <div class="col-md-12 col-lg-6" style="max-width: 350px;">
                            @if ($product->images->count() > 0)
                                <div class="row justify-content-center" style="margin-bottom: 20px;">
                                    <div class="col-12">
                                        <img style="width: 100%;" alt="{{ $product->name }}" src="{{ asset('storage/images/product_images/'.$product->images->first()->name) }}" />
                                    </div>
                                </div>

                                @if ($product->images->count() > 1)
                                    <div class="row">
                                        @for ($i = 1; $i < $product->images->count(); $i++)
                                            <div class="col-4 col-lg-3">
                                                <img style="width: 100%;" alt="{{ $product->name }}" src="{{ asset('storage/images/product_images/'.$product->images[$i]->name) }}" />
                                            </div>
                                        @endfor
                                    </div>
                                @endif
                            @endif
                        </div>
                        <div class="col-md-12 col-lg-6 product-info">
                            <h1>{{ $product->name }}
                                @if ($product->active)
                                    <span class="badge badge-success">{{ __('Active') }}</span>
                                @else
                                    <span class="badge badge-danger">{{ __('Inactive') }}</span>
                                @endif
                            </h1>
                            <h2 style="color: red">{{ $product->price }} EUR</h2>
                            <div class="table-responsive product-details">
                                <table class="table table-vcenter">
                                    <tbody>
                                        <tr>
                                            <td>{{ __('EAN') }}:</td>
                                            <td>{{ $product->ean }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Type') }}:</td>
                                            <td>{{ $product->type }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Weight') }}:</td>
                                            <td>{{ $product->weight }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Color') }}:</td>
                                            <td>{{ $colors[$product->color] }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Quantity') }}:</td>
                                            <td>{{ $product->quantity }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="tabs">
                                <div class="btn-list text-center Tab_header_tabs tab_pills">
                                    <button class="btn btn-primary btn-pill" data-tab="price-history">{{ __('Price History') }}</button>
                                    <button class="btn btn-secondary btn-pill" data-tab="quantity-history">{{ __('Quantity History') }}</button>
                                </div>
                                <div class="tab" id="price-history">
                                </div>
                                <div class="tab" id="quantity-history" style="display: none">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include ('products/graph_scripts')

@endsection
