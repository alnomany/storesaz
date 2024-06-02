
@extends('storefront.layout.theme8')
@section('page-title')
    {{ __('Blog') }}
@endsection
@push('css-page')
@endpush
@php
if (!empty(session()->get('lang'))) {
    $currantLang = session()->get('lang');
} else {
    $currantLang = $store->lang;
}
$imgpath=\App\Models\Utility::get_file('uploads/blog_cover_image/');
$languages = \App\Models\Utility::languages();
@endphp
@section('content')
<div class="wrapper">
    <section class="article-section padding-bottom padding-top">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9 col-12">
                    <div class="article-inner">
                        <div class="article-title">
                            <h5>{{$blogs->title}}</h5>
                            <span>{{\App\Models\Utility::dateFormat($blogs->created_at)}}</span>
                        </div>
                        @if (!empty($blogs->blog_cover_image))
                            <img src="{{ $imgpath . $blogs->blog_cover_image }}">
                        @else   
                            <img src="{{ asset(Storage::url('uploads/store_logo/default.jpg')) }}">
                        @endif
                        <p>{!! $blogs->detail !!}
                        </p>
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
@endpush

