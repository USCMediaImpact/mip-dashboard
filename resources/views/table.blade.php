@extends('layouts.main')

@section('content')
    <table>
        <thead>
            <tr>
                <td>Date</td>
                <td>Page View</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($pv as $row)
            <tr>
                <td>{{ $row['date'] }}</td>
                <td>{{ $row['pv'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection