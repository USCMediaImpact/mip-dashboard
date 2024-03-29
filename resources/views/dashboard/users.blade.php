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
                    <div class="row small-up-2 medium-up-4 board" data-equalizer>
                        <div class="column align-self-middle text-center">
                            <div class="box" data-equalizer-watch>
                                <div class="box-content">
                                    <label class="number has-tip top" data-tooltip aria-haspopup="true"  title="Email Subscribers or Donors on Site">
                                        {{number_format($dataBox1To4[0]->Unduplicated_TotalUsersKPI)}}
                                    </label>
                                    <div class="desc">Loyal Users on Site</div>
                                    {{-- */$d1 = count($dataBox1To4) == 2 && $dataBox1To4[1]->Unduplicated_TotalUsersKPI != 0 ? ($dataBox1To4[0]->Unduplicated_TotalUsersKPI - $dataBox1To4[1]->Unduplicated_TotalUsersKPI ) / $dataBox1To4[1]->Unduplicated_TotalUsersKPI : '';/* --}}
                                    <div class="values" style="{{ !is_numeric($d1) || round($d1, 2) == 0 ? '' : ($d1 > 0 ? 'color:#7cc066;' : 'color:#ec4f43;') }}">{{$formatter->showAsPercent($d1)}} from previous week</div>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="column align-self-middle text-center">
                            <div class="box" data-equalizer-watch>
                                <div class="box-content">
                                    <label class="number has-tip top" data-tooltip aria-haspopup="true"  title="Email Subscribers or Donors in Database">
                                        {{number_format($dataBox1To4[0]->Unduplicated_Database_TotalUsersKPI)}}
                                    </label>
                                    <div class="desc">Loyal Users in Database</div>
                                    {{-- */$d2 = count($dataBox1To4) == 2 && $dataBox1To4[1]->Unduplicated_Database_TotalUsersKPI != 0 ? ($dataBox1To4[0]->Unduplicated_Database_TotalUsersKPI - $dataBox1To4[1]->Unduplicated_Database_TotalUsersKPI ) / $dataBox1To4[1]->Unduplicated_Database_TotalUsersKPI : '';/* --}}
                                    <div class="values" style="{{ !is_numeric($d2) || round($d2, 2) == 0 ? '' : ($d2 > 0 ? 'color:#7cc066;' : 'color:#ec4f43;') }}">{{$formatter->showAsPercent($d2)}} from previous week</div>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="column align-self-middle text-center">
                            <div class="box" data-equalizer-watch>
                                <div class="box-content">
                                    <label class="number has-tip top" data-tooltip aria-haspopup="true"  title="Email Subscribers or Donors in Database that Visited the Site">
                                        {{$formatter->showAsPercent($dataBox1To4[0]->Loyal_Users_On_Site)}}
                                    </label>
                                    <div class="desc">Percent of Loyal Users on Site</div>
                                    {{-- */$d3 = count($dataBox1To4) == 2 && $dataBox1To4[1]->Loyal_Users_On_Site != 0 ? ($dataBox1To4[0]->Loyal_Users_On_Site - $dataBox1To4[1]->Loyal_Users_On_Site ) / $dataBox1To4[1]->Loyal_Users_On_Site : '';/* --}}
                                    {{-- */$d4 = count($dataBox1To4) == 2 ? $dataBox1To4[0]->Loyal_Users_On_Site - $dataBox1To4[1]->Loyal_Users_On_Site : '';/* --}}
                                    <div class="values" style="{{ !is_numeric($d3) || round($d3, 2) == 0 ? '' : ($d3 > 0 ? 'color:#7cc066;' : 'color:#ec4f43;') }}">{{$formatter->showAsPercent($d3)}} change from previous week</div>
                                </div>
                            </div>                         
                        </div>
                        <div class="column align-self-middle">
                            <div class="box" data-equalizer-watch>
                                <div class="title text-center">Loyal Users</div>
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
                            <div class="row-box">
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
                    var form = $('#dateRangeDashboard').parents('form'),
                        min_date = moment(date, 'MM/DD/YYYY').day(0),
                        max_date = moment(date, 'MM/DD/YYYY').day(6);
                    $('#dateRangeDashboard').daterangepicker('setRange', {
                        start: min_date.toDate(),
                        end: max_date.toDate()
                    });
                    $('#dateRangeDashboard').daterangepicker('close');
                    $('input[name="min_date"]', form).val(min_date.format('YYYY-MM-DD'));
                    $('input[name="max_date"]', form).val(max_date.format('YYYY-MM-DD'));
                    form.submit();
                }
            }
        });
        /**
         * set default range
         */
        if(!$('#dateRangeDashboard').daterangepicker('getRange')){
            $('#dateRangeDashboard').daterangepicker('setRange', {
                start: moment('{{date('Y-m-d', $min_date)}}').toDate(),
                end: moment('{{date('Y-m-d', $max_date)}}').toDate()
            });
        }
        AmCharts.makeChart('box4', {
            type: 'pie',
            theme: 'light',
            dataProvider: [{
                title: 'Email Subscribers',
                value: {{$dataBox1To4[0]->KPI_TotalEmailSubscribersKnownToMIP}}
            }, {
                title: 'Donors',
                value: {{$dataBox1To4[0]->KPI_TotalDonorsKnownToMIP}}
            }],
            titleField: 'title',
            valueField: 'value',
            labelsEnabled: false,
            radius: '42%',
            innerRadius: '60%',
            balloonText: '[[title]]<br />([[value]] | [[percents]]%)',
            export: {
                enabled: true
            }
        });

        AmCharts.makeChart('box5', {
            type: 'serial',
            theme: 'light',
            dataProvider: {!!json_encode($dataBox5)!!},
            categoryField: 'date',
            categoryAxis: {
                parseDates: true,
                dataDateFormat: 'YYYY-MM-DD',
                firstDayOfWeek: 0,
                equalSpacing: true,
                minPeriod: 'WW',
                dateFormats: [{
                    period: 'fff',
                    format: 'JJ:NN:SS'
                }, {
                    period: 'ss',
                    format: 'JJ:NN:SS'
                }, {
                    period: 'mm',
                    format: 'JJ:NN'
                }, {
                    period: 'hh',
                    format: 'JJ:NN'
                }, {
                    period: 'DD',
                    format: 'weekW\nYYYY'
                }, {
                    period: 'WW',
                    format: 'weekW\nYYYY'
                }, {
                    period: 'MM',
                    format: 'weekW\nYYYY'
                }, {
                    period: 'YYYY',
                    format: 'YYYY'
                }],
                autoGridCount: false,
                gridCount: 20,
            },
            valueAxes: [{
                stackType: 'regular',
                axisAlpha: 0.3,
                gridAlpha: 0
            }, {
                "id": "lastYear",
                "axisAlpha": 0,
                "gridAlpha": 0,
                "position": "right",
            }],
            chartCursor: {
                oneBalloonOnly: true
            },
            graphs: [{
                balloonFunction: function(item, graph){
                    var html = '<div class="text-left">';
                    html += 'Came To Site Through Email: ';
                    html += AmCharts.formatNumber(item.dataContext.CameToSiteThroughEmail, {
                        precision: 2, 
                        decimalSeparator: '.', 
                        thousandsSeparator: ','
                    }, 0);
                    html += '<br />';
                    html += 'Total Donors This Week: ';
                    html += AmCharts.formatNumber(item.dataContext.TotalDonorsThisWeek, {
                        precision: 2, 
                        decimalSeparator: '.', 
                        thousandsSeparator: ','
                    }, 0);
                    html += '<br />';
                    html += 'Loyal Users for Week of <br />';
                    html += moment(item.dataContext.LastYearDate).format('MMM D, YYYY');
                    html += ': ';
                    if(item.dataContext.LastYearTotal){
                        html += AmCharts.formatNumber(item.dataContext.LastYearTotal, {
                            precision: 2, 
                            decimalSeparator: '.', 
                            thousandsSeparator: ','
                        }, 0);
                    }else{
                        html += 'N/A';
                    }
                    html += '</div>'
                    return html;
                },
                fillAlphas: 0.8,
                labelText: '[[value]]',
                lineAlpha: 0.3,
                title: 'Came To Site Through Email',
                type: 'column',
                fillColors: ['#487aa9'],
                color: '#000000',
                valueField: 'CameToSiteThroughEmail'
            }, {
                balloonText: '[[title]]<br />[[category]]:[[value]]',
                showBalloon: false,
                fillAlphas: 0.8,
                labelText: '[[value]]',
                lineAlpha: 0.3,
                title: 'Total Donors This Week',
                type: 'column',
                fillColors: ['#5ea0dd'],
                color: '#000000',
                valueField: 'TotalDonorsThisWeek'
            }, {
                balloonText: '[[title]] for Week of <br />[[category]]:<b>[[value]]</b>',
                showBalloon: false,
                bullet: 'round',
                lineThickness: 3,
                bulletSize: 7,
                bulletBorderAlpha: 1,
                bulletColor: '#FFFFFF',
                useLineColorForBulletBorder: true,
                bulletBorderThickness: 3,
                fillAlphas: 0,
                lineAlpha: 1,
                fillColors: ['#bcbdbe'],
                title: 'Loyal Users',
                valueField: 'LastYearTotal',
                valueAxis: 'lastYear',
                dashLengthField: 'dashLengthLine',
                connect: false
            }]
        });
        AmCharts.makeChart('box6', {
            type: 'serial',
            theme: 'light',
            dataProvider: {!!json_encode($dataBox6)!!},
            categoryField: 'date',
            categoryAxis: {
                parseDates: true,
                dataDateFormat: 'YYYY-MM-DD',
                firstDayOfWeek: 0,
                equalSpacing: true,
                minPeriod: 'WW',
                dateFormats: [{
                    period: 'fff',
                    format: 'JJ:NN:SS'
                }, {
                    period: 'ss',
                    format: 'JJ:NN:SS'
                }, {
                    period: 'mm',
                    format: 'JJ:NN'
                }, {
                    period: 'hh',
                    format: 'JJ:NN'
                }, {
                    period: 'DD',
                    format: 'weekW\nYYYY'
                }, {
                    period: 'WW',
                    format: 'weekW\nYYYY'
                }, {
                    period: 'MM',
                    format: 'weekW\nYYYY'
                }, {
                    period: 'YYYY',
                    format: 'YYYY'
                }],
                autoGridCount: false,
                gridCount: 20,
            },
            valueAxes: [{
                stackType: 'regular',
                axisAlpha: 0.3,
                gridAlpha: 0
            }],
            chartCursor: {
                oneBalloonOnly: true
            },
            graphs: [{
                balloonText: '[[title]]:[[value]]',
                bullet: 'round',
                lineThickness: 3,
                bulletSize: 7,
                bulletBorderAlpha: 1,
                bulletColor: '#FFFFFF',
                useLineColorForBulletBorder: true,
                bulletBorderThickness: 3,
                fillAlphas: 0,
                lineAlpha: 1,
                title: 'Total Known Users % Change',
                valueField: 'changes',
                dashLengthField: 'dashLengthLine'
            }]
        });
    });
</script>
@endsection