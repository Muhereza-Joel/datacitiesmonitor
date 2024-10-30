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
            vertical-align: top;
            /* Aligns content to the top for better readability */
            word-wrap: break-word;
            /* Allows content to break within cells */
            white-space: pre-wrap;
            /* Preserves whitespace and wraps long content */
        }

        .extra-info {
            margin-top: 10px;
            /* Adds a bit of spacing between the response details and additional info */
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
                <th>Response ID</th>
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
            <!-- Additional Information for Each Response -->
            <tr>
                <td colspan="5" class="extra-info">
                    @if ($response->notes !== '')
                    <strong>Notes:</strong>
                    <div>{!! nl2br($response->notes) !!}</div>
                    @endif
                </td>
            </tr>
            <tr>
                <td colspan="5" class="extra-info">
                    @if ($response->lessons !== '')
                    <strong>Lessons:</strong>
                    <div>{!! nl2br($response->lessons) !!}</div>
                    @endif
                </td>
            </tr>
            <tr>
                <td colspan="5" class="extra-info">
                    @if ($response->recommendations !== '')
                    <strong>Recommendations:</strong>
                    <div>{!! nl2br($response->recommendations) !!}</div>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>