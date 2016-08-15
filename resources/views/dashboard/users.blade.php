@inject('formatter', 'App\Helpers\FormatterHelper')
@extends('layouts.main')

@section('content')
    <div class="dashboard">
        <div class="row expanded">
            <div class="column small-9">
                <h4 class="title">Media Impact Dashboard</h4>
                <h5 class="sub-title">Metrics and Charts by Week</h5>
            </div>
            <div class="column small-3">
                <form method="POST">
                    {!! csrf_field() !!}
                    <input id="dateRangeDashboard" name="date_range" />
                    <input type="hidden" name="min_date" value="{{ date('Y-m-d', $min_date) }}" />
                    <input type="hidden" name="max_date" value="{{ date('Y-m-d', $max_date) }}" />
                </form>
            </div>
        </div>
        <div class="row expanded">
            <div class="column small-12">
                <div class="panel">
                    <div class="top-bar">
                        <div class="top-bar-left">
                            Users Overview
                        </div>
                        <div class="top-bar-right">
                            
                        </div>
                    </div>
                    <div class="row small-up-2 medium-up-4 board">
                        <div class="column align-self-middle text-center">
                            <div class="box">
                                <div class="box-content">
                                    <label class="number has-tip top" data-tooltip aria-haspopup="true"  title="Email Subscribers or Donors on Site">
                                        {{number_format($dataBox1To4[0]->Unduplicated_TotalUsersKPI)}}
                                    </label>
                                    <div class="desc">Loyal Users on Site</div>
                                    {{-- */$d1 = count($dataBox1To4) == 2 ? ($dataBox1To4[0]->Unduplicated_TotalUsersKPI - $dataBox1To4[1]->Unduplicated_TotalUsersKPI ) / $dataBox1To4[1]->Unduplicated_TotalUsersKPI : '';/* --}}
                                    <div class="values" style="{{ $d1 ? 'color:#ec4f43;' : 'color:#7cc066;' }}">{{$formatter->showAsPercent($d1)}} from previous week</div>
                                </div>
                            </div>
                        </div>
                        <div class="column align-self-middle text-center">
                            <div class="box">
                                <div class="box-content">
                                    <label class="number has-tip top" data-tooltip aria-haspopup="true"  title="Email Subscribers or Donors in Database">
                                        {{number_format($dataBox1To4[0]->Unduplicated_Database_TotalUsersKPI)}}
                                    </label>
                                    <div class="desc">Loyal Users in Database</div>
                                    {{-- */$d2 = count($dataBox1To4) == 2 ? ($dataBox1To4[0]->Unduplicated_Database_TotalUsersKPI - $dataBox1To4[1]->Unduplicated_Database_TotalUsersKPI ) / $dataBox1To4[1]->Unduplicated_Database_TotalUsersKPI : '';/* --}}
                                    <div class="values" style="{{ $d2 ? 'color:#ec4f43;' : 'color:#7cc066;' }}">{{$formatter->showAsPercent($d2)}} from previous week</div>
                                </div>
                            </div>
                        </div>
                        <div class="column align-self-middle text-center">
                            <div class="box">
                                <div class="box-content">
                                    <label class="number has-tip top" data-tooltip aria-haspopup="true"  title="Email Subscribers or Donors in Database that Visited the Site">
                                        {{$formatter->showAsPercent($dataBox1To4[0]->Loyal_Users_On_Site)}}
                                    </label>
                                    <div class="desc">Percent of Loyal Users on Site</div>
                                    {{-- */$d3 = count($dataBox1To4) == 2 ? ($dataBox1To4[0]->Loyal_Users_On_Site - $dataBox1To4[1]->Loyal_Users_On_Site ) / $dataBox1To4[1]->Loyal_Users_On_Site : '';/* --}}
                                    {{-- */$d4 = count($dataBox1To4) == 2 ? $dataBox1To4[0]->Loyal_Users_On_Site - $dataBox1To4[1]->Loyal_Users_On_Site : '';/* --}}
                                    <div class="values" style="{{ $d3 ? 'color:#ec4f43;' : 'color:#7cc066;' }}">{{$formatter->showAsPercent($d2)}} change from previous week</div>
                                    <div class="values" style="{{ $d4 ? 'color:#ec4f43;' : 'color:#7cc066;' }}">{{$formatter->showAsPercent($d4)}} points from previous week</div>
                                </div>
                            </div>                         
                        </div>
                        <div class="column align-self-middle">
                            <div class="box">
                                <div id="box4" class="chart"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12">
                            <div class="row-box">
                                <p class="chart-title text-center">{{date('Y', $min_date)}} vs {{date('Y', strtotime('-1 years', $min_date))}}</p>
                                <div id="box5" style="min-height: 300px;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns small-12">
                            <div class="box">
                                <p class="chart-title text-center">% Change from {{date('Y', $min_date)}} to {{date('Y', strtotime('-1 years', $min_date))}}</p>
                                <div id="box6" style="min-height: 300px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    DefaultDateRangePickerOptions = {
        datepickerOptions: {
            minDate: moment('{{$date_range_min}}').toDate(),
            maxDate: moment('{{$date_range_max}}').toDate()
        }
    };
    $(function () {
        $('#dateRangeDashboard').daterangepicker({
            dateFormat: 'M d, yy',
            presetRanges: [],
            datepickerOptions: {
                minDate: moment('{{$date_range_min}}').toDate(),
                maxDate: moment('{{$date_range_max}}').toDate(),
                numberOfMonths: 1,
                showOtherMonths: true,
                selectOtherMonths: true,
                onSelect: function (date, el) {
                    var form = $('#dateRangeDashboard').parents('form');
                    min_date = moment(date, 'MM/DD/YYYY').day(0),
                        max_date = moment(date, 'MM/DD/YYYY').day(6);
                    $('#dateRangeDashboard').daterangepicker('setRange', {
                        start: min_date.toDate(),
                        end: max_date.toDate()
                    });
                    $('#dateRangeDashboard').daterangepicker('close');
                    $('input[name="min_date"]', form).val(min_date.format('YYYY-MM-DD'));
                    $('input[name="max_date"]', form).val(min_date.format('YYYY-MM-DD'));
                    form.submit();
                }
            }
        });
        AmCharts.makeChart("box4", {
            "type": "pie",
            "theme": "light",
            "dataProvider": [{
                'title': 'Email Subscribers',
                "value": {{$dataBox1To4[0]->KPI_TotalEmailSubscribersKnownToMIP}}
            }, {
                'title': 'Donors',
                "value": {{$dataBox1To4[0]->KPI_TotalDonorsKnownToMIP}}
            }],
            "titleField": "title",
            "valueField": "value",
            labelsEnabled: false,
            "radius": "42%",
            "innerRadius": "60%",
            "labelText": "[[title]]( [[value]] | )",
            "export": {
                "enabled": true
            }
        });
        AmCharts.makeChart("box5", {
            "type": "serial",
            "theme": "light",
            "dataProvider": {!!json_encode($dataBox5)!!},
            "categoryField": "date",
            "valueAxes": [{
                "stackType": "regular",
                "axisAlpha": 0.3,
                "gridAlpha": 0
            }],
            "graphs": [{
                "balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
                "fillAlphas": 0.8,
                "labelText": "[[value]]",
                "lineAlpha": 0.3,
                "title": "CameToSiteThroughEmail",
                "type": "column",
                'fillColors': ['#487aa9'],
                "color": "#000000",
                "valueField": "CameToSiteThroughEmail"
            }, {
                "balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
                "fillAlphas": 0.8,
                "labelText": "[[value]]",
                "lineAlpha": 0.3,
                "title": "TotalDonorsThisWeek",
                "type": "column",
                'fillColors': ['#5ea0dd'],
                "color": "#000000",
                "valueField": "TotalDonorsThisWeek"
            }, {
                "balloonText": "<span style='font-size:12px;'>[[title]] in [[category]]:<br><span style='font-size:20px;'>[[value]]</span> [[additional]]</span>",
                "bullet": "round",
                "lineThickness": 3,
                "bulletSize": 7,
                "bulletBorderAlpha": 1,
                "bulletColor": "#FFFFFF",
                "useLineColorForBulletBorder": true,
                "bulletBorderThickness": 3,
                "fillAlphas": 0,
                "lineAlpha": 1,
                'fillColors': ['#bcbdbe'],
                "title": "TotalDonorsThisWeek + CameToSiteThroughEmail",
                "valueField": "lastYear",
                "dashLengthField": "dashLengthLine"
            }]
        });
        AmCharts.makeChart("box6", {
            "type": "serial",
            "theme": "light",
            "dataProvider": {!!json_encode($dataBox6)!!},
            "categoryField": "date",
            "valueAxes": [{
                "stackType": "regular",
                "axisAlpha": 0.3,
                "gridAlpha": 0
            }],
            "graphs": [{
                "balloonText": "<span style='font-size:12px;'>[[title]] in [[category]]:<br><span style='font-size:20px;'>[[value]]</span> [[additional]]</span>",
                "bullet": "round",
                "lineThickness": 3,
                "bulletSize": 7,
                "bulletBorderAlpha": 1,
                "bulletColor": "#FFFFFF",
                "useLineColorForBulletBorder": true,
                "bulletBorderThickness": 3,
                "fillAlphas": 0,
                "lineAlpha": 1,
                "title": "TotalDonorsThisWeek + CameToSiteThroughEmail",
                "valueField": "change",
                "dashLengthField": "dashLengthLine"
            }]
        });
    });
</script>
@endsection