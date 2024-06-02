@extends('layouts.admin')
@section('page-title')
    {{ __('Custom Page') }}
@endsection
@section('title')
    <div class="d-inline-block">
        <h5 class="h4 d-inline-block text-white font-weight-bold mb-0 ">{{ __('Custom Page') }}</h5>
    </div>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('Custom Page') }}</li>
@endsection

@push('css-page')
    <link rel="stylesheet" href="{{asset('custom/libs/summernote/summernote-bs4.css')}}">
@endpush
@push('script-page')
    <script src="{{asset('custom/libs/summernote/summernote-bs4.js')}}"></script>
@endpush

@section('action-btn')
@can('Create Custom Page')
    <a class="btn btn-sm btn-icon  btn-primary me-2 text-white" data-url="{{ route('custom-page.create') }}" data-title="{{ __('Create New Page') }}" data-ajax-popup="true" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Create') }}">
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
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Page Slug') }}</th>
                                    <th>{{ __('Header') }}</th>
                                    <th class="text-right">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pageoptions as $pageoption)
                                    <tr data-name="{{ $pageoption->name }}">
                                        <td>{{ $pageoption->name }}</td>
                                        @if ($store && $store->enable_domain == 'on')
                                            <td>
                                                {{ $store->domains . '/page/' . $pageoption->slug }}
                                            </td>
                                        @elseif($sub_store && $sub_store->enable_subdomain == 'on')
                                            <td>
                                                {{ $sub_store->subdomain . '/page/' . $pageoption->slug }}</td>
                                        @else
                                            <td>
                                                {{ env('APP_URL') . '/page/' . $pageoption->slug }}
                                            </td>
                                        @endif
                                        <td>
                                            {{ ucfirst($pageoption->enable_page_header == 'on' ? $pageoption->enable_page_header : 'Off') }}
                                        </td>
                                        <td class="Action">
                                            <div class="d-flex">
                                                @can('Edit Custom Page')
                                                    <a href="#!" class="btn btn-sm btn-icon  bg-light-secondary me-2" data-title="{{ __('Edit Page') }}" data-url="{{ route('custom-page.edit', $pageoption->id) }}" data-ajax-popup="true" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Edit') }}">
                                                        <i  class="ti ti-edit f-20"></i>
                                                    </a>
                                                @endcan
                                                @can('Delete Custom Page')
                                                    <a class="bs-pass-para btn btn-sm btn-icon bg-light-secondary" href="#"
                                                        data-title="{{ __('Delete Lead') }}"
                                                        data-confirm="{{ __('Are You Sure?') }}"
                                                        data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                        data-confirm-yes="delete-form-{{ $pageoption->id }}"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="{{ __('Delete') }}">
                                                        <i class="ti ti-trash f-20"></i>
                                                    </a>
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['custom-page.destroy', $pageoption->id], 'id' => 'delete-form-' . $pageoption->id]) !!}
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
    <script src="{{ asset('assets/js/plugins/tinymce/tinymce.min.js') }}"></script>
  
    <script>
        if ($(".pc-tinymce-2").length) {
            tinymce.init({
                selector: '.pc-tinymce-2',
                height: "400",
                content_style: 'body { font-family: "Inter", sans-serif; }',
                menubar:false,
                statusbar: false,
            });
        }
    </script>
   
@endpush
