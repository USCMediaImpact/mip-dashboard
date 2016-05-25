@extends('layouts.main')

@section('content')
    <div class="chartContainer"></div>
@endsection

@section('script')
    $('.chartContainer').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'spline'
        },
        title: {
            text: 'Page View. Apr, 2016'
        },
        xAxis: {
            categories: {!! json_encode($category) !!}
        },
        yAxis: {
            title: {
                text: 'Page View'
            },
            min: 0
        },
        plotOptions: {
            spline: {
                marker: {
                    enabled: true
                }
            }
        },
        series: [{
            name: 'page view',
            data: {!! json_encode($pv) !!}
        }]
    });
@endsection