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
            <div class="headerTitle"><b>NO #: </b>{{++$key }}</div>
            @if(isset($busines->listing) && $busines->listing)
                <div class="headerTitle"><b>LISTING #:</b> {{$busines->listing}}</div>
            @endif
        <!--标题-->
            @if(isset($busines->title) && $busines->title)
                <div class="headerTitle"><b>TITLE:</b> {{$busines->title}}</div>
            @endif
        <!--标价-->
            @if(isset($busines->price) && $busines->price)
                <div class="headerTitle"><b>PRICE:</b> $ {{$busines->price}}</div>
            @endif
        <!--地理位置-->
            @if(isset($busines->address) || isset($busines->country) || isset($busines->states))
                <div class="headerTitle">
                    <b>LOCATION:</b>{{isset($busines->country)?$busines->country:'' .' '. isset($busines->states)?$busines->states:'' .' '. isset($busines->states)?$busines->address:'' }}</div>
            @endif
            <!--是否盈利-->
            @if(isset($busines->profitability) && $busines->profitability)
                <div class="headerTitle"><b>PROFITABILITY:</b> {{getBusinessStatus($busines->profitability)}}</div>
            @endif
        <!--是否包含房地产-->
            @if(isset($busines->real_estate) && $busines->real_estate)
                <div class="headerTitle"><b>REAL ESTATE:</b> {{getBusinessStatus($busines->real_estate)}}</div>
            @endif
        <!--是否连锁店-->
            @if(isset($busines->franchise) && $busines->franchise)
            <div class="headerTitle">Franchise: {{getBusinessStatus($busines->franchise)}}</div>
            @endif
        <!--营业面积-->
            @if(isset($busines->building_sf) && $busines->building_sf)
                <div class="headerTitle"><b>BUILDING SF: </b>{{$busines->building_sf}}</div>
            @endif
        <!--员工人数-->
            @if(isset($busines->employee_count) && $busines->employee_count)
                <div class="headerTitle"><b>EMPLOYEE COUNT:</b> {{$busines->employee_count}}</div>
            @endif
        <!--毛利润-->
            @if(isset($busines->gross_income) && $busines->gross_income)
                <div class="headerTitle"><b>GROSS INCOME: </b>{{$busines->gross_income . '  /' . __('custom.'.getDateUnit($busines->gross_income_unit))}}</div>
            @endif
        <!--税息折旧及摊销前利润-->
            @if(isset($busines->ebitda) && $busines->ebitda)
                <div class="headerTitle"><b>EBITDA(Earning Before Interest, Tax, Depreciation &
                        Amortization): </b>{{$busines->ebitda}}</div>
            @endif
        <!--硬件资产价值-->
            @if(isset($busines->ff_e) && $busines->ff_e)
                <div class="headerTitle"><b>FF&E(Furniture, Fixture, & Equipment):</b> {{$busines->ff_e}}</div>
            @endif
        <!--库存-->
            @if(isset($busines->inventory) && $busines->inventory)
                <div class="headerTitle"><b>INVENTORY: </b>{{$busines->inventory}}</div>
            @endif
        <!--净利润-->
            @if(isset($busines->net_income) && $busines->net_income)
                <div class="headerTitle"><b>NET INCOME:</b> {{$busines->net_income . '  /' . __('custom.'.getDateUnit($busines->net_income_unit))}}</div>
            @endif
        <!--租约有效期-->
            @if(isset($busines->lease) && $busines->lease)
                <div class="headerTitle"><b>LEASE TERM:</b> {{$busines->lease . ' /' . __('custom.'.getDateUnit($busines->lease_unit))}}</div>
            @endif
        <!--房地产估价-->
            @if(isset($busines->leasvalue_of_real_estatee) && $busines->leasvalue_of_real_estatee)
                <div class="headerTitle"><b>Est. Value of Real Estate: </b>{{$busines->value_of_real_estate}}</div>
            @endif
        <!--卖家融资-->
            @if(isset($busines->buyer_financing) && $busines->buyer_financing)
                <div class="headerTitle"><b>BUYER FINANCING: </b>{{$busines->buyer_financing}}</div>
            @endif

            @if(isset($busines->business_description) && $busines->business_description)
                <div class="headerTitle"><b>BUSINESS DESCRIPTION</b></div>
                <p class="description">
                    {{$busines->business_description}}
                </p>
            @endif

            @if(isset($busines->employee_info) && $busines->employee_info)
                <div class="headerTitle"><b>EMPLOYEE INFORMATION</b></div>
                <p class="description">
                    {{$busines->employee_info}}
                </p>
            @endif

            @if(isset($busines->financial_performance) && $busines->financial_performance)
                <div class="headerTitle"><b>FINANCIAL PERFORMANCE</b></div>
                <p class="description"> {{$busines->financial_performance}}
                </p>
            @endif

            @if(isset($busines->franchise_reports) && $busines->franchise_reports)
                <div class="headerTitle"><b>FRANCHISE REPORTS</b></div>
                <p class="description">
                    {{$busines->franchise_reports}}
                </p>
            @endif

            @if(isset($busines->tax_returns) && $busines->tax_returns)
                <div class="headerTitle"><b>TAX RETURNS</b></div>
                <p class="description">
                    {{$busines->tax_returns}}
                </p>
            @endif

            @if(isset($busines->business_assets) && $busines->business_assets)
                <div class="headerTitle"><b>BUSINESS ASSETS</b></div>
                <p class="description">
                    {{$busines->business_assets}}
                </p>
            @endif
            @if(isset($busines->reason_for_selling) && $busines->reason_for_selling)
                <div class="headerTitle"><b>REASON FOR SELLING</b></div>
                <p class="description">
                    {{$busines->reason_for_selling}}
                </p>
            @endif
        </div>
    @endforeach
</main>
</body>
</html>