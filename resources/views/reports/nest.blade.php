<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nest Report</title>
</head>
<body>
    <h1>Nest #{{ $nest->id }} - {{ $nest->species }}</h1>
    <p><strong>Date:</strong> {{ $nest->date }}</p>
    <p><strong>Location:</strong> {{ $nest->location }}</p>
    <p><strong>Egg Count:</strong> {{ $nest->egg_count }}</p>
    <p><strong>Notes:</strong> {{ $nest->notes }}</p>

    <h2>Threats</h2>
    <ul>
      @foreach($nest->threats as $threat)
        <li>{{ $threat->threat_type }} - {{ $threat->notes }}</li>
      @endforeach
    </ul>

    <h2>Incubation Logs</h2>
    <ul>
      @foreach($nest->incubations as $incubation)
        <li>{{ $incubation->status }} - {{ $incubation->notes }}</li>
      @endforeach
    </ul>

    <h2>Hatchling Releases</h2>
    <ul>
      @foreach($nest->hatchReleases as $release)
        <li>{{ $release->hatchdate }} - {{ $release->number }} hatchlings</li>
      @endforeach
    </ul>
</body>
</html>
