<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<style>
    @font-face {
        font-family: 'msyh';
        font-style: normal;
        font-weight: normal;
        src: url({{ storage_path('fonts/msyh.ttf') }}) format('truetype');
    }

    body {
        width: 95%;
        /*max-width: 1100px;*/
        font-family: msyh, DejaVu Sans,sans-serif;
        margin: 0px auto;
        background-image: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAOwAAADsCAMAAABADbomAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyZpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNi1jMTQ1IDc5LjE2MzQ5OSwgMjAxOC8wOC8xMy0xNjo0MDoyMiAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIDIwMTkgKFdpbmRvd3MpIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOkQ1RDVGRjgzQURDNzExRTlBMDg3RTlCNDEwOTM1ODFDIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOkQ1RDVGRjg0QURDNzExRTlBMDg3RTlCNDEwOTM1ODFDIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6RDVENUZGODFBREM3MTFFOUEwODdFOUI0MTA5MzU4MUMiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6RDVENUZGODJBREM3MTFFOUEwODdFOUI0MTA5MzU4MUMiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz4RfFj2AAAABlBMVEXo6Oj/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");
    }

    .headerTitle {
        font-size: 16px;
        /*font-weight: bold;*/
        line-height: 25px;
    }

    .headerTitle b:after{
        content: "  ";
    }

    .container {
        page-break-after: always;
        height: 300px;
    }


    @media print {
        .noprint {
            display: none;
        }
    }
</style>
@foreach($business as $key => $busines)
    <div class="container">
        <!--编号-->
        <div class="headerTitle"><b>NO #: </b>{{++$key}}</div>
        @if($busines->listing)
            <div class="headerTitle"><b>LISTING #:</b> {{$busines->listing}}</div>
        @endif
    <!--标题-->

        @if($busines->title)
            <div class="headerTitle"><b>TITLE:</b> {{$busines->title}}</div>
        @endif
    <!--标价-->
        @if($busines->price)
            <div class="headerTitle"><b>PRICE:</b> $ {{$busines->price}}</div>
        @endif
    <!--地理位置-->
        <div class="headerTitle"><b>LOCATION:</b>{{$busines->country .' '. $busines->states .' '. $busines->address }}</div>
        <!--是否盈利-->
        @if($busines->profitability)
            <div class="headerTitle"><b>PROFITABILITY:</b> {{getBusinessStatus($busines->profitability)}}</div>
        @endif
    <!--是否包含房地产-->
        @if($busines->real_estate)
            <div class="headerTitle"><b>REAL ESTATE:</b> {{getBusinessStatus($busines->real_estate)}}</div>
        @endif
    <!--是否连锁店-->
        {{--@if($busines->type)--}}
        {{--<div class="headerTitle">Type: {{$busines->type}}</div>--}}
        {{--@endif--}}
    <!--营业面积-->
        @if($busines->building_sf)
            <div class="headerTitle"><b>BUILDING SF: </b>{{$busines->building_sf}}</div>
        @endif
    <!--员工人数-->
        @if($busines->employee_count)
            <div class="headerTitle"><b>EMPLOYEE COUNT:</b> {{$busines->employee_count}}</div>
        @endif
    <!--毛利润-->
        @if($busines->gross_income)
            <div class="headerTitle"><b>GROSS INCOME: </b>{{$busines->gross_income}}</div>
        @endif
    <!--税息折旧及摊销前利润-->
        @if($busines->ebitda)
            <div class="headerTitle"><b>EBITDA(Earning Before Interest, Tax, Depreciation &
                Amortization): </b>{{$busines->ebitda}}</div>
        @endif
    <!--硬件资产价值-->
        @if($busines->ff_e)
            <div class="headerTitle"><b>FF&E(Furniture, Fixture, & Equipment):</b> {{$busines->ff_e}}</div>
        @endif
    <!--库存-->
        @if($busines->inventory)
            <div class="headerTitle"><b>INVENTORY: </b>{{$busines->inventory}}</div>
        @endif
    <!--净利润-->
        @if($busines->net_income)
            <div class="headerTitle"><b>NET INCOME:</b> {{$busines->net_income}}</div>
        @endif
    <!--租约有效期-->
        @if($busines->lease)
            <div class="headerTitle"><b>LEASE TERM:</b> {{$busines->lease}}</div>
        @endif
    <!--房地产估价-->
        @if($busines->leasvalue_of_real_estatee)
            <div class="headerTitle"><b>Est. Value of Real Estate: </b>{{$busines->value_of_real_estate}}</div>
        @endif
    <!--卖家融资-->
        @if($busines->buyer_financing)
            <div class="headerTitle"><b>BUYER FINANCING: </b>{{$busines->buyer_financing}}</div>
        @endif

        @if($busines->business_description)
            <div class="headerTitle"><b>BUSINESS DESCRIPTION</b></div>
            <p>
                {{$busines->business_description}}
            </p>
        @endif
        @if($busines->financial_performance)
            <div class="headerTitle"><b>FINANCIAL PERFORMANCE</b></div>

            <p> {{$busines->financial_performance}}
            </p>
        @endif
        @if($busines->business_assets)
            <div class="headerTitle"><b>BUSINESS ASSETS</b></div>
            <p>
                {{$busines->business_assets}}
            </p>
        @endif
        @if($busines->reason_for_selling)
            <div class="headerTitle"><b>REASON FOR SELLING</b></div>
            <p>
                {{$busines->reason_for_selling}}
            </p>
        @endif
        @if($key == 1)
            <script type="text/php">
              if (isset($pdf)) {
                $font = $fontMetrics->getFont("Arial", "bold");
                 $pdf->page_text(255, 800, "Page {PAGE_NUM} / {PAGE_COUNT}", $font, 16, array(0, 0, 0));
               }
            </script>
            {{--@break--}}
        @endif
    </div>
@endforeach
