<style>

</style>
@foreach($business as $item)
    <div class="business_main">
        <div>
            <div>business</div>
            <div>{{$item->title}}</div>
        </div>
    </div>
@endforeach
