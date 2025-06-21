{{-- @foreach($salesData as $sale)
<tr>
    <td>{{ $sale->week }}</td>
    <td>{{ $sale->product_name }}</td>
    <td>{{ $sale->total_qty }}</td>
</tr>
@endforeach --}}



        @foreach($salesData as $sale)
            <tr>
                <td>Week {{ substr($sale->week, 4) }}</td>
                <td>{{ $sale->product_name }}</td>
                <td>{{ $sale->total_qty }}</td>
            </tr>
        @endforeach

        
