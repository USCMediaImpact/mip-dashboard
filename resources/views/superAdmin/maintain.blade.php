@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="small-12 columns">
            <div class="panel client-info">
                <div class="top-bar">
                    <div class="top-bar-left">
                        Data Sync Monitor
                    </div>
                </div>
                <div class="row">
                    <div class="small-12 columns">
                        <table class="report text-center">
                            <thead>
                                <tr>
                                    <th rowspan="2">Week</th>
                                    @foreach($result as $item)
                                    <th colspan="3">{{$item['client']}}</th>
                                    @endforeach
                                    <th rowspan="2">Action</th>
                                </tr>
                                <tr>
                                    @foreach($result as $item)
                                    <th>Users</th>
                                    <th>Stories</th>
                                    <th>Data Quality</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>

                                @foreach($weeks as $date)
                                {{-- */$thisDate = date('Y-m-d', $date); $is_sync = false;/* --}}
                                <tr>
                                    <td>{{$thisDate}}</td>
                                    @foreach($result as $item)
                                    @if(array_key_exists($thisDate, $item['users']))
                                    {{-- */$is_sync = true;/* --}}
                                    <td>{!! $item['users'][$thisDate] ? 'Ready' : '<button class="button tiny">mark as ready</button>' !!}</td>
                                    @else
                                    <td></td>
                                    @endif
                                    @if(array_key_exists($thisDate, $item['stories']))
                                    <td>{!! $item['stories'][$thisDate] ? 'Ready' : '<button class="button tiny">mark as ready</button>' !!}</td>
                                    @else
                                    <td></td>
                                    @endif
                                    @if(array_key_exists($thisDate, $item['quality']))
                                    <td>{!! $item['quality'][$thisDate] ? 'Ready' : '<button class="button tiny">mark as ready</button>' !!}</td>
                                    @else
                                    <td></td>
                                    @endif
                                    @endforeach
                                    <td>
                                        @if(!$is_sync)
                                        <button class="button tiny">Sync</button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection