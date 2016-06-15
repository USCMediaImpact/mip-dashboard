@extends('layouts.main')

@section('content')

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
@endsection