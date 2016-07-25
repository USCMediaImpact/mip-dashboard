@inject('formatter', 'App\Helpers\FormatterHelper')
@extends('layouts.main')

@section('content')
	@if ($have_data)
		<div class="row expanded">
			<div class="column small-9">
				<h4 class="title">Email Subscriber and Donor User Summary</h4>
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
                            @include('widgets.daterange', ['min_date' => $min_date, 'max_date' => $max_date])
							<button class="button small btnDownload" action='/data/users/total_known_users/csv'>Download</button>
						</div>
					</div>
					<table id="dataUsersTotalKnownUsers" class="report tiny hover">
			            <thead>
			                <tr>
			                	<th>Week of</th>
			                    <th>Email Subscribers and Donors and Members on Site</th>
			                    <th>Email Subscribers or Donors or Members on Site or all Three</th>
			                    <th>Email Subscribers and Donors and Members in MIP DB</th>
			   					<th>Email Subscribers or Donors or Members, all pairs, or all three in DB</th>
			   					<th>% of Loyal Users on Site</th>
			                </tr>
			            </thead>
			            <tbody>
			                
			            </tbody>
			        </table>
		        </div>
		        <div class="panel">
					<div class="top-bar">
						<div class="top-bar-left">
							Email Newsletter Subscribers
						</div>
						<div class="top-bar-right">
                            @include('widgets.daterange', ['min_date' => $min_date, 'max_date' => $max_date])
							<button class="button small btnDownload" action="/data/users/email_newsletter_subscribers/csv">Download</button>
						</div>
					</div>
					<table id="dataUsersEmailNewsletterSubscribers" class="report tiny hover">
			            <thead>
			                <tr>
			                	<th>Week of</th>
			                    <th>(MailChimp) Email Subscribers on Site</th>
			                    <th>Total Email Subscribers in MIP DB</th>
			                    <th>% of Email Subscribers in MIP DB on Site</th>
			                    <th>New Email Subscribers</th>
			                </tr>
			            </thead>
			            <tbody>
			            </tbody>
			        </table>
		        </div>
		        <div class="panel">
					<div class="top-bar">
						<div class="top-bar-left">
							Donors
						</div>
						<div class="top-bar-right">
                            @include('widgets.daterange', ['min_date' => $min_date, 'max_date' => $max_date])
							<button class="button small btnDownload" action="/data/users/donors/csv">Download</button>
						</div>
					</div>
					<table id="dataUsersDonors" class="report tiny hover">
			            <thead>
			                <tr>
			                	<th>Week of</th>
			                    <th>Donors Donating</th>
			                    <th>Donors in MIP DB</th>
			                    <th>% of Donors in MIP DB Donating</th>
			                </tr>
			            </thead>
			            <tbody>
			                
			            </tbody>
			        </table>
		        </div>
                <div class="panel">
                    <div class="top-bar">
                        <div class="top-bar-left">
                            Members
                        </div>
                        <div class="top-bar-right">
                            @include('widgets.daterange', ['min_date' => $min_date, 'max_date' => $max_date])
                            <button class="button small btnDownload" action="/data/users/donors/csv">Download</button>
                        </div>
                    </div>
                    <table id="dataUsersMembers" class="report tiny hover">
                        <thead>
                            <tr>
                                <th>Week of</th>
                                <th>Known members on the site THIS WEEK</th>
                                <th>Known: Total known members in the MIP database</th>
                                <th>Known: Percent of members in the MIP database who logged in this week</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
			</div>
		</div>
    @else
		<div class="small-12 column">
	        <div>Data Users Coming soon.</div>
	    </div>
	@endif
@endsection

