<table>
    <thead>
        <tr>
            <th>User ID</th>
            <th>User</th>
            <th>Pemrosesan</th>
            <th>Persepsi</th>
            <th>Input</th>
            <th>Pemahaman</th>
            <th>Tanggal</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($participants as $p)
            <tr>
                <td>{{ $p['id'] }}</td>
                <td>{{ $p['user'] }}</td>
                <td>{{ $p['email'] }}</td>
                <td>{{ $p['pemrosesan'] }}</td>
                <td>{{ $p['persepsi'] }}</td>
                <td>{{ $p['input'] }}</td>
                <td>{{ $p['pemahaman'] }}</td>
                <td>{{ $p['tanggal'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
