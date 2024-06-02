@extends('storefront.layout.theme6')
@section('page-title')
    {{ ucfirst($pageoption->name) }}
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

        .pagedetails {
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
@endphp
@section('content')
<div class="wrapper">
    <section class="contact-us-section padding-top padding-bottom">
        <div class="container">
            <div class="contact-content">
                <h4>{{ ucfirst($pageoption->name) }}</h4>
                <p>{!! $pageoption->contents !!}
                </p>
            </div>
        </div>
    </section>
</div>
@endsection


