<table class="table table-bordered table-sm">
    <thead>
        <tr>
            @foreach($rows[0] ?? [] as $col)
                <th>{{ $col }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach(array_slice($rows, 1) as $row)
            <tr>
                @foreach($row as $col)
                    <td>{{ $col }}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
