<!DOCTYPE html>
<html>

<head>
    <title>Indicator Report</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            page-break-inside: avoid;
            /* Prevent page break inside table */
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
            page-break-inside: avoid;
            /* Prevents page break inside a cell */
        }

        .additional-info {
            margin-top: 10px;
            /* Adds spacing above additional info */
            background-color: #f9f9f9;
            /* Light gray background for additional info */
            padding: 10px;
            /* Padding for better readability */
            border-top: 1px solid #ccc;
            /* Border to separate from the response row */
            page-break-inside: avoid;
            /* Prevent page break inside additional info */
        }

        strong {
            display: block;
            /* Makes the strong text stand out as a block */
            margin-top: 5px;
            /* Adds spacing above each strong element */
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
    @foreach ($indicator->responses as $response)
    <div class="response-entry">
        <p><strong>Response ID:</strong> {{ $response->id }}</p>
        <p><strong>Status:</strong> {{ $response->status }}</p>
        <p><strong>Current State:</strong> {{ $response->current }}</p>
        <p><strong>Percentage Progress From Baseline:</strong> {{ $response->progress }}%</p>
        <p><strong>Created At:</strong> {{ $response->created_at }}</p>

        <div class="additional-info">
            <strong>Additional Information:</strong>
            @if ($response->notes !== '')
            <p><strong>Notes:</strong></p>
            <p>{!! nl2br($response->notes) !!}</p>
            @endif

            @if ($response->lessons !== '')
            <p><strong>Lessons:</strong></p>
            <p>{!! nl2br($response->lessons) !!}</p>
            @endif

            @if ($response->recommendations !== '')
            <p><strong>Recommendations:</strong></p>
            <p>{!! nl2br($response->recommendations) !!}</p>
            @endif
        </div>
        <hr>
    </div>
    @endforeach


</body>

</html>