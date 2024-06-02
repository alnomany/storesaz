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

        article p {
            word-break: break-all;
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
    <section class="article-section padding-top padding-bottom">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9 col-12">
                    <div class="article-inner">
                        <div class="article-title">
                            <h5>{{$blogs->title}}</h5>
                            <span>{{\App\Models\Utility::dateFormat($blogs->created_at)}}</span>
                        </div>
                        @if(!empty($blogs->blog_cover_image) )
                            <img src="{{$imgpath.$blogs->blog_cover_image}}">
                        @else
                            <img src="{{asset(Storage::url('uploads/blog_cover_image/default.jpg'))}}">
                        @endif
                        <P>{!! $blogs->detail !!}
                        </P>
                    </div>
                </div>
            </div>
            @if(!empty($socialblogs) && $socialblogs->enable_social_button == 'on')
                <div id="share" class="text-center"></div>
            @endif
        </div>
    </section>
</div>
@endsection
@push('script')
    <script>
        $(document).ready(function () {
            var blog = {{$blogs}};
            if (blog == '') {
                window.location.href = "{{route('store.slug',$store->slug)}}";
            }
        });
    </script>
@endpush

