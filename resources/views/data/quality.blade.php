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
						{{$displayGroupName}} <small>with Data from MailChimp and Eventbrite</small>
					</div>
					<div class="top-bar-right">
						@include('widgets.resultGroup')
					</div>
				</div>
				<table class="tiny hover">
					<tr>
						<th>Week of</th>
						<th rowspan="3">Google Analytics vs MIP GTM</th>
					</tr>
					<tr>
						<th rowspan="3">Users </th>
					</tr>
					<tr>
						<th>SCPR GA  Users</th>
						<th>MIP KPCC GTM Users</th>
						<th>Variance</th>
					</tr>
					@foreach ($report as $row)
					<tr>
						<td>{{ date('Y-m-d', strtotime($row['date'])) }}</td>
						<td>{{ number_format($row['ga_users']) }}</td>
	                    <td>{{ number_format($row['mip_users']) }}</td>
	                    <td>{{ number_format(($row['mip_users'] - $row['ga_users']) / $row['ga_users'], 2) }}</td>
					</tr>
					@endforeach
				</table>

				<div class="table-scroll">
					<table class="tiny hover" style="width: 2000px !important;">
			            <thead>
			            	<tr>
			            		<th rowspan="4">Date</th>
			                    <th colspan="13">EMAIL NEWSLETTER SUBSCRIBERS</th>
			            	</tr>
			            	<tr>
			            		<th colspan="4">How many e-mail subscribers came to the site this week?</th>
			            		<th colspan="3">How many new subscribers subscribed to an e-mail newsletter this week?</th>
			            		<th colspan="5">How many e-mail newsletter subscribers are now known to MIP as of this week?</th>
			            		<th></th>
			            	</tr>
			            	<tr>
			            		<th> Number of users this week who BOTH subscribed to an e-mail newsletter AND came to the site through an e-mail  </th>
			            		<th> Number of users who came to the site through an e-mail this week for the first time since MIP started collecting data  </th>
			            		<th> Number of e-mail subscribers already in MIP database and who came to the site this week </th>
			            		<th> Total number of users who came to the site through an email this week </th>
			            		<th> Number of users this week who BOTH subscribed to an e-mail newsletter AND came to the site through an e-mail  </th>
			            		<th> Number of users this week who subscribed to an e-mail newsletter  </th>
			            		<th> KPI: Number of new subscribers this week </th>
			            		<th> Number of new subscribers this week </th>
			            		<th> Number of users who came to the site through an e-mail this week for the first time since MIP started collecting data  </th>
			            		<th> Number of e-mail subscribers already in MIP database and who came to the site this week </th>
			            		<th> Number of e-mail subscribers already in MIP database and who came to the site this week </th>
			            		<th> KPI: Total number of e-mail subscribers known to MIP </th>
			            		<th> KPI: Percent of e-mail subscribers known to MIP who came to the site through an e-mail this week </th>
			            	</tr>
			            </thead>
			            <tbody>
			                @foreach ($report as $row)
			                <tr>
			                    <td>{{ date('Y-m-d', strtotime($row['date'])) }}</td>
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
			</div>
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