@section('script')
<script>
    DefaultDateRangePickerOptions = {
        datepickerOptions: {
            minDate: moment('{{$date_range_min}}').toDate(),
            maxDate: moment('{{$date_range_max}}').toDate()
        }
    };
    ReportDataTable = {};
	$(function(){
		ReportDataTable['dataUsersTotalKnownUsers'] = $('#dataUsersTotalKnownUsers').DataTable({
            'processing': true,
            'serverSide': true,
            'searching': false,
            "order": [[ 0, "desc" ]],
            'ajax': {
	            'url': '/data/users/total_known_users',
	            'type': 'POST',
	            'data': function(data){
                    var panel = $('#dataUsersTotalKnownUsers').parents('.panel');
	            	return $.extend({
	            		'min_date': $('[name="min_date"]', panel).val(),
						'max_date': $('[name="max_date"]', panel).val(),
	            	}, data);
	            }
	        },
            'dom': 'Bfrtip',
            'columns': [{
                'data': 'date'
            }, {
                'data': 'Duplicated_MembersPlusCameThroughEmailPlusDonors'
            }, {
                'data': 'Unduplicated_TotalUsersKPI'
            }, {
                'data': 'Duplicated_Database_MembersPlusCameThroughEmailPlusDonors'
            }, {
                'data': 'Unduplicated_Database_TotalUsersKPI'
            }, {
                'data': 'Loyal_Users_On_Site'
            }],
            'columnDefs': [{
                'targets': 0,
                'render': function (data, type, row) {
                    return moment(data).format('MM/DD/YY')
                }
            }, {
                'targets': 1,
                'render': function (data, type, row) {
                    return new Intl.NumberFormat().format(data)
                }
            }, {
                'targets': 2,
                'render': function (data, type, row) {
                    return new Intl.NumberFormat().format(data)
                }
            }, {
                'targets': 3,
                'render': function (data, type, row) {
                    return new Intl.NumberFormat().format(data)
                }
            }, {
                'targets': 4,
                'render': function (data, type, row) {
                    return new Intl.NumberFormat().format(data)
                }
            }, {
                'targets': 5,
                'render': function (data, type, row) {
                	return new Intl.NumberFormat('en-US', {style: 'percent', minimumFractionDigits: 0}).format(data);
                }
            } ]
        });
        ReportDataTable['dataUsersEmailNewsletterSubscribers'] = $('#dataUsersEmailNewsletterSubscribers').DataTable({
            'processing': true,
            'serverSide': true,
            'searching': false,
            "order": [[ 0, "desc" ]],
            'ajax': {
	            'url': '/data/users/email_newsletter_subscribers',
	            'type': 'POST',
	            'data': function(data){
                    var panel = $('#dataUsersEmailNewsletterSubscribers').parents('.panel');
	            	return $.extend({
	            		'min_date': $('[name="min_date"]', panel).val(),
						'max_date': $('[name="max_date"]', panel).val(),
	            	}, data);
	            }
	        },
            'dom': 'Bfrtip',
            'columns': [{
                'data': 'date'
            }, {
                'data': 'CameToSiteThroughEmail'
            }, {
                'data': 'KPI_TotalEmailSubscribersKnownToMIP'
            }, {
                'data': 'KPI_PercentKnownSubsWhoCame'
            }, {
                'data': 'KPI_NewEmailSubscribers'
            }],
            'columnDefs': [{
                'targets': 0,
                'render': function (data, type, row) {
                    return moment(data).format('MM/DD/YY')
                }
            }, {
                'targets': 1,
                'render': function (data, type, row) {
                    return new Intl.NumberFormat().format(data)
                }
            }, {
                'targets': 2,
                'render': function (data, type, row) {
                    return new Intl.NumberFormat().format(data)
                }
            }, {
                'targets': 3,
                'render': function (data, type, row) {
                    return new Intl.NumberFormat('en-US', {style: 'percent', minimumFractionDigits: 0}).format(data);
                }
            }, {
                'targets': 4,
                'render': function (data, type, row) {
                    return new Intl.NumberFormat().format(data)
                }
            }]
        });
        ReportDataTable['dataUsersDonors'] = $('#dataUsersDonors').DataTable({
            'processing': true,
            'serverSide': true,
            'searching': false,
            "order": [[ 0, "desc" ]],
            'ajax': {
	            'url': '/data/users/donors',
	            'type': 'POST',
	            'data': function(data){
                    var panel = $('#dataUsersDonors').parents('.panel');
	            	return $.extend({
	            		'min_date': $('[name="min_date"]', panel).val(),
						'max_date': $('[name="max_date"]', panel).val(),
	            	}, data);
	            }
	        },
            'dom': 'Bfrtip',
            'columns': [{
                'data': 'date'
            }, {
                'data': 'TotalDonorsThisWeek'
            }, {
                'data': 'KPI_TotalDonorsKnownToMIP'
            }, {
                'data': 'Donors_In_MIP'
            }],
            'columnDefs': [{
                'targets': 0,
                'render': function (data, type, row) {
                    return moment(data).format('MM/DD/YY')
                }
            }, {
                'targets': 1,
                'render': function (data, type, row) {
                    return new Intl.NumberFormat().format(data)
                }
            }, {
                'targets': 2,
                'render': function (data, type, row) {
                    return new Intl.NumberFormat().format(data)
                }
            }, {
                'targets': 3,
                'render': function (data, type, row) {
                    return new Intl.NumberFormat('en-US', {style: 'percent', minimumFractionDigits: 0}).format(data);
                }
            }]
        });
        ReportDataTable['dataUsersMembers'] = $('#dataUsersMembers').DataTable({
            'processing': true,
            'serverSide': true,
            'searching': false,
            "order": [[ 0, "desc" ]],
            'ajax': {
                'url': '/data/users/members',
                'type': 'POST',
                'data': function(data){
                    var panel = $('#dataUsersDonors').parents('.panel');
                    return $.extend({
                        'min_date': $('[name="min_date"]', panel).val(),
                        'max_date': $('[name="max_date"]', panel).val(),
                    }, data);
                }
            },
            'dom': 'Bfrtip',
            'columns': [{
                'data': 'date'
            }, {
                'data': 'TotalMembersThisWeek'
            }, {
                'data': 'KPI_TotalMembersKnownToMIP'
            }, {
                'data': 'Members_In_MIP'
            }],
            'columnDefs': [{
                'targets': 0,
                'render': function (data, type, row) {
                    return moment(data).format('MM/DD/YY')
                }
            }, {
                'targets': 1,
                'render': function (data, type, row) {
                    return new Intl.NumberFormat().format(data)
                }
            }, {
                'targets': 2,
                'render': function (data, type, row) {
                    return new Intl.NumberFormat().format(data)
                }
            }, {
                'targets': 3,
                'render': function (data, type, row) {
                    return new Intl.NumberFormat('en-US', {style: 'percent', minimumFractionDigits: 0}).format(data);
                }
            }]
        });
	});
</script>
@endsection