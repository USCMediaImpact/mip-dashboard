@extends('layouts.main')

@section('content')
	@if ($have_data)
		<form id="form_data_quality" method="GET">
		<input type="hidden" name="min_date" value="{{ date('Y-m-d', $min_date) }}" />
		<input type="hidden" name="max_date" value="{{ date('Y-m-d', $max_date) }}" />
		<div class="row">
			<div class="column small-12">
				<div class="top-bar">
					<div class="top-bar-left">
						Weekly Story Performance<br>
						<small>with Data from MIP Google Tag Manager </small>
					</div>
					<div class="top-bar-right">
						@include('widgets.resultGroup')
					</div>
				</div>
				<div class="table-scroll">
				<p>Scroll Depth</p>
					<table class="tiny hover" style="width: 2000px !important;">
			            <thead>
			            	<tr>
			            		<th rowspan="3">Week of</th>
			                    <th rowspan="3">URL</th>
			                    <th rowspan="3">Total Pageviews</th>
			                    <th>Started scrolling</th>
			                    <th>25%</th>
			                    <th>50%</th>
			                    <th>75%</th>
			                    <th>100%</th>
			                    <th>Related content</th>
			                    <th>End of page</th> 
			                </tr>
			            </thead>
			            <tbody>
			                @foreach ($report as $row)
			                <tr>
			                    <td>{{ date('Y-m-d', strtotime($row['date'])) }}</td>
			                    <td>{{ $row['events'] }}</td>
			                    <td>{{ $row['page_path'] }}</td>
			                    <td>{{ number_format($row['pageviews']) }}</td>
								{{-- scroll depth --}}
								<td>{{ number_format($row['scroll_start']) }}</td>
								<td>{{ number_format($row['scroll_25']) }}</td>
								<td>{{ number_format($row['scroll_50']) }}</td>
								<td>{{ number_format($row['scroll_75']) }}</td>
								<td>{{ number_format($row['scroll_100']) }}</td>
								<td>{{ number_format($row['scroll_supplemental']) }}</td>
								<td>{{ number_format($row['scroll_end']) }}</td>
			                </tr>
			                @endforeach
			            </tbody>
			        </table>
				</div>
				<div class="table-scroll">
				<p>Time on Article</p>
					<table class="tiny hover" style="width: 2000px !important;">
			            <thead>
			            	<tr>
			            		<th rowspan="3">Week of</th>
			                    <th rowspan="3">Article</th>
			                    <th rowspan="3">Total Page Views</th>
								<th>15 seconds</th>
			                    <th>30 seconds</th>
			                    <th>45 seconds</th>
			                    <th>60 seconds</th>
			                    <th>75 seconds</th>
			                    <th>90 seconds</th>
			                </tr>
			            </thead>
			            <tbody>
			                @foreach ($report as $row)
			                <tr>
			                    <td>{{ date('Y-m-d', strtotime($row['date'])) }}</td>
			                    <td>{{ $row['events'] }}</td>
			                    <td>{{ $row['page_path'] }}</td>
			                    <td>{{ number_format($row['pageviews']) }}</td>
								
								{{-- time on article --}}
								<td>{{ number_format($row['time_15']) }}</td>
								<td>{{ number_format($row['time_30']) }}</td>
								<td>{{ number_format($row['time_45']) }}</td>
								<td>{{ number_format($row['time_60']) }}</td>
								<td>{{ number_format($row['time_75']) }}</td>
								<td>{{ number_format($row['time_90']) }}</td>
								
			                </tr>
			                @endforeach
			            </tbody>
			        </table>
				</div>
				<div class="table-scroll"> 
				<p>User Interactions</p>
					<table class="tiny hover" style="width: 2000px !important;">
			            <thead>
			            	<tr>
			            		<th rowspan="3">Week of</th>
			                    <th rowspan="3">URL</th>
			                    <th rowspan="3">Total Pageviews</th>
			                    <th>Comments</th>
			                    <th>Email Shares</th>
			                    <th>Tweets</th>
			                    <th>Facebook Shares</th>
			                    <th>Total Shares</th>
			                    <th>Share Rate</th>
			                    <th>Related Content Clicks</th>
			                    <th>Click Rate</th>
			                </tr>
			            </thead>
			            <tbody>
			                @foreach ($report as $row)
			                <tr>
			                    <td>{{ date('Y-m-d', strtotime($row['date'])) }}</td>
			                    <td>{{ $row['events'] }}</td>
			                    <td>{{ $row['page_path'] }}</td>
			                    <td>{{ number_format($row['pageviews']) }}</td>
								{{-- user interactions--}}
								<td>{{ number_format($row['comments']) }}</td>
								<td>{{ number_format($row['emails']) }}</td>
								<td>{{ number_format($row['tweets']) }}</td>
								<td>{{ number_format($row['facebook_recommendations']) }}</td>
			                    <td>{{ number_format($row['emails'] + $row['tweets'] + $row['facebook_recommendations']) }}</td>
			                    <td>{{ number_format(($row['emails'] + $row['tweets'] + $row['facebook_recommendations']) / $row['pageviews'], 2) }}</td>
			                    <td>{{ number_format($row['related_clicks']) }}</td>
			                    <td>{{ $row['scroll_supplemental'] ? number_format($row['related_clicks'] / $row['scroll_supplemental'], 2) : 0 }}</td>
			                </tr>
			                @endforeach
			            </tbody>
			        </table>
				</div>
				
			</div>
		</div>
		
	    </form>
    @else
		<div class="small-12 column">
	        <div>Data Quality Coming soon.</div>
	    </div>
	@endif

@endsection

@section('script')
    <script>
		$(function(){
			var min_date = $('[name="min_date"]').val(),
				max_date = $('[name="max_date"]').val();
			if(min_date && max_date){
				$('#dateRange').daterangepicker("setRange", {
					start: moment(min_date).toDate(),
					end: moment(max_date).toDate()
				});
			}
			$(document).on('dateChange.mip-dashboard', function(event, beginDate, endDate){
				console.log(beginDate, endDate);
				$('[name="min_date"]').val(beginDate.format('YYYY-MM-DD'));
				$('[name="max_date"]').val(endDate.format('YYYY-MM-DD'));
				$('#form_data_quality')[0].submit();
			});

			$(document).on('change', '#resultGroup', function (evt) {
			  	$('#form_data_quality')[0].submit();
			});
		})
    </script>
@endsection