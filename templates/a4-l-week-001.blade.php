<!DOCTYPE html>
<html>
<head>
    <title>S{{ $week }}-Q{{ $quarter }}-{{ $year }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        {!! $assets->css->styles !!}
    </style>

    {{-- favicon --}}
    <link
        type="image/svg+xml"
        rel="shortcut icon"
        href={{ $assets->images->favicon }}
        sizes="24x24">
</head>
<body>
    <div id="toolsbar">
        <form action="" method="get">
            <label for="date">Select a date:</label>
            <input type="date" id="date" name="date" value={{ $today }} onchange="this.form.submit()">
        </form>
        <button onclick="window.print();" class="noPrint">Print</button>
    </div>

    <div class="container" id="toPrint">

        <div class="title">
            <span> {{ $startOfWeek }}</span>
            <h1>{{ $weekMount }}° week of {{ $month }} - S{{ $week }}·Q{{ $quarter }}·{{ $year }}</h1>
            <span> {{ $endOfWeek }}</span>
        </div>

        <div class="grid">
            @foreach ($days as $dayName => $day)
                <div class="day {{ $day->working ? 'full-height' : 'half-height' }}">
                    <div class="day-data">
                        <h2>{{ $dayName }}</h2>
                        <h3>({{ $day->dayOfYear }}) {{ $day->date }}</h3>
                    </div>
                    <div class="hours">
                        @if( $day->working )
                            @for ( $hour = $working->hours->start; $hour < $working->hours->end; $hour += $working->hours->step)
                                <div class="hour">{{ $hour }}:00</div>
                            @endfor
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="notes">
            <h2>Notes:</h2>
        </div>
    </div>
</body>
</html>