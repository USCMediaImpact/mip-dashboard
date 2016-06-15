@extends('layouts.main')

@section('content')
	@if ($have_data)
		<form id="form_data_quality" method="GET">
		<input type="hidden" name="min_date" value="{{ date('Y-m-d', $min_date) }}" />
		<input type="hidden" name="max_date" value="{{ date('Y-m-d', $max_date) }}" />
		<div class="table-scroll" style="margin: 20px 10px;">
	        <table class="tiny hover" style="width: 2000px !important;">
	            <thead>
	            	<tr>
	            		<th rowspan="2">Date</th>
	                    <th rowspan="2">Events</th>
	                    <th rowspan="2">SCPR GA Users</th>
	                    <th rowspan="2">MIP KPCC GTM Users</th>
	                    <th rowspan="2">Variance</th>
	            		<th colspan="4">E-mail newsletter subscribers</th>
	            		<th colspan="3">E-mail newsletter clicks</th>
	            		<th colspan="5">Donors</th>
	            		<th rowspan="2">Total number of unique known e-mail addresses in the MIP database</th>
	            	</tr>
	                <tr>
	                    <th>Identified e-mail newsletter subscribers</th>
	                    <th>Known e-mail newsletter subscribers</th>
	                    <th>Total number of known e-mail newsletter subscribers in the MIP database</th>
	                    <th>New identified e-mail newsletter subscribers</th>
	                    <th>E-mail newsletter clicks</th>
	                    <th>Eloqua e-mail newsletter clicks</th>
	                    <th>Variance</th>
	                    <th>Identified donors</th>
	                    <th>Known donors</th>
	                    <th>Eloqua known donors</th>
	                    <th>Variance</th>
	                    <th>Total number of known donors in the MIP database</th>
	                </tr>
	            </thead>
	            <tbody>
	                @foreach ($report as $row)
	                <tr>
	                    <td>{{ date('Y-m-d', strtotime($row['date'])) }}</td>
	                    <td>{{ $row['events'] }}</td>
	                    <td>{{ number_format($row['ga_users']) }}</td>
	                    <td>{{ number_format($row['mip_users']) }}</td>
	                    <td>{{ number_format(($row['mip_users'] - $row['ga_users']) / $row['ga_users'], 2) }}</td>
	                    <td>{{ number_format($row['identified_emailsubscribers']) }}</td>
	                    <td>{{ $row['known_emailsubscribers'] }}</td>
	                    <td>{{ $row['total_database_emails'] }}</td>
	                    <td>{{ $row['identified_newemailsubscribers'] }}</td>
	                    <td>{{ $row['email_newsletter_clicks'] }}</td>
	                    <td>{{ $row['eloqua_email_newsletter_clicks'] }}</td>
	                    <td>{{ $row['email_newsletter_clicks_variance'] }}</td>
	                    <td>{{ $row['identified_donors'] }}</td>
	                    <td>{{ $row['known_donors'] }}</td>
	                    <td>{{ $row['eloqua_known_donors'] }}</td>
	                    <td>{{ $row['donors_variance'] }}</td>
	                    <td>{{ $row['total_known_donors'] }}</td>
	                    <td>{{ $row['total_known__unique_email'] }}</td>
	                </tr>
	                @endforeach
	            </tbody>
	        </table>
	    </div>
	    </form>
    @else
		<div class="small-12 column">
	        <div>Data Quality Comming soon.</div>
	    </div>
	@endif

@endsection

@section('script')
    <script>
		$(function(){
			var min_date = $('[name="min_date"]').val(),
				max_date = $('[name="max_date"]').val();
			console.log(min_date, max_date);
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
		})
    </script>
@endsection