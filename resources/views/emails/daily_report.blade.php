@component('mail::message')
# Daily Sales Report - {{ $date }}
@if (sizeof($itemsSold) > 0)
## Item stats:
@component('mail::table')
| Item       | Sold         | Income  | Stock |
|:----------:|:------------:|:-------:|:-------------:|
@foreach($itemsSold as $item)
| {{$item['title']}}   | {{$item['quantity']}}     | ${{$item['income']}}     | {{$item['stock']}}             |
@endforeach
@endcomponent

# Overall income
${{$income}}
@else
# No sales for today :(
@endif

@if (sizeof($outOfStock) > 0)
## These items are almost out of stock: 
@component('mail::table')
| Item       | Stock         |
|:-------------:|:-------------:|
@foreach($outOfStock as $item)
| {{$item->title}} | {{$item->stock}} |
@endforeach
@endcomponent
@endif
@endcomponent
