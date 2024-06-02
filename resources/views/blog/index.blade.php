
@php($store_logo=\App\Models\Utility::get_file('uploads/blog_cover_image/'))
@extends('layouts.admin')
@section('page-title')
    {{ __('Blog') }}
@endsection
@section('title')
    <div class="d-inline-block">
        <h5 class="h4 d-inline-block text-white font-weight-bold mb-0 ">{{ __('Blog') }}</h5>
    </div>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('Blog') }}</li>
@endsection
@push('css-page')
    <link rel="stylesheet" href="{{ asset('custom/libs/summernote/summernote-bs4.css') }}">
@endpush
@push('script-page')
    <script src="{{ asset('custom/libs/summernote/summernote-bs4.js') }}"></script>
@endpush
@section('action-btn')
@can('Create Blog')
    <a class="btn btn-sm btn-icon  btn-primary me-2 text-white" data-url="{{ route('blog.create') }}" data-title="{{ __('Create New Blog') }}" data-ajax-popup="true" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Create') }}">
        <i  data-feather="plus"></i>
    </a>    
@endcan
   
@endsection
@section('filter')
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table mb-0 dataTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Blog Cover Image') }}</th>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Created At') }}</th>
                                    <th class="text-right">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($blogs as $blog)
                                {{-- @DD($store_logo. $blog->blog_cover_image ) --}}
                                    <tr data-name="{{ $blog->title }}">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if (!empty($blog->blog_cover_image))
                                                <a href="{{ $store_logo . $blog->blog_cover_image }}" target="_blank">
                                                    <img alt="Image placeholder"
                                                        src="{{ $store_logo . $blog->blog_cover_image }}"
                                                       class="rounded-circle" alt="images">
                                                </a>
                                                @else
                                                <a href="{{ $store_logo . '/avatar.png' }}" target="_blank">
                                                    <img alt="Image placeholder" src="{{ $store_logo . '/avatar.png' }}"
                                                       class="rounded-circle" alt="images">
                                                </a>
                                                @endif
                                            </div>
                                        </td>
                                        <td>{{ $blog->title }}</td>
                                        <td>
                                            {{ \App\Models\Utility::dateFormat($blog->created_at) }}</td>
                                        <td class="Action">
                                            <div class="d-flex">
                                                @can('Edit Blog')
                                                <a href="#!" class="btn btn-sm btn-icon  bg-light-secondary me-2" data-url="{{ route('blog.edit', $blog->id) }}" data-title="{{ __('Edit Blog') }}" data-ajax-popup="true" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Edit') }}">
                                                    <i  class="ti ti-edit f-20"></i>
                                                </a>
                                                @endcan
                                                @can('Delete Blog')
                                                    <a class="bs-pass-para btn btn-sm btn-icon bg-light-secondary" href="#"
                                                        data-title="{{ __('Delete Lead') }}"
                                                        data-confirm="{{ __('Are You Sure?') }}"
                                                        data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                        data-confirm-yes="delete-form-{{ $blog->id }}"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="{{ __('Delete') }}">
                                                        <i class="ti ti-trash f-20"></i>
                                                    </a>
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['blog.destroy', $blog->id], 'id' => 'delete-form-' . $blog->id]) !!}
                                                    {!! Form::close() !!}
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script-page')
    <script>
        $(document).ready(function() {
            $(document).on('keyup', '.search-user', function() {
                var value = $(this).val();
                $('.employee_tableese tbody>tr').each(function(index) {
                    var name = $(this).attr('data-name').toLowerCase();
                    if (name.includes(value.toLowerCase())) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        });
    </script>
@endpush
