@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="small-12 column">
            <div id="chartdiv" style="width: 100%; height: 420px;"></div>
        </div>
        <div class="small-12 column">
            <table class="tiny">
                <thead>
                    <tr>
                        <td>Date</td>
                        <td>Page Views</td>
                        <td>Visits</td>
                        <td>Hits</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($report as $row)
                    <tr>
                        <td>{{ $row['date'] }}</td>
                        <td>{{ $row['pageviews'] }}</td>
                        <td>{{ $row['visits'] }}</td>
                        <td>{{ $row['hits'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('script')
    <script>
    var chart = AmCharts.makeChart("chartdiv", {
        "type": "serial",
        "theme": "light",
        "legend": {
            "equalWidths": false,
            "useGraphSettings": true,
            "valueAlign": "left",
            "valueWidth": 120
        },
        "dataProvider": {!! json_encode($report) !!},
        "valueAxes": [{
            "id": "pageViewsAxis",
            "axisAlpha": 0,
            "gridAlpha": 0,
            "position": "left",
            "title": "PageViews"
        }, {
            "id": "visitsAxis",
            "axisAlpha": 0,
            "gridAlpha": 0,
            "labelsEnabled": false,
            "position": "right"
        }, {
            "id": "hitsAxis",
            "axisAlpha": 0,
            "gridAlpha": 0,
            "inside": true,
            "position": "right",
            "title": "Hits"
        }],
        "graphs": [{
            "alphaField": "alpha",
            "balloonText": "pv: [[value]]",
            "dashLengthField": "dashLength",
            "fillAlphas": 0.7,
            "legendPeriodValueText": "pv: [[value]]",
            "legendValueText": "pv: [[value]]",
            "title": "page views",
            "type": "column",
            "valueField": "pageviews",
            "valueAxis": "pageViewsAxis"
        }, {
            "balloonText": "hits: [[value]]",
            "bullet": "round",
            "bulletBorderAlpha": 1,
            "useLineColorForBulletBorder": true,
            "bulletColor": "#FFFFFF",
            "bulletSizeField": "5",
            "dashLengthField": "dashLength",
            "labelPosition": "right",
            "labelText": "hits: [[value]]",
            "legendValueText": "hits: [[value]]",
            "title": "hits",
            "fillAlphas": 0,
            "valueField": "hits",
            "valueAxis": "hitsAxis"
        }, {
            "bullet": "square",
            "bulletBorderAlpha": 1,
            "bulletBorderThickness": 1,
            "dashLengthField": "dashLength",
            "legendValueText": "visits: [[value]]",
            "title": "Visits",
            "fillAlphas": 0,
            "valueField": "visits",
            "valueAxis": "visitsAxis"
        }],
        "chartCursor": {
            "categoryBalloonDateFormat": "DD",
            "cursorAlpha": 0.1,
            "cursorColor": "#000000",
            "fullWidth": true,
            "valueBalloonsEnabled": false,
            "zoomable": false
        },
        "dataDateFormat": "YYYYMMDD",
        "categoryField": "date",
        "categoryAxis": {
            "dateFormats": [{
                "period": "DD",
                "format": "DD"
            }, {
                "period": "WW",
                "format": "MMM DD"
            }, {
                "period": "MM",
                "format": "MMM"
            }, {
                "period": "YYYY",
                "format": "YYYY"
            }],
            "parseDates": true,
            "autoGridCount": false,
            "axisColor": "#555555",
            "gridAlpha": 0.1,
            "gridColor": "#FFFFFF",
            "gridCount": 50
        },
        "listeners": [{
            "event": "changed",
            "method": function (event) {
                chart.cursorDataContext = event.chart.dataProvider[event.index];
            }
        }, {
            "event": "rendered",
            "method": function (event) {
                event.chart.chartDiv.addEventListener('click', function () {
                    console.log(chart.cursorDataContext);
                });
            }
        }]
    });
    </script>
@endsection