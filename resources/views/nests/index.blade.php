<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Saved Nests</title>
</head>
<body>
  <h1>Saved Nests</h1>

  @if(session('success'))
    <p style="color:green;">{{ session('success') }}</p>
  @endif

  <table border="1" cellpadding="8">
    <tr>
      <th>Date</th>
      <th>Species</th>
      <th>Photo</th>
      <th>Notes</th>
    </tr>
    @foreach($nests as $nest)
      <tr>
        <td>{{ $nest->date }}</td>
        <td>{{ $nest->species }}</td>
        <td>
          @if($nest->photo_path)
            <img src="{{ asset('storage/'.$nest->photo_path) }}" alt="photo" width="100">
          @endif
        </td>
        <td>{{ $nest->notes }}</td>
      </tr>
    @endforeach
  </table>
</body>
</html>
