@inject('formatter', 'App\Helpers\FormatterHelper')
@extends('layouts.main')

@section('content')
    <div class="row expanded">
        <div class="column small-9">
            <h4 class="title">Media Impact Dashboard</h4>
        </div>
    </div>
    <div class="row expanded">
        <div class="column small-12">
            <div class="panel">
                <div class="top-bar">
                    <div class="top-bar-left">
                        Metrics Overview
                    </div>
                    <div class="top-bar-right">
                        @include('widgets.daterange', ['min_date' => $min_date, 'max_date' => $max_date])
                    </div>
                </div>
                <div class="row">
                    <div class="row small-up-2 medium-up-4 board">
                        <div class="column align-self-middle">
                            <div class="box" data-equalizer-watch="box">
                                <label class="number">28.94</label>
                                <div class="percent">percent</div>
                                <div class="desc">average returnning views</div>
                                <div class="values">+5% from 2012</div>
                            </div>
                        </div>
                        <div class="column">
                            <div class="box" data-equalizer-watch="box">
                                <label class="number">28.94</label>
                                <div class="percent">percent</div>
                                <div class="desc">average returnning views</div>
                                <div class="values">+5% from 2012</div>
                            </div>
                        </div>
                        <div class="column">
                            <div class="box" data-equalizer-watch="box">
                                <label class="number">28.94</label>
                                <div class="percent">percent</div>
                                <div class="desc">average returnning views</div>
                                <div class="values">+5% from 2012</div>
                            </div>                            
                        </div>
                        <div class="column">
                            <div class="box" data-equalizer-watch="box">
                                <div id="pie"></div>
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
    $(function(){
        AmCharts.makeChart("pie",
        {
            "type": "pie",
            "balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[  percents]]%)</span>",
            "titleField": "country",
            "valueField": "litres",
            "fontSize": 12,
            "theme": "light",
            "allLabels": [],
            "balloon": {},
            "titles": [],
            "dataProvider": [
                {
                    "country": "Czech Republic",
                    "litres": "356.9"
                },
                {
                    "country": "Ireland",
                    "litres": 131.1
                },
                {
                    "country": "Germany",
                    "litres": 115.8
                },
                {
                    "country": "Australia",
                    "litres": 109.9
                },
                {
                    "country": "Austria",
                    "litres": 108.3
                },
                {
                    "country": "UK",
                    "litres": 65
                },
                {
                    "country": "Belgium",
                    "litres": "20"
                }
            ]
        }
    );
    });
</script>
@endsection