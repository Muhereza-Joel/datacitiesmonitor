<!DOCTYPE html>
<html>

<head>
    <title>Indicator Report</title>
    <style>
        @page {
            margin: 100px 25px;
            /* Adjust margins as needed */
        }

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

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #f8f9fa;
            padding: 20px;

        }

        .header img {
            width: 300px;
            /* Logo width */
            object-fit: contain;
        }

        .header h1 {
            margin: 10px 0 5px;
            font-size: 24px;
            color: #343a40;
        }

        .header h3 {
            margin: 10px 0 5px;
            font-size: 18px;
            color: #343a40;
        }

        .header p {
            margin: 5px 0 0;
            font-size: 16px;
            color: #6c757d;
        }

        .header .date {
            font-weight: bold;
            color: #007bff;
        }

        .qr-code {
            width: 150px;
            /* Adjust QR code size as needed */
            height: 150px;
            /* Adjust QR code size as needed */
        }

        .page-number {
            font-size: 12px;
        }
    </style>
</head>

<body>
    <table style="width: 100%; border-collapse: collapse; background-color: #f8f9fa; border-bottom: 2px solid #007bff;">
        <tr>
            <td style="border: none; vertical-align: top; text-align: center; line-height: 1.0">
                <div class="header">
                    <div>
                        <h1>Datacities M $ E Monitor</h1>
                        <h3>Indicator Report For</h3>
                        <p>{{ $indicator->indicator_title }}</p>
                        <p class="date">As of {{ now()->format('F j, Y') }}</p>
                    </div>
                    <div>

                    </div>
                </div>
            </td>
            <td style="border: none; text-align: right; vertical-align: middle;">
                <img src="data:image/png;base64,{{ $qrCodeBase64 }}" alt="QR Code" class="qr-code">
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td>Indicator Id</td>
            <td>{{ $indicator->id }}</td>
        </tr>
        <tr>
            <td>Category</td>
            <td>{{ $indicator->category === 'None' ? 'Un Categorised' :  $indicator->category }} Indicator</td>
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
        <p>
            Response {{ $loop->iteration }} with ID {{ $response->id }} was recorded on {{ $response->created_at }}.
            <strong>The current state of the indicator is</strong> {{ $response->current }}, while its status is {{ $response->status }}.
            The percentage progress compared to the baseline is {{ $response->current }} percent.
        </p>

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

    <footer>
        <div class="page-number">PDF Document Generate By the Datacities M $ E Monitor</div>
    </footer>
</body>

</html>