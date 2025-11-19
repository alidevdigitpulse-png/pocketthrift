<style>
td {
    font-size: 16px;
    font-weight: 600;
}
</style>
<!-- @dump(Auth::user()->bids) -->
<table class="table">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Vehicle</th>
            <th scope="col">Bid</th>
            <th scope="col">Time</th>
            <th scope="col">Date</th>
        </tr>
    </thead>
    <tbody>
        @php
            $count = 1;
        @endphp
        @foreach (Auth::user()->bids as $item)
            <tr>
                <th scope="row">{{ $count }}</th>
                <td>{{ $item->vehicle->name }}</td>
                <td><span class="badge badge-dark">${{ number_format($item->bid) }}</span></td>
                <td><span class="badge badge-primary">{{ Carbon::parse($item->created_at)->format('h:m A') }}</span></td>
                <td><span class="badge badge-success">{{ Carbon::parse($item->created_at)->format('d F Y') }}</span></td>
            </tr>
            @php
                $count++;
            @endphp
        @endforeach
    </tbody>
</table>
