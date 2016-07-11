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
							Total Known Users
						</div>
						<div class="top-bar-right">
							<button class="button">Download</button>
						</div>
					</div>
					<table id="dataUsersTotalKnownUsers" class="report tiny hover">
			            <thead>
			                <tr>
			                	<th>Week</th>
			                    <th data-tooltip aria-haspopup="true" class="has-tip top" title="How many e-mail subscribers came to the site this week?">Email Subscribers and Donors on Site</th>
			                    <th>Email Subscribers or Donors on Site</th>
			                    <th>Email Subscribers and Donors in MIP DB</th>
			   					<th>Email Subscribers or Donors in MIP DB</th>
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
							<button class="button">Download</button>
						</div>
					</div>
					<table id="dataUsersEmailNewsletterSubscribers" class="report tiny hover">
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
					<table id="dataUsersDonors" class="report tiny hover">
			            <thead>
			                <tr>
			                	<th>Week</th>
			                    <th>Donors Dontating</th>
			                    <th>Donors in MIP DB</th>
			                    <th>% of Donors in MIP DB Donating</th>
			                </tr>
			            </thead>
			            <tbody>
			                
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

@section('script')
<script>
	$(function(){
		var dataTable = [];
		dataTable[0] = $('#dataUsersTotalKnownUsers').DataTable({
            'processing': true,
            'serverSide': true,
            'searching': false,
            'ajax': {
	            'url': '/data/users/total_known_users',
	            'type': 'POST',
	            'data': function(data){
	            	console.log(data);
	            	return $.extend({
	            		'min_date': $('[name="min_date"]').val(),
						'max_date': $('[name="max_date"]').val(),
	            	}, data);
	            }
	        },
            'dom': 'Bfrtip',
            'columns': [{
                'data': 'date'
            }, {
                'data': 'TotalMembersThisWeek'
            }, {
                'data': 'TotalMembersThisWeek'
            }, {
                'data': 'KPI_TotalMembersKnownToMIP'
            }, {
                'data': 'KPI_TotalMembersKnownToMIP'
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
                	var value = row.KPI_TotalMembersKnownToMIP / row.TotalMembersThisWeek;
                    return new Intl.NumberFormat('en-US', {style: 'percent', minimumFractionDigits: 2}).format(data);
                }
            } ]
        });
        dataTable[1] = $('#dataUsersEmailNewsletterSubscribers').DataTable({
            'processing': true,
            'serverSide': true,
            'searching': false,
            'ajax': {
	            'url': '/data/users/email_newsletter_subscribers',
	            'type': 'POST',
	            'data': function(data){
	            	console.log(data);
	            	return $.extend({
	            		'min_date': $('[name="min_date"]').val(),
						'max_date': $('[name="max_date"]').val(),
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
                'data': 'NewEmailSubscribers'
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
            }]
        });
        dataTable[2] = $('#dataUsersDonors').DataTable({
            'processing': true,
            'serverSide': true,
            'searching': false,
            'ajax': {
	            'url': '/data/users/donors',
	            'type': 'POST',
	            'data': function(data){
	            	console.log(data);
	            	return $.extend({
	            		'min_date': $('[name="min_date"]').val(),
						'max_date': $('[name="max_date"]').val(),
	            	}, data);
	            }
	        },
            'dom': 'Bfrtip',
            'columns': [{
                'data': 'date'
            }, {
                'data': 'Unduplicated_TotalUsersKPI'
            }, {
                'data': 'Duplicated_Database_CameThroughEmailPlusDonors'
            }, {
                'data': 'Unduplicated_Database_TotalUsersKPI'
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
            }]
        });

        $(document).on('change.daterange', function(){
			$.each(dataTable, function(){
				this.ajax.reload();
			});
        });
	});
</script>
@endsection