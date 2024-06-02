
@extends('storefront.layout.theme7')
@section('page-title')
    {{ ucfirst($pageoption->name) }}
@endsection
@push('css-page')

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
    <section class="contact-us-section padding-bottom padding-top">
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


