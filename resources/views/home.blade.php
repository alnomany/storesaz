@php

$logo=\App\Models\Utility::get_file('uploads/logo/');
$profile=\App\Models\Utility::get_file('uploads/profile/');
$logo1=\App\Models\Utility::get_file('uploads/is_cover_image/');
$setting = App\Models\Utility::settings();
$company_logo = \App\Models\Utility::getValByName('company_logo');
@endphp

@extends('layouts.admin')
@section('page-title')
    {{ __('Dashboard') }}
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{__('Home')}}</a></li>
@endsection
@push('script-page')
    <script>
        var timezone = '{{ !empty($setting['timezone']) ? $setting['timezone'] : 'Asia/Kolkata' }}';

        let today = new Date(new Date().toLocaleString("en-US", {
            timeZone: timezone
        }));
        var curHr = today.getHours()
        var target = document.getElementById("greetings");

        if (curHr < 12) {
            target.innerHTML = "Good Morning,";
        } else if (curHr < 17) {
            target.innerHTML = "Good Afternoon,";
        } else {
            target.innerHTML = "Good Evening,";
        }

    </script>
    <script>
        $(document).on('click', '#code-generate', function() {
            var length = 10;
            var result = '';
            var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            var charactersLength = characters.length;
            for (var i = 0; i < length; i++) {
                result += characters.charAt(Math.floor(Math.random() * charactersLength));
            }
            $('#auto-code').val(result);
        });
    </script>
