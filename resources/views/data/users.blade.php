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
				<div class="table-scroll">
					<table class="tiny hover" style="width: 2000px !important;">
			            <thead>
			            	<tr>
			            		<th rowspan="2">week of</th>
			                    <th colspan="4">E-MAIL NEWSLETTER SUBSCRIBERS</th>
			                    <th colspan="4">WEBSITE MEBERS THAT LOGGED IN</th>
			                    <th colspan="2">DONORS</th>
			                    <th colspan="4">TOTAL KNOWN USERS</th>
			            	</tr>
			                <tr>
			                    <th title="How many e-mail subscribers came to the site this week?"> Total number of users who came to the site through an email this week </th>
			                    <th title="How many e-mail newsletter subscribers are now known to MIP as of this week?"> KPI: Total number of e-mail subscribers known to MIP </th>
			                    <th> KPI: Percent of e-mail subscribers known to MIP who came to the site through an e-mail this week </th>
			                    <th title="How many new subscribers subscribed to an e-mail newsletter this week?"> KPI: Number of new subscribers this week </th>
			                    <th title="How many texas tribune website members logged into the site this week?"> Total number of users that logged in this week </th>
			                    <th title="how many website members are now known to MIP as of this week?"> KPI: Total number of TT usernames known to MIP </th>
			                    <th> KPI: Percent of site logged in members known to MIP this week </th>
			                    <th title="How many new users logged in to Texas Tribune this week?"> KPI: Number of new logged in members </th>
			                    <th title="How many users donated on the site this week?"> Total number of users who donated on the site this week </th>
			                    <th title="How many donors are now known to MIP as of this week?"> KPI: Total number of donors known to MIP </th>
			                    <th title=""> Duplicated: Number of e-mail subscribers who came to the site this week, PLUS the number of donors who donated this week, Plus the number of users that logged in this week </th>
			                    <th title="KPI:  How many people engaged with Texas Tribune on the site this week through an e-mail newsletter and/or a donation?"> KPI:  Total number of unduplicated people THIS WEEK:  e-mail subscriber only, donor only, e-mail subscriber and donor </th>
			                    <th> Duplicated: Number of e-mail subscribers in the MIP database as of this week, PLUS the number of donors in the MIP database </th>
			                    <th title="KPI:  How many people does MIP now have in its database as of this week?"> KPI:  Total number of unduplicated people:  e-mail subscriber only, donor only, e-mail subscriber and donor </th>
			                </tr>
			            </thead>
			            <tbody>
			                @foreach ($report as $row)
			                <tr>
			                    <td>{{ date('Y-m-d', strtotime($row['date'])) }}</td>
			                    {{-- newsletter --}}
			                    <td>{{ number_format($row['cametositethroughemail']) }}</td>
			                    <td>{{ number_format($row['kpi_totalemailsubscribersknowntomip']) }}</td>
			                    <td>{{ number_format($row['kpi_percentknownsubswhocame'], 2) }}</td>
			                    <td>{{ number_format($row['kpi_newemailsubscribers']) }}</td>
			                    {{-- member --}}
			                   	<td>{{ number_format($row['totalmembersthisweek']) }}</td>
			                   	<td>{{ number_format($row['kpi_totalmembersknowntomip']) }}</td>
			                   	<td>{{ number_format($row['kpi_totalmembersknowntomip'] / $row['totalmembersthisweek'], 2) }}</td>
			                   	<td></td>
			                   	{{-- donors --}}
			                   	<td>{{ number_format($row['totaldonorsthisweek']) }}</td>
			                   	<td>{{ number_format($row['kpi_totaldonorsknowntomip']) }}</td>
			                   	{{-- total known users --}}
			                   	<td>{{ number_format($row['duplicated_memberspluscamethroughemailplusdonors']) }}</td>
			                   	<td>{{ number_format($row['unduplicated_totaluserskpi']) }}</td>
			                   	<td>{{ number_format($row['duplicated_database_memberspluscamethroughemailplusdonors']) }}</td>
			                    <td>{{ number_format($row['unduplicated_database_totaluserskpi']) }}</td>
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
	        <div>Data Users Comming soon.</div>
	    </div>
	@endif
@endsection