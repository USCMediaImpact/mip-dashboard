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
				<br />
				<div class="table-scroll">
					<table class="tiny hover">
						<colgroup>
							<col style="width: 120px" />
							<col style="width: 120px" />
							<col style="width: 120px" />
							<col style="width: 120px" />
						</colgroup>
			            <thead>
			            	<tr>
			            		<th rowspan="4">week of</th>
			                    <th colspan="4">TOTAL KNOWN USERS</th>
			            	</tr>
			                <tr>
			                    <th title="How many e-mail subscribers or Donors came to the site this week?"> Email Subscribers or Donors on Site </th>
			                    <th title="How many e-mail newsletter subscribers are now known to MIP as of this week?"> Email Subscribers or Donors in MIP Database </th>
			                    <th> % of Loyal Users on Site </th>
			   
			                </tr>
			            </thead>
			            <tbody>
			                @foreach ($report as $row)
			                <tr>
			                    <td>{{ date('Y-m-d', strtotime($row['date'])) }}</td>
			          
			                   	{{-- total known users --}}
			                   	<td>{{ number_format($row['unduplicated_totaluserskpi']) }}</td>
			                   	<td>{{ number_format($row['duplicated_database_memberspluscamethroughemailplusdonors']) }}</td>
			                    <td>{{ number_format($row['unduplicated_database_totaluserskpi']) }}</td>
			                </tr>
			                @endforeach
			            </tbody>
			        </table>
				</div>
				<div class="table-scroll">
					<table class="tiny hover">
						<colgroup>
							<col style="width: 120px" />
							<col style="width: 120px" />
							<col style="width: 120px" />
							<col style="width: 120px" />
							<col style="width: 120px" />
						</colgroup>
			            <thead>
			            	<tr>
			            		<th rowspan="2">week of</th>
			                    <th colspan="4">E-MAIL NEWSLETTER SUBSCRIBERS</th>
			            	</tr>
			                <tr>
			                    <th title="How many e-mail subscribers came to the site this week?"> Email Subscribers on Site </th>
			                    <th title="How many e-mail newsletter subscribers are now known to MIP as of this week?"> Total Email subscribers in MIP DB as of this week</th>
			                    <th> % of Email Subscribers in MIP DB on Site </th>
			                    <th title="How many new subscribers subscribed to an e-mail newsletter this week?"> New Email Subscribers this week </th>
			                    
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
			                </tr>
			                @endforeach
			            </tbody>
			        </table>
				</div>
				<div class="table-scroll">
					<table class="tiny hover">
						<colgroup>
							<col style="width: 120px" />
							<col style="width: 120px" />
							<col style="width: 120px" />
							<col style="width: 120px" />
						</colgroup>
			            <thead>
			            	<tr>
			            		<th rowspan="2">week of</th>
			                    <th colspan="2">DONORS</th>
			            	</tr>
			                <tr>
			                    <th title="How many users donated on the site this week?"> Donors on Site </th>
			                    <th title="How many donors are now known to MIP as of this week?"> Donors in MIP Database </th>
			                     <th title="What percentage of Donors are now known to MIP as of this week?"> % of Donors in MIP DB </th>
			                </tr>
			            </thead>
			            <tbody>
			                @foreach ($report as $row)
			                <tr>
			                    <td>{{ date('Y-m-d', strtotime($row['date'])) }}</td>
			                   	{{-- donors --}}
			                   	<td>{{ number_format($row['totaldonorsthisweek']) }}</td>
			                   	<td>{{ number_format($row['kpi_totaldonorsknowntomip']) }}</td>
			                   	<td>{{ number_format($row['totaldonorsthisweek'] / $row['kpi_totaldonorsknowntomip'],2) }}</td>
			                   	
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
	        <div>Data Users Coming soon.</div>
	    </div>
	@endif
@endsection