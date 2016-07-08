@inject('formatter', 'App\Helpers\FormatterHelper')
@extends('layouts.main')

@section('content')
	@if ($have_data)
		<form id="form_data_quality" method="GET">
		
		<div class="row expanded">
			<div class="column small-9">
				<h4 class="title">Weekly Story Performance</h4>
				<h6 class="sub-title">with Data from Oracle Eloqua</h6>
			</div>
			<div class="column small-3 align-self-bottom">
				@include('widgets.daterange', ['min_date' => $min_date, 'max_date' => $max_date])
			</div>
		</div>
		<div class="row expanded">
			<div class="column small-12">
				<div class="panel">
					<div class="top-bar">
						<div class="top-bar-left">
							Scroll Depth
						</div>
						<div class="top-bar-right">
							<div class="button-group tiny">
								<button class="button">Percent</button>
								<button class="button">Count</button>
								<span>&nbsp;</span>
								<button class="button small">Download</button>
							</div>
						</div>
					</div>
					<div class="table-scroll">
						<table class="report tiny hover expanded">
				            <thead>
				                <tr>
				                	<th>Article</th>
				                    <th>Total Page Views</th>
				                    <th>Started Scrolling</th>
				                    <th>25%<br />Scroll</th>
				                    <th>50%<br />Scroll</th>
				                    <th>75%<br />Scroll</th>
				                    <th>100%<br />Scroll</th>
				   					<th>Related Content</th>
				   					<th>End of Page</th>
				                </tr>
				            </thead>
				            <tbody>
				                @foreach ($report as $row)
				                <tr>
				                    <td>{{ $row['Page_Path'] }}</td>
				                   	<td>{{ number_format($row['Pageviews']) }}</td>
				                   	<td>{{ number_format($row['Scroll_Start']) }}</td>
				                   	<td>{{ number_format($row['Scroll_25']) }}</td>
				                    <td>{{ number_format($row['Scroll_50']) }}</td>
				                    <td>{{ number_format($row['Scroll_75']) }}</td>
				                    <td>{{ number_format($row['Scroll_100']) }}</td>
				                    <td>{{ number_format($row['Scroll_Supplemental']) }}</td>
				                    <td>{{ number_format($row['Scroll_End']) }}</td>
				                </tr>
				                @endforeach
				            </tbody>
				        </table>
					</div>
		        </div>
		        <div class="panel">
					<div class="top-bar">
						<div class="top-bar-left">
							Time on Article
						</div>
						<div class="top-bar-right">
							<button class="button">Download</button>
						</div>
					</div>
					<div class="table-scroll">
						<table class="report tiny hover expanded">
				            <thead>
				                <tr>
				                	<th>Article</th>
				                    <th>Total Page Views</th>
				                    <th>15 <br />Seconds</th>
				                    <th>30 <br />Seconds</th>
				                    <th>45 <br />Seconds</th>
				                    <th>60 <br />Seconds</th>
				                    <th>75 <br />Seconds</th>
				                    <th>90 <br />Seconds</th>
				                </tr>
				            </thead>
				            <tbody>
				                @foreach ($report as $row)
				                <tr>
				                    <td>{{ $row['Page_Path'] }}</td>
				                   	<td>{{ number_format($row['Pageviews']) }}</td>
				                    <td>{{ number_format($row['Time_15']) }}</td>
				                    <td>{{ number_format($row['Time_30']) }}</td>
				                    <td>{{ number_format($row['Time_45']) }}</td>
				                    <td>{{ number_format($row['Time_60']) }}</td> 
				                    <td>{{ number_format($row['Time_75']) }}</td>
				                    <td>{{ number_format($row['Time_90']) }}</td> 
				                </tr>
				                @endforeach
				            </tbody>
				        </table>
					</div>
		        </div>
		        <div class="panel">
					<div class="top-bar">
						<div class="top-bar-left">
							User Interactions
						</div>
						<div class="top-bar-right">
							<button class="button">Download</button>
						</div>
					</div>
					<div class="table-scroll">
						<table class="report tiny hover expanded">
				            <thead>
				                <tr>
				                	<th>Article</th>
				                    <th>Comments</th>
				                    <th>Email Shares</th>
				                    <th>Tweets</th>
				                    <th>FB Shares</th>
				                    <th>Total Shares</th>
				                    <th>Share Rate</th>
				                    <th>Related Content Clicks</th>
				                    <th>Click Thru Rate</th>
				                </tr>
				            </thead>
				            <tbody>
				                @foreach ($report as $row)
				                <tr>
				                    <td>{{ $row['Page_Path'] }}</td>
				                   	<td>{{ number_format($row['Comments']) }}</td>
				                   	<td>{{ number_format($row['Emails']) }}</td>
				                   	<td>{{ number_format($row['Tweets']) }}</td>
				                   	<td>{{ number_format($row['Facebook_Recommendations']) }}</td>
				                   	{{--*/ $shareTotal = $row['Emails'] + $row['Tweets'] + $row['Facebook_Recommendations']; /*--}}
				                   	<td>{{ number_format($shareTotal) }}</td>
				                   	<td>{{ $formatter->percent($shareTotal, $row['Pageviews'])}}</td>
				                   	<td>{{ number_format($row['Related_Clicks']) }}</td>
			                   		<td>{{ $formatter->percent($row['Related_Clicks'], $row['Scroll_Supplemental']) }}</td>
				                </tr>
				                @endforeach
				            </tbody>
				        </table>
			        </div>
		        </div>
			</div>
		</div>
	    </form>
    @else
		<div class="small-12 column">
	        <div>Data Users Coming soon.</div>
	    </div>
	@endif
@endsection



@section('script')
	<script>
		DefaultDateRangePickerOptions = {
			presetRanges: [],
			datepickerOptions: {
				numberOfMonths: 1
			}
		};
		$.datepicker.setDefaults({
			onSelect: function(date){
				console.log(date);
			}
		})
	</script>
@endsection