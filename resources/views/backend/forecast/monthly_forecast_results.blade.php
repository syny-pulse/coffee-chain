@foreach($salesData as $sale)
<tr>
    <td>Month {{ $sale->month }}</td> <!-- Display the month in "YYYY-MM" format -->
    <td>{{ $sale->product_name }}</td>
    <td>{{ $sale->total_qty }}</td>
</tr>
@endforeach
