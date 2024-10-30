<!DOCTYPE html>
<html>

<head>
    <title>Indicator Report</title>

    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }
    </style>
</head>

<body>
    <h1>Indicator: {{ $indicator->name }}</h1>
    <table>
        <tr>
            <th>Attribute</th>
            <th>Value</th>
        </tr>
        <tr>
            <td>Indicator Id</td>
            <td>{{ $indicator->id }}</td>
        </tr>
        <tr>
            <td>Category</td>
            <td>{{ $indicator->category }} Indicator</td>
        </tr>
        <tr>
            <td>Direction of Progress Measurement</td>
            <td>{{ $indicator->direction }} direction</td>
        </tr>

        <tr>
            <td>Indicator Title</td>
            <td>{{ $indicator->indicator_title }}</td>
        </tr>
        <tr>
            <td>Indicator Definition</td>
            <td>{{ $indicator->definition }}</td>
        </tr>
        <tr>
            <td>Indicator Status</td>
            <td>{{ $indicator->status }}</td>
        </tr>
        <tr>
            <td>Updated Manually</td>
            <td>{{ $indicator->is_manually_updated ? 'Yes' : 'No' }}</td>
        </tr>
        <tr>
            <td>Indicator Baseline</td>
            <td>{{ $indicator->baseline }}</td>
        </tr>
        <tr>
            <td>Indicator Target</td>
            <td>{{ $indicator->target }}</td>
        </tr>
        <tr>
            <td>Data Source</td>
            <td>{{ $indicator->data_source }}</td>
        </tr>
        <tr>
            <td>Measurement Frequency</td>
            <td>{{ $indicator->frequency }}</td>
        </tr>
        <tr>
            <td>Responsible</td>
            <td>{{ $indicator->responsible }}</td>
        </tr>
        <tr>
            <td>Reporting</td>
            <td>{{ $indicator->reporting }}</td>
        </tr>
    </table>

    <h2>Responses</h2>
    <table>
        <thead>
            <tr>
                <th>Response id</th>
                <th>Status</th>
                <th>Current State</th>
                <th>Percentage Progress From Baseline</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($indicator->responses as $response)
            <tr>
                <td>{{ $response->id }}</td>
                <td>{{ $response->status }}</td>
                <td>{{ $response->current }}</td>
                <td>{{ $response->progress }}</td>
                <td>{{ $response->created_at }}</td>
            </tr>
            @endforeach
        </tbody>

    </table>
    @foreach ($indicator->responses as $response)
    <!-- Additional Information for Each Response -->

    @if ($response->notes !== '')
    <h4>Notes</h4>
    <div>{!! $response->notes !!}</div>
    @endif

    @if ($response->lessons !== '')
    <h4>Lessons</h4>
    <div>{!! $response->lessons !!}</div>
    @endif

    @if ($response->recommendations !== '')
    <h4>Recommendations</h4>
    <div>{!! $response->recommendations !!}</div>
    @endif
    @endforeach

</body>

</html>