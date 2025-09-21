@extends('dashboard') {{-- use your custom dashboard layout --}}

@section('content')
    <h2>Create Threat Report</h2>

    <form action="{{ route('threats.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <label for="nest_id">Nest #</label>
        <select name="nest_id" id="nest_id" required>
            @foreach($nests as $nest)
                <option value="{{ $nest->id }}">
                    Nest #{{ $nest->id }} - {{ $nest->species }}
                </option>
            @endforeach
        </select>

        <label for="threat_type">Threat Type</label>
        <select name="threat_type" id="threat_type" required>
            <option value="Debris">Debris</option>
            <option value="Light">Light</option>
            <option value="Pollution">Pollution</option>
            <option value="Unauthorized activity">Unauthorized activity</option>
        </select>

        <label for="photo">Upload Photo</label>
        <input type="file" name="photo" id="photo">

        <label for="notes">Notes</label>
        <textarea name="notes" id="notes"></textarea>

        <button type="submit">Save</button>
    </form>
@endsection
