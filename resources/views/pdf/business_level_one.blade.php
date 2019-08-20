<html>
<head>
    <style>
        /** Define the margins of your page **/
        @page {
            margin: 40px 20px;
        }

        @font-face {
            font-family: 'msyh';
            font-style: normal;
            font-weight: normal;
            {{--src: url({{ storage_path('fonts/msyh.ttf') }}) format('truetype');--}}
             src: url({{ storage_path('fonts/DroidSansFallback.ttf') }}) format('truetype');
        }

        body {
            font-family: msyh, DejaVu Sans, sans-serif;
            word-break: break-all;
            font-size: 14px;
        }

        header {
            position: fixed;
            top: -60px;
            left: 0px;
            right: 0px;
            height: 50px;

            /** Extra personal styles **/
            /*background-color: #03a9f4;*/
            color: white;
            text-align: center;
            line-height: 35px;
        }

        footer {
            position: fixed;
            bottom: -20px;
            left: 0px;
            right: 0px;
            height: 50px;

            /** Extra personal styles **/
            /*background-color: #03a9f4;*/
            color: white;
            text-align: center;
            line-height: 18px;
        }

        .description {
            word-break: break-all;
            word-wrap: break-word;
            width: 95%;
            /*background-color: #03a9f4;*/
        }
        .headerTitle span{
            font-size: 16px;
            padding-right: 10px;
        }
        main {
            width: 95%;
            margin: 0 auto;
        }
    </style>
</head>
<body>
<!-- Define header and footer blocks before your content -->
<header>
</header>

<footer>
    <script type="text/php">
              if (isset($pdf)) {
                $font = $fontMetrics->getFont("Arial");
                 $pdf->page_text(255, 800, "Page  {PAGE_NUM} / {PAGE_COUNT}", $font, 14, array(0, 0, 0));
               }

    </script>
</footer>

<!-- Wrap the content of your PDF inside a main tag -->
@php
    $size = sizeof($business) -1 ;