@endpush
@section('content')
@if (\Auth::user()->type == 'super admin')
<div class="row">
    <!-- [ sample-page ] start -->
    <div class="col-sm-12">
        <div class="row">
            <div class="col-xxl-6">
                <div class="row">
                    <div class="col-lg-4 col-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="theme-avtar bg-primary">
                                    <i class="fas fa-cube"></i>
                                </div>
                                <h6 class="mb-3 mt-4 ">{{ __('Total Store') }}</h6>
                                <h3 class="mb-0">{{ $user->total_user }}</h3>
                                {{-- <h6 class="mb-3 mt-4 ">{{ __('Paid Store') }}</h6>
                                <h3 class="mb-0">{{ $user['total_paid_user'] }}</h3> --}}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="theme-avtar bg-warning">
                                    <i class="fas fa-cart-plus"></i>
                                </div>
                                <h6 class="mb-3 mt-4 ">{{ __('Total Orders') }}</h6>
                                <h3 class="mb-0">{{ $user->total_orders }}</h3>
                               
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="theme-avtar bg-danger">
                                    <i class="fas fa-shopping-bag"></i>
                                </div>
                                <h6 class="mb-3 mt-4 ">{{ __('Total Plans') }}</h6>
                                <h3 class="mb-0">{{ $user['total_plan'] }}</h3>
                                {{-- <h6 class="mb-3 mt-4 ">{{ __('Most Purchase Plan') }}</h6>
                                <h3 class="mb-0">
                                    {{ !empty($user['most_purchese_plan']) ? $user['most_purchese_plan'] : '-' }}</h3> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-6">
                <div class="card">
                    <div class="card-header">
                        <h5>{{ __('Recent Orders') }}</h5>
                    </div>
                    <div class="card-body">
                        <div id="plan_order" data-color="primary" data-height="230"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ sample-page ] end -->
</div>
@else
<!-- [ Main Content ] start -->
<div class="row">
    <!-- [ sample-page ] start -->
    <div class="col-sm-12">
        <div class="row mb-5 gy-4">
            <div class="col-lg-4">
                <div class="welcome-card border bg-light-primary p-3 border-primary rounded text-dark h-100">
                    <div class="d-flex align-items-center mb-4">
                        <div class="me-2">
                            <img src="{{ !empty($users->avatar) ? $profile . '/' . $users->avatar : $profile . '/avatar.png' }}" alt="" class="theme-avtar">
                        </div>
                        <div>
                            <h5 class="mb-0">
                                <span class="d-block text-primary" id="greetings"></span>
                                <b class="f-w-700 text-primary">{{ __(Auth::user()->name) }}</b>
                            </h5>
                        </div>
                    </div>
                    <p class="mb-0 text-primary">{{ __('Have a nice day! Did you know that you can quickly add your favorite product or category to the store?') }}</p>
                    <div class="btn-group mt-4">
                        <button class="btn  btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i data-feather="plus" class="me-2"></i>
                            {{ __('Quick add') }}</button>
                        <div class="dropdown-menu">
                            @can('Create Products')
                                <a class="dropdown-item" href="{{ route('product.create') }}">{{ __('Add new product') }}</a>
                            @endcan
                            @can('Create Product Tax')
                                <a href="#" data-size="md" data-url="{{ route('product_tax.create') }}" data-ajax-popup="true" data-title="{{ __('Create New Product Tax') }}" class="dropdown-item" data-bs-placement="top ">
                                    <span>{{ __('Add new product tax') }}</span>
                                </a>
                            @endcan
                            @can('Create Product category')
                                <a href="#" data-size="md" data-url="{{ route('product_categorie.create') }}" data-ajax-popup="true" data-title="{{ __('Create New Product Category') }}" class="dropdown-item" data-bs-placement="top">
                                    <span>{{ __('Add new product category') }}</span>
                                </a>
                            @endcan
                            @can('Create Product Coupan')
                                <a href="#" data-size="md" data-url="{{ route('product-coupon.create') }}" data-ajax-popup="true" data-title="{{ __('Create New Product Coupon') }}" class="dropdown-item" data-bs-placement="top ">
                                    <span>{{ __('Add new product coupon') }}</span>
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="row gy-4">
                    <div class="col-xl-3 col-lg-6 col-sm-6">
                        <div class="card shadow-none mb-0">
                            <div class="card-body border rounded  p-3">
                                <div class="mb-4 d-flex align-items-center justify-content-between">
                                    <h6 class="mb-0">{{ $store_id->name }}</h6>
                                    <span>
                                        <i data-feather="arrow-up-right"></i>
                                    </span>
                                </div>
                                <div class="mb-4 text-center">{{--qrcode--}}
                                    {!! QrCode::generate($store_id['store_url']) !!}
                                </div>
                                <div class="d-flex justify-content-between pb-2">
                                    <a href="#!" class="btn btn-light-primary w-100 cp_link" data-link="{{  $store_id['store_url'] }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Click to copy Store link') }}" style=" padding-left: 10px; padding-right: 10px; ">
                                        {{ __('Store Link') }}
                                        <i class="ms-3"data-feather="copy"></i>
                                    </a>
                                    <a href="#" id="socialShareButton"
                                        class="socialShareButton btn btn-sm btn-primary ms-1 share-btn" style=" padding-top: 8px; padding-bottom: 8px; ">
                                        <i class="ti ti-share"></i>
                                    </a>
                                    <div id="sharingButtonsContainer" class="sharingButtonsContainer"
                                        style="display: none;">
                                        <div class="Demo1 d-flex align-items-center justify-content-center hidden"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-sm-6">
                        <div class="card shadow-none mb-0">
                            <div class="card-body border rounded  p-3">
                                <div class="mb-3 d-flex align-items-center justify-content-between">
                                    <h6 class="mb-0">{{ __('Total Products') }}</h6>
                                    <span>
                                        <i data-feather="arrow-up-right"></i>
                                    </span>
                                </div>
                                <div class="mb-3 d-flex align-items-center justify-content-between">
                                    <span class="f-30 f-w-600">{{ $newproduct }}</span>
                                </div>
                                <div class="chart-wrapper">
                                    <div id="TotalProducts" class="remove-min"></div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-sm-6">
                        <div class="card shadow-none mb-0">
                            <div class="card-body border rounded  p-3">
                                <div class="mb-3 d-flex align-items-center justify-content-between">
                                    <h6 class="mb-0">{{ __('Total Sales') }}</h6>
                                    <span>
                                        <i data-feather="arrow-up-right"></i>
                                    </span>
                                </div>
                                <div class="mb-3 d-flex align-items-center justify-content-between">
                                    <span class="f-30 f-w-600" style=" word-break: break-word; ">{{ \App\Models\Utility::priceFormat($totle_sale) }}</span>
                                </div>
                                <div class="chart-wrapper">
                                    <div id="TotalSales" class="remove-min"></div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-sm-6">
                        <div class="card shadow-none mb-0">
                            <div class="card-body border rounded  p-3">
                                <div class="mb-3 d-flex align-items-center justify-content-between">
                                    <h6 class="mb-0">{{ __('Total Orders') }}</h6>
                                    <span>
                                        <i data-feather="arrow-up-right"></i>
                                    </span>
                                </div>
                                <div class="mb-3 d-flex align-items-center justify-content-between">
                                    <span class="f-30 f-w-600">{{ $totle_order }}</span>
                                </div>
                                <div class="chart-wrapper">
                                    <div id="TotalOrders" class="remove-min"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <h4 >{{ __('Storage Status') }} <small>({{ $users->storage_limit . 'MB' }} / {{ $plan->storage_limit . 'MB' }})</small></h4>

                <div class="card shadow-none mb-0">
                    <div class="card-body border rounded  p-3">
                        <div id="device-chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <h4 >{{ __('Top Products') }}</h4>
                <div class="card mb-0 shadow-none">
                    <div class="card-body border border-bottom-0 overflow-hidden rounded pb-0 table-border-style">
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th class="bg-transparent">{{ __('Product') }}</th>
                                        <th class="bg-transparent"> {{ __('Quantity') }}</th>
                                        <th class="bg-transparent">{{ __('Price') }}</th>
                                        <th class="bg-transparent"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($item_id))
                                        @foreach ($products as $product)
                                            @foreach ($item_id as $k => $item)
                                                @if ($product->id == $item)
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="theme-avtar me-2">
                                                                    @if (!empty($product->is_cover))
                                                                        <img src="{{ $logo1 . $product->is_cover }}" alt="">
                                                                    @else
                                                                        <img src="{{ asset(Storage::url('uploads/is_cover_image/default.jpg')) }}" alt="">
                                                                    @endif                                                                
                                                                </div>
                                                                <a href="#" class=" text-dark f-w-600">{{ $product->name }}</a>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            @if($product->enable_product_variant == 'on')
                                                                {{ __("In Variant") }}
                                                            @else   
                                                                {{ $product->quantity }}
                                                            @endif  
                                                        </td>
                                                        <td>
                                                            <span class="f-w-700">
                                                                @if($product->enable_product_variant == 'on')
                                                                    {{ __("In Variant") }}
                                                                @else   
                                                                    {{ \App\Models\Utility::priceFormat($product->price) }}
                                                                @endif  
                                                            </span>
                                                        </td>
                                                        <td><span class="badge rounded p-2 f-10 bg-light-primary">{{ $totle_qty[$k] }}
                                                            {{ __('Sold') }}</span></td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @endforeach
                                    @else
                                        <tr height="150px">
                                            <td class="dataTables-empty" colspan="4"> 
                                                {{ __('No data available in table.') }}
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <h4>{{ __('Orders') }}</h4>
                <div class="card shadow-none mb-0">
                    <div class="card-body p-3 rounded border">
                        <div id="traffic-chart"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <h2 class="f-w-900 mb-3">{{ __('Recent Orders') }}</h2>
            </div>
            <div class="col-12">
                <div class="card mb-0 shadow-none">
                    <div class="card-body border border-bottom-0 overflow-hidden rounded pb-0 table-border-style">
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th class="bg-transparent">{{ __('Orders') }}</th>
                                        <th class="bg-transparent">{{ __('Date') }}</th>
                                        <th class="bg-transparent">{{ __('Name') }}</th>
                                        <th class="bg-transparent">{{ __('Value') }}</th>
                                        <th class="bg-transparent">{{ __('Payment Type') }}</th>
                                        <th class="bg-transparent">{{ __('Status') }}</th>
                                        <th class="bg-transparent">{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (!empty($new_orders))
                                        @foreach ($new_orders as $order)
                                            @if ($order->status != 'Cancel Order')
                                                <tr>
                                                    <td>
                                                    <a href="{{ route('orders.show', \Illuminate\Support\Facades\Crypt::encrypt($order->id)) }}" class="btn  btn-outline-primary"  data-link="{{ $store_id['store_url'] }}" data-bs-toggle="tooltip" data-toggle="tooltip" data-bs-original-title="{{__('Details')}}" title="{{__('Details')}}">{{ $order->order_id }}</a>
                                                    </td>
                                                    <td> {{ \App\Models\Utility::dateFormat($order->created_at) }}</td>
                                                    <td>{{ $order->name }}</td>
                                                    <td> {{ \App\Models\Utility::priceFormat($order->price) }}</td>
                                                    <td>{{ $order->payment_type }}</td>
                                                    <td>
                                                        @if ($order->payment_status == 'approved' && $order->status == 'pending')
                                                            <span class="badge me-2 rounded p-2  bg-light-secondary">{{ __('Pending') }}</span>
                                                            {{ \App\Models\Utility::dateFormat($order->created_at) }}
                                                        @else
                                                            <span class="badge me-2 rounded p-2  bg-light-primary">{{ __('Delivered') }}</span>
                                                            {{ \App\Models\Utility::dateFormat($order->updated_at) }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('orders.show', \Illuminate\Support\Facades\Crypt::encrypt($order->id)) }}" class="btn btn-sm btn-icon  bg-light-secondary me-2" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Details') }}"> <i  class="ti ti-eye f-20"></i></a>
                                                    </td>
                                                </tr>        
                                            @endif
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ sample-page ] end -->
</div>
<!-- [ Main Content ] end -->


