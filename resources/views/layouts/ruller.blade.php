<div class="mt-3">
    <div style="position: relative; height: 20px; background-color: #f0f0f0; border-radius: 5px; border: 1px solid #ccc;">
        @php
        // Ensure baseline, current, and target are non-negative and valid
        $baseline = max($indicator->baseline, 0);
        $current = isset($indicator->current) ? max($indicator->current, 0) : 0; // Default to 0 if null
        $target = max($indicator->target, 0);

        // Calculate the minimum and maximum values for range determination
        $minValue = min($baseline, $current, $target);
        $maxValue = max($baseline, $current, $target);
        $range = max($maxValue - $minValue, 1); // Avoid zero range

        // Calculate positions
        $baselinePosition = (($baseline - $minValue) / $range) * 100;
        $currentPosition = (($current - $minValue) / $range) * 100;
        $targetPosition = (($target - $minValue) / $range) * 100;

        // Ensure minimum spacing between markers
        $minSpacing = 2; // Minimum percentage spacing
        if (abs($baselinePosition - $currentPosition) < $minSpacing) {
            $currentPosition=$baselinePosition + $minSpacing;
            }
            if (abs($currentPosition - $targetPosition) < $minSpacing) {
            $targetPosition=$currentPosition + $minSpacing;
            }

            // Handle case where all values are equal
            if ($baseline===$current && $current===$target) {
            $currentPosition=$baselinePosition; // Align current with baseline and target
            }
            @endphp


            <!-- Faint Shade from Baseline to Current (For both directions) -->
            <div style="position: absolute; left: {{ min($baselinePosition, $currentPosition) }}%; right: {{ 100 - max($baselinePosition, $currentPosition) }}%; height: 100%; background-color: rgba(144, 238, 144, 0.3); border-radius: 3px;" title="Shaded Area"></div>

            <!-- Baseline Marker -->
            <div style="position: absolute; left: {{ $baselinePosition }}%; width: 6px; height: 100%; background-color: rgba(0, 0, 255, 0.5); border-radius: 3px;" title="Baseline"></div>

            <!-- Current State Marker -->
            <div style="position: absolute; left: {{ $currentPosition }}%; width: 6px; height: 100%; background-color: green; border-radius: 3px;" title="Current State"></div>

            <!-- Target Marker -->
            <div style="position: absolute; left: {{ $targetPosition }}%; width: 6px; height: 100%; background-color: red; border-radius: 3px;" title="Target"></div>
    </div>

    <!-- Horizontal Arrow Indicating Progress Direction -->
    <div style="position: relative; margin-top: 5px;">
        <div style="position: absolute; left: {{ $baselinePosition }}%; right: {{ 100 - $targetPosition }}%; height: 0; border-top: 2px solid {{ $baseline < $target ? 'green' : 'red' }};">
            <div style="position: absolute; {{ $baseline < $target ? 'right: 0;' : 'left: 0;' }} top: -5px; border-left: 5px solid transparent; border-right: 5px solid transparent; border-bottom: 5px solid {{ $baseline < $target ? 'green' : 'red' }};"></div>
        </div>
    </div>

    <div class="px-1" style="position: relative; margin-top: 15px;">
        <div style="position: relative; height: 10px;">
            <div style="position: absolute; left: 0; top: 0; height: 100%; width: 100%; border-top: 1px solid #aaa;"></div>

            @php
            // Set start and end values for the loop
            $start = min($baseline, $current, $target);
            $end = max($baseline, $current, $target);
            $step = ($end - $start) / 10;
            $formatDecimals = $step < 1; // Add decimal points if the step is less than 1
                @endphp

                @for ($i=$start; $i <=$end; $i +=$step)
                <div style="position: absolute; left: {{ (($i - $start) / ($end - $start)) * 100 }}%; height: 15px; border-left: 1px solid #aaa;">
        </div>
        <div style="position: absolute; left: {{ (($i - $start) / ($end - $start)) * 100 }}%; top: 15px; transform: translateX(-50%);">
            {{ $formatDecimals ? number_format($i, 1) : number_format($i, 0) }}
        </div>
        @endfor
    </div>
</div>

<div class="d-flex justify-content-between mt-2 pt-3">
    <div style="text-align: center;">
        <span style="color: rgba(0, 0, 255, 0.5);">{{ $baseline }}</span><br>
        <small>Baseline</small>
    </div>
    <div style="text-align: center;">
        <span style="color: green;">{{ $current }}</span><br>
        <small>Current State</small>
    </div>
    <div style="text-align: center;">
        <span style="color: red;">{{ $target }}</span><br>
        <small>Target</small>
    </div>
</div>
</div>