@endphp
<main>
    @foreach($business as $key => $busines)
        <div class="container" style="
        @if(isset($busines->listing) && $key < $size)
                page-break-after: always;
        @endif
                ">
            <!--编号-->
            <div class="headerTitle"><span>{{__('custom.no')}} #: </span>{{++$key }}</div>
            @if(isset($busines->listing) && $busines->listing)
                <div class="headerTitle"><span>{{__('custom.listing')}} #:</span> {{$busines->listing}}</div>
            @endif
            @if(isset($busines->company) && $busines->company)
                <div class="headerTitle"><span>{{__('custom.company_name')}}:</span> {{$busines->company}}</div>
            @endif
            @if(isset($busines->category) && $busines->category)
                <div class="headerTitle"><span>{{__('custom.company_category')}}:</span>
     {{\Illuminate\Support\Facades\App::getLocale() == 'en'?$busines->category->category_en:$busines->category->category_zh }}
                </div>
            @endif
        <!--标题-->
            @if(isset($busines->title) && $busines->title)
                <div class="headerTitle"><span>{{__('custom.title')}}:</span> {{$busines->title}}</div>
            @endif
        <!--标价-->
            @if(isset($busines->price) && $busines->price)
                <div class="headerTitle"><span>{{__('custom.price')}}:</span> $ {{$busines->price}}</div>
            @endif
        <!--地理位置-->
            @if(isset($busines->address) || isset($busines->country) || isset($busines->states))
                <div class="headerTitle">
                    <span>{{__('custom.location')}}:</span>{{isset($busines->country)?$busines->country:'' .' '. isset($busines->states)?$busines->states:'' .' '. isset($busines->states)?$busines->address:'' }}</div>
            @endif
            <!--是否盈利-->
            @if(isset($busines->profitability) && $busines->profitability)
                <div class="headerTitle"><span>{{__('custom.profitability')}}:</span> {{getBusinessStatus($busines->profitability)}}</div>
            @endif
        <!--是否包含房地产-->
            @if(isset($busines->real_estate) && $busines->real_estate)
                <div class="headerTitle"><span>{{__('custom.real_estate')}}:</span> {{getBusinessStatus($busines->real_estate)}}</div>
            @endif
        <!--是否连锁店-->
            @if(isset($busines->franchise) && $busines->franchise)
            <div class="headerTitle">{{__('custom.Franchise')}}: {{getBusinessStatus($busines->franchise)}}</div>
            @endif
        <!--营业面积-->
            @if(isset($busines->building_sf) && $busines->building_sf)
                <div class="headerTitle"><span>{{__('custom.building_sf')}}: </span>{{$busines->building_sf}}</div>
            @endif
        <!--员工人数-->
            @if(isset($busines->employee_count) && $busines->employee_count)
                <div class="headerTitle"><span>{{__('custom.employee_count')}}:</span> {{$busines->employee_count}}</div>
            @endif
        <!--毛利润-->
            @if(isset($busines->gross_income) && $busines->gross_income)
                <div class="headerTitle"><span>{{__('custom.gross_revenue')}}: </span>{{$busines->gross_income . '  /' . __('custom.'.getDateUnit($busines->gross_income_unit))}}</div>
            @endif
        <!--税息折旧及摊销前利润-->
            @if(isset($busines->ebitda) && $busines->ebitda)
                <div class="headerTitle"><span>{{__('custom.ebitda')}}: </span>{{$busines->ebitda}}</div>
            @endif
        <!--硬件资产价值-->
            @if(isset($busines->ff_e) && $busines->ff_e)
                <div class="headerTitle"><span>{{__('custom.ff_e')}}:</span> {{$busines->ff_e}}</div>
            @endif
        <!--库存-->
            @if(isset($busines->inventory) && $busines->inventory)
                <div class="headerTitle"><span>{{__('custom.inventory')}}: </span>{{$busines->inventory}}</div>
            @endif
        <!--净利润-->
            @if(isset($busines->net_income) && $busines->net_income)
                <div class="headerTitle"><span>{{__('custom.net_income')}}:</span> {{$busines->net_income . '  /' . __('custom.'.getDateUnit($busines->net_income_unit))}}</div>
            @endif
        <!--租约有效期-->
            @if(isset($busines->lease) && $busines->lease)
                <div class="headerTitle"><span>{{__('custom.lease_term')}}:</span> {{$busines->lease . ' /' . __('custom.'.getDateUnit($busines->lease_unit))}}</div>
            @endif
        <!--房地产估价-->
            @if(isset($busines->leasvalue_of_real_estatee) && $busines->leasvalue_of_real_estatee)
                <div class="headerTitle"><span>{{__('custom.value_of_real')}}: </span>{{$busines->value_of_real_estate}}</div>
            @endif
        <!--卖家融资-->
            @if(isset($busines->buyer_financing) && $busines->buyer_financing)
                <div class="headerTitle"><span>{{__('custom.buyer_financing')}}: </span>{{$busines->buyer_financing}}</div>
            @endif

            @if(isset($busines->business_description) && $busines->business_description)
                <div class="headerTitle"><span>{{__('custom.business_description')}}:</span></div>
                <p class="description">
                    {{$busines->business_description}}
                </p>
            @endif

            @if(isset($busines->employee_info) && $busines->employee_info)
                <div class="headerTitle"><span>{{__('custom.employee_information')}}:</span></div>
                <p class="description">
                    {{$busines->employee_info}}
                </p>
            @endif

            @if(isset($busines->financial_performance) && $busines->financial_performance)
                <div class="headerTitle"><span>{{__('custom.financial_performance')}}:</span></div>
                <p class="description"> {{$busines->financial_performance}}
                </p>
            @endif

            @if(isset($busines->franchise_reports) && $busines->franchise_reports)
                <div class="headerTitle"><span>{{__('custom.franchise_reports')}}:</span></div>
                <p class="description">
                    {{$busines->franchise_reports}}
                </p>
            @endif

            @if(isset($busines->tax_returns) && $busines->tax_returns)
                <div class="headerTitle"><span>{{__('custom.tax_returns')}}:</span></div>
                <p class="description">
                    {{$busines->tax_returns}}
                </p>
            @endif

            @if(isset($busines->business_assets) && $busines->business_assets)
                <div class="headerTitle"><span>{{__('custom.business_assets')}}:</span></div>
                <p class="description">
                    {{$busines->business_assets}}
                </p>
            @endif
            @if(isset($busines->reason_for_selling) && $busines->reason_for_selling)
                <div class="headerTitle"><span>{{__('custom.reason_for_selling')}}:</span></div>
                <p class="description">
                    {{$busines->reason_for_selling}}
                </p>
            @endif
        </div>
    @endforeach
</main>
</body>
</html>