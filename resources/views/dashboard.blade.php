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

    });
</script>
@endsection