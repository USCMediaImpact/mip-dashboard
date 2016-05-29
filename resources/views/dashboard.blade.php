@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="small-12 column">
            <table>
                <thead>
                    <tr>
                        <td>Date</td>
                        <td>Page View</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($report as $row)
                    <tr>
                        <td>{{ $row['date'] }}</td>
                        <td>{{ $row['value'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection