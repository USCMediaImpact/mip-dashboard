@inject('formatter', 'App\Helpers\FormatterHelper')
@extends('layouts.main')

@section('content')
	@if ($have_data)
		<form id="form_data_quality" method="POST">
		<?php echo csrf_field(); ?>
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
							GA vs GTM 
						</div>
						<div class="top-bar-right">
							<button class="button">Download</button>
						</div>
					</div>
					<table class="report tiny hover">
			            <thead>
			                <tr>
			                	<th>Week</th>
			                    <th>Events</th>
			                    <th>SCPR GA  Users</th>
			                    <th>MIP KPCC GTM Users</th>
			   					<th>Variance</th>
			                </tr>
			            </thead>
			            <tbody>
			                @foreach ($report as $row)
			                <tr>
			                    <td>{{ $formatter->date($row['date']) }}</td>
			                   	<td></td>
			                   	<td>{{ number_format($row['GA_Users']) }}</td>
			                   	<td>{{ number_format($row['MIP_Users']) }}</td>
								<td>{{ $formatter->percent($row['MIP_Users'] - $row['GA_Users'], $row['GA_Users']) }}</td>
			                </tr>
			                @endforeach
			            </tbody>
			        </table>
		        </div>
		        <div class="panel">
					<div class="top-bar">
						<div class="top-bar-left">
							Email Subscribers
						</div>
						<div class="top-bar-right">
							<button class="button">Download</button>
						</div>
					</div>
					<table class="report tiny hover">
			            <thead>
			                <tr>
			                	<th>Week</th>
			                    <th>Identified: Subscribers already in MIP database who came to the site this week</th>
			                    <th>Known: Subscribers already in MIP database who came to the site this week</th>
			                    <th>Identified: Subscribers who came to the site through an e-mail this week for the first time since MIP started collecting data</th>
			                    <th>Known: Subscribers who came to the site through an e-mail this week for the first time since MIP started collecting data</th>
			                    <th>  Identified:  New subscribers this week who also clicked on an e-mail this week   </th>
			                    <th>  Known:  New subscribers this week who also clicked on an e-mail this week   </th>
			                    <th>  Known:  New subscribers this week who also clicked on an e-mail this week   </th>
			                    <th>  Identified e-mail newsletter subscribers THIS WEEK  </th>
			                    <th>  Known e-mail newsletter subscribers This Week </th>
			                    <th> Identified:  New e-mail subscribers this week  </th>
			                    <th>  Known:  New e-mail subscribers this week  </th>
			                    <th>  Identified: Total identified e-mail newsletter subscribers in the MIP database  </th>
			                    <th>  Known: Total number of known e-mail newsletter subscribers in the MIP database  </th>
			                    <th>Known:  Percent of subscribers in the MIP database who clicked on an e-mail this week</th>
			                </tr>
			            </thead>
			            <tbody>
			                @foreach ($report as $row)
			                <tr>
			                    <td>{{ $formatter->date($row['date']) }}</td>
			                    <td>{{ number_format($row['Came_To_Site_Subscribed_And_Came_Through_Email']) }}</td>
			                    <td>{{ number_format($row['Came_To_Site_Came_Through_Email_For_First_Time']) }}</td>
			                    <td>{{ number_format($row['Came_To_Site_Came_Through_Email_Again']) }}</td>
			                    <td>{{ number_format($row['Came_To_Site_Total_Came_To_Site_Through_Email']) }}</td> 
			                    <td>{{ number_format($row['New_Subscribers_Subscribed_And_Came_Through_Email']) }}</td>
			                    <td>{{ number_format($row['New_Subscribers_Subscribed_Only']) }}</td>
			                    <td>{{ number_format($row['New_Subscribers_KPI_New_Email_Subscribers']) }}</td>
			                    <td>{{ number_format($row['Known_To_MIP_New_Email_Subscribers']) }}</td> 
			                    <td>{{ number_format($row['Known_To_MIP_Came_Through_Email_For_First_Time']) }}</td>
			                    <td>{{ number_format($row['Known_To_MIP_Came_Through_Email_Again']) }}</td>
			                    <td>{{ number_format($row['Known_To_MIP_Subscribers_Who_Did_Not_Come_Through_Email']) }}</td>
			                    <td>{{ number_format($row['Known_To_MIP_KPI_Total_Email_Subscribers_Known_To_MIP']) }}</td> 
			                    <td>{{ number_format($row['Known_To_MIP_KPI_Percent_Known_Subs_Who_Came']) }}</td> 
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
			                    <th> Identified: Donors already in MIP database who came to the site this week </th>
			                    <th> Known: Donors already in MIP database who came to the site this week </th>
			                    <th> Identified: Users who donated on the site for the first time since MIP started collecting data  </th>
			                    <th> Known: Users who donated on the site for the first time since MIP started collecting data  </th>
			                    <th> Identified donors on the site THIS WEEK </th>
			                    <th> Known donors on the site This Week </th>
			                    <th> Identified: Total identified donors in the MIP database </th>
			                    <th> Known: Total known donors in the MIP database </th>
			                    <th>Known:  Percent of subscribers in the MIP database who clicked on an e-mail this week</th>
			                </tr>
			            </thead>
			            <tbody>
			                @foreach ($report as $row)
			                <tr>
			                    <td>{{ $formatter->date($row['date']) }}</td>
			                   	<td>{{ number_format($row['Donated_This_Week_New_Donors']) }}</td>
			                   	<td>{{ number_format($row['Donated_This_Week_Donated_Again']) }}</td>
			                   	<td>{{ number_format($row['Donated_This_Week_Total_Donors_This_Week']) }}</td>
			                   	<td>{{ number_format($row['Known_To_MIP_New_Donors']) }}</td>
			                   	<td>{{ number_format($row['Known_To_MIP_Donated_Again']) }}</td>
			                   	<td>{{ number_format($row['Known_To_MIP_Database_Donors_Who_Did_Not_Donate_This_Week']) }}</td>
			                   	<td>{{ number_format($row['Known_To_MIP_KPI_Total_Donors_Known_To_MIP']) }}</td>
			                   	<td>{{ number_format($row['Known_To_MIP_KPI_Percent_Known_Donors_Who_Donated']) }}</td>
			                   	<td>{{ number_format($row['Known_To_MIP_KPI_Percent_Known_Donors_Who_Donated']) }}</td>
			                </tr>
			                @endforeach
			            </tbody>
			        </table>
		        </div>
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
			                    <th>How many known individuals came to the site THIS WEEK? </th>
			                    <th>How many known individuals are in the MIP database?</th>
			                    <th> Percentage of Known that Visited This Week </th>
			                </tr>
			            </thead>
			            <tbody>
			                @foreach ($report as $row)
			                <tr>
			                    <td>{{ $formatter->date($row['date']) }}</td>
			                   	<td>{{ number_format($row['Email_Newsletter_Clicks']) }}</td>
			                   	<td>{{ number_format($row['Totol_Donors_This_Week']) }}</td>
			                   	<td>{{ $formatter->percent($row['Totol_Donors_This_Week'], $row['Email_Newsletter_Clicks'])}}</td>
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