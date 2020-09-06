@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card products-list">
                <div class="card-header">
                    {{ __('Products') }}
                    <a class="btn btn-primary btn-sm float-right" href="{{ route('products.create') }}">{{ __('Add New') }}</a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link {{ isset($showTrash) ? '' : 'active' }}" href="{{ route('products.index') }}">{{ __('All products') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ isset($showTrash) ? 'active' : '' }}" href="{{ route('products.trash') }}">{{ __('Trash') }}</a>
                        </li>
                    </ul>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-outline table-vcenter">
                            <tr>
                                <th></th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('EAN') }}</th>
                                <th>{{ __('Price') }}</th>
                                <th>{{ __('Quantity') }}</th>
                                <th>{{ __('Type') }}</th>
                                <th>{{ __('Weight') }}</th>
                                <th>{{ __('Color') }}</th>
                                <th>{{ __('Active') }}</th>
                                <th style="width: 150px">{{ __('Action') }}</th>
                            </tr>
                            @foreach ($products as $product)
                                <tr>
                                    <td>
                                        @if ($product->images->first())
                                            <img alt="{{ $product->name }}" height=70 src="{{ asset('storage/images/product_images/'.$product->images->first()->name) }}" />
                                        @endif
                                    </td>
                                    <td><a href='{{ route('products.show', $product->id) }}'>{{ $product->name }}</a></td>
                                    <td>{{ $product->ean }}</td>
                                    <td>{{ $product->price }}</td>
                                    <td>{{ $product->quantity }}</td>
                                    <td>{{ $product->type }}</td>
                                    <td>{{ $product->weight }}</td>
                                    <td>{{ $colors[$product->color] }}</td>
                                    <td>
                                        @if ($product->active)
                                            <span class="badge badge-success">{{ __('Active') }}</span>
                                        @else
                                            <span class="badge badge-danger">{{ __('Inactive') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-toolbar" role="toolbar">
                                            @if (isset($showTrash))
                                                <form action="{{ route('products.restore', $product->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')

                                                    <button type="submit" class="btn btn-success btn-sm">{{ __('Restore') }}</button>
                                                </form>
                                            @else
                                                <a class="btn btn-primary btn-sm" href="{{ route('products.edit', $product->id) }}">{{ __('Edit') }}</a>
                                                <form action="{{ route('products.destroy', $product->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="btn btn-danger btn-sm">{{ __('Delete') }}</button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