@endif
@endsection
@push('script-page')
@if (\Auth::user()->type == 'super admin')
<script>
    (function() {
        var options = {
            chart: {
                height: 250,
                type: 'area',
                toolbar: {
                    show: false,
                },
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                width: 2,
                curve: 'smooth'
            },


            series: [{
                name: "{{ __('Order') }}",
                data: {!! json_encode($chartData['data']) !!}
            }],

            xaxis: {
                axisBorder: {
                    show: !1
                },
                type: "MMM",
                categories: {!! json_encode($chartData['label']) !!},
                title: {
                    text: '{{ __("Days") }}'
                }
            },
            colors: ['#e83e8c'],

            grid: {
                strokeDashArray: 4,
            },
            legend: {
                show: false,
            },
            // markers: {
            //     size: 4,
            //     colors: ['#FFA21D'],
            //     opacity: 0.9,
            //     strokeWidth: 2,
            //     hover: {
            //         size: 7,
            //     }
            // },
            yaxis: {
                tickAmount: 3,
                title: {
                text: '{{ __("Amount") }}'
            },
            }
        };
        var chart = new ApexCharts(document.querySelector("#plan_order"), options);
        chart.render();
    })();
   
</script>
@else
<script>
    $(document).ready(function() {
        $('.cp_link').on('click', function() {
            var value = $(this).attr('data-link');
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(value).select();
            document.execCommand("copy");
            $temp.remove();
            show_toastr('Success', '{{ __('Link copied') }}', 'success')
        });
    });
    (function () {
        var options = {
            chart: {
                height: 250,
                type: 'area',
                toolbar: {
                    show: false,
                },
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                width: 2,
                curve: 'smooth'
            },
            series: [{
                name: "{{ __('Order') }}",
                data: {!! json_encode($chartData['data']) !!}
            }],
            xaxis: {
                axisBorder: {
                    show: !1
                },
                type: "MMM",
                categories: {!! json_encode($chartData['label']) !!},
                title: {
                    text: '{{ __("Days") }}'
                }
            },
            colors: ['#ffa21d', '#FF3A6E'],

            grid: {
                strokeDashArray: 4,
            },
            legend: {
                show: false,
            },
            yaxis: {
                tickAmount: 3,
                title: {
                text: '{{ __("Amount") }}'
            },
            }
        };
        var chart = new ApexCharts(document.querySelector("#traffic-chart"), options);
        chart.render();
    })();
    (function () {
        var options = {
            chart: {
                height: 80,
                type: 'area',
                toolbar: {
                    show: false,
                },
            },
            dataLabels: {
                enabled: false,
                show:false,
            },
            stroke: {
                width: 2,
                curve: 'smooth',
            },
            series: [{
                name: "{{ __('Sales') }}",
                data: {!! json_encode($saleData['data']) !!}
            }],
            colors: ['#6FD943'],
            grid: {
                strokeDashArray: 4,
                show: false,
            },
            legend: {
                show: false,
            },
            markers: {
                enabled: false
            },
            yaxis: {
                show: false,
            },
            xaxis: {
                labels: {
                    show: false,
                },
                axisBorder: {
                    show: false,
                },
                axisTicks: {
                    show: false,
                },
                tooltip: {
                    enabled: false,
                }
            },
            tooltip: {
                enabled: false,
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'dark',
                    type: "horizontal",
                    shadeIntensity: 0,
                    gradientToColors: undefined, 
                    inverseColors: true,
                    opacityFrom: 0,
                    opacityTo: 0,
                    stops: [0, 50, 100],
                    colorStops: []
                }
            }
        };
        var chart = new ApexCharts(document.querySelector("#TotalSales"), options);
        chart.render();
    })();
    (function () {
        var options = {
            chart: {
                height: 80,
                type: 'area',
                toolbar: {
                    show: false,
                },
            },
            dataLabels: {
                enabled: false,
                show:false,
            },
            stroke: {
                width: 2,
                curve: 'smooth',
            },
            series: [{
                name: "{{ __('Order') }}",
                data: {!! json_encode($chartData['data']) !!}
            }],
            colors: ['#6FD943'],
            grid: {
                strokeDashArray: 4,
                show: false,
            },
            legend: {
                show: false,
            },
            markers: {
                enabled: false
            },
            yaxis: {
                show: false,
            },
            xaxis: {
                labels: {
                    show: false,
                },
                axisBorder: {
                    show: false,
                },
                axisTicks: {
                    show: false,
                },
                tooltip: {
                    enabled: false,
                }
            },
            tooltip: {
                enabled: false,
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'dark',
                    type: "horizontal",
                    shadeIntensity: 0,
                    gradientToColors: undefined, 
                    inverseColors: true,
                    opacityFrom: 0,
                    opacityTo: 0,
                    stops: [0, 50, 100],
                    colorStops: []
                }
            }
        };
        var chart = new ApexCharts(document.querySelector("#TotalOrders"), options);
        chart.render();
    })();
    (function () {
        var options = {
            chart: {
                height: 80,
                type: 'area',
                toolbar: {
                    show: false,
                },
            },
            dataLabels: {
                enabled: false,
                show:false,
            },
            stroke: {
                width: 2,
                curve: 'smooth',
            },
            series: [{
                name: "{{ __('Order') }}",
                data: []
            }],
            colors: ['#6FD943'],
            grid: {
                strokeDashArray: 4,
                show: false,
            },
            legend: {
                show: false,
            },
            markers: {
                enabled: false
            },
            yaxis: {
                show: false,
            },
            xaxis: {
                labels: {
                    show: false,
                },
                axisBorder: {
                    show: false,
                },
                axisTicks: {
                    show: false,
                },
                tooltip: {
                    enabled: false,
                }
            },
            tooltip: {
                enabled: false,
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'dark',
                    type: "horizontal",
                    shadeIntensity: 0,
                    gradientToColors: undefined, 
                    inverseColors: true,
                    opacityFrom: 0,
                    opacityTo: 0,
                    stops: [0, 50, 100],
                    colorStops: []
                }
            }
        };
        var chart = new ApexCharts(document.querySelector("#TotalProducts"), options);
        chart.render();
    })();

    (function () {
        var options = {
            series: [{{ round($storage_limit,2) }}],
            chart: {
                height: 350,
                type: 'radialBar',
                offsetY: -20,
                sparkline: {
                    enabled: true
                }
            },
            plotOptions: {
                radialBar: {
                    startAngle: -90,
                    endAngle: 90,
                    track: {
                        background: "#e7e7e7",
                        strokeWidth: '97%',
                        margin: 5, // margin is in pixels
                    },
                    dataLabels: {
                        name: {
                            show: true
                        },
                        value: {
                            offsetY: -50,
                            fontSize: '20px'
                        }
                    }
                }
            },
            grid: {
                padding: {
                    top: -10
                }
            },
            colors: ["#6FD943"],
            labels: ['Used'],
        };
        var chart = new ApexCharts(document.querySelector("#device-chart"), options);
        chart.render();
    })();


    //social sharing
    $(document).ready(function() {
        var customURL = {!! json_encode(url('/store/' . $store_id->slug)) !!};
        $('.Demo1').socialSharingPlugin({
            url: customURL,
            title: $('meta[property="og:title"]').attr('content'),
            description: $('meta[property="og:description"]').attr('content'),
            img: $('meta[property="og:image"]').attr('content'),
            enable: ['whatsapp', 'facebook', 'twitter', 'pinterest', 'linkedin']
        });

        $('.socialShareButton').click(function(e) {
            e.preventDefault();
            $('.sharingButtonsContainer').toggle();
        });
    });
</script>
@endif
@endpush

