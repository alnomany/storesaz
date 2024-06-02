@extends('storefront.layout.theme4')
@section('page-title')
    {{ __('Blog') }}
@endsection
@push('css-page')
    <style>
        .shoping_count:after {
            content: attr(value);
            font-size: 14px;
            background: #273444;
            border-radius: 50%;
            padding: 1px 5px 1px 4px;
            position: relative;
            left: -5px;
            top: -10px;
        }
    </style>
@endpush
@php
    if(!empty(session()->get('lang')))
    {
        $currantLang = session()->get('lang');
    }else{
        $currantLang = $store->lang;
    }
    $languages=\App\Models\Utility::languages();
    $imgpath=\App\Models\Utility::get_file('uploads/blog_cover_image/');

@endphp
@section('content')
<div class="wrapper">
    <section class="blog-section padding-bottom">
        <div class="container">
            <div class="row blog-row">
                @foreach($blogs as $blog)
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="blog-card">
                        <div class="blog-card-inner">
                            <div class="blog-img">
                                <a href="{{route('store.store_blog_view',[$store->slug,$blog->id])}}">
                                    <img src="{{$imgpath.$blog->blog_cover_image}}">
                                </a>
                            </div>
                            <div class="blog-content">
                                <h3>
                                    <a href="{{route('store.store_blog_view',[$store->slug,$blog->id])}}">{{$blog->title}}</a>
                                </h3>
                                <p>{{\App\Models\Utility::dateFormat($blog->created_at)}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            </div>
        </div>
    </section>    
</div>    
@endsection
@push('script')
    <script>
        $(document).ready(function () {
            var blog = {{sizeof($blogs)}};
            if (blog < 1) {
                window.location.href = "{{route('store.slug',$store->slug)}}";
            }
        });
    </script>
@endpush
