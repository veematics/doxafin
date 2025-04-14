<table class="table table-bordered table-striped">
    @if(!empty($headers))
        <thead>
            <tr>
                @foreach($headers as $header)
                    <th>{{ trim($header) }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
                <tr>
                    @foreach($row as $cell)
                        <td>{{ trim($cell) }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    @else
        <tr>
            <td class="text-center">{{ __('No valid CSV data found') }}</td>
        </tr>
    @endif
</table>