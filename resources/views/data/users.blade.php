@extends('layouts.main')

@section('content')
	@if ($have_data)
		<form id="form_data_quality" method="GET">
		
		<div class="row expanded">
			<div class="column small-9">
				<h4 class="title">Email Subscriber and Donor User Summary</h4>
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
							Total Known Users
						</div>
						<div class="top-bar-right">
							<button class="button">Download</button>
						</div>
					</div>
					<table class="report tiny hover">
			            <thead>
			                <tr>
			                	<th>Week</th>
			                    <th>Email Subscribers and Donors on Site</th>
			                    <th>Email Subscribers or Donors on Site</th>
			                    <th>Email Subscribers and Donors in MIP DB</th>
			   					<th>Email Subscribers or Donors in MIP DB</th>
			   					<th>% of Loyal Users on Site</th>
			                </tr>
			            </thead>
			            <tbody>
			                @foreach ($report as $row)
			                <tr>
			                    <td>{{ date('m/d/y', strtotime($row['date'])) }}</td>
			          
			                   	{{-- total known users --}}
			                   	<td>{{ number_format($row['unduplicated_totaluserskpi']) }}</td>
			                   	<td>{{ number_format($row['duplicated_database_memberspluscamethroughemailplusdonors']) }}</td>
			                    <td>{{ number_format($row['unduplicated_database_totaluserskpi']) }}</td>
			                </tr>
			                @endforeach
			            </tbody>
			        </table>
		        </div>
		        <div class="panel">
					<div class="top-bar">
						<div class="top-bar-left">
							Email Newsletter Subscribers
						</div>
						<div class="top-bar-right">
							<button class="button">Download</button>
						</div>
					</div>
					<table class="report tiny hover">
			            <thead>
			                <tr>
			                	<th>Week</th>
			                    <th>Email Subscribers on Site</th>
			                    <th>Total Email Subscribers in MIP DB</th>
			                    <th>% of Email Subscribers in MIP DB on Site</th>
			                    <th>New Email Subscribers</th>
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
		        <div class="panel">
					<div class="top-bar">
						<div class="top-bar-left">
							Donors
						</div>
						<div class="top-bar-right">
							<button class="button">Download</button>
						</div>
					</div>
					<table class="report tiny hover">
			            <thead>
			                <tr>
			                	<th>Week</th>
			                    <th>Donors Dontating</th>
			                    <th>Donors in MIP DB</th>
			                    <th>% of Donors in MIP DB Donating</th>
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