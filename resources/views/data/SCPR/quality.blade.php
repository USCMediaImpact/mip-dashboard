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
							GA vs GTM 
						</div>
						<div class="top-bar-right">
                            @include('widgets.daterange', ['min_date' => $min_date, 'max_date' => $max_date])
                            @include('widgets.dropdownbutton', [
                                'text' => 'Download',
                                'class' => 'small download-group',
                                'buttons' => [
                                    [
                                        'text' => 'Download Selected Date',
                                        'class' => 'small btnDownload',
                                        'attr' => [['action', '/data/quality/ga_vs_gtm/csv']],
                                    ], [
                                        'text' => 'Download All Date',
                                        'class' => 'small btnDownload',
                                        'attr' => [['action', '/data/quality/ga_vs_gtm/csv/all']],
                                    ]
                                ]
                            ])
						</div>
					</div>
					<table id="dataQualityGAvsGTM" class="report tiny hover">
			            <thead>
			                <tr>
			                	<th><span data-tooltip aria-haspopup="true" class="has-tip top" title="Seven-day week starting with Sunday">Week of</span></th>
			                    <th><span data-tooltip aria-haspopup="true" class="has-tip top" title="External or internal events or actions that help explain trends">Events</span></th>
			                    <th><span data-tooltip aria-haspopup="true" class="has-tip top" title="Weekly unique users (unique combo of device + browser)">GA Users</span></th>
			                    <th><span data-tooltip aria-haspopup="true" class="has-tip top" title="Weekly unique users (unique combo of device + browser)">MIP GTM Users</span></th>
			   					<th><span data-tooltip aria-haspopup="true" class="has-tip top" title="Percent difference between SCPR GA users and MIP KPCC GTM users">Variance</span></th>
			                </tr>
			            </thead>
			            <tbody>
			            </tbody>
			        </table>
		        </div>
		        <div class="panel">
					<div class="top-bar">
						<div class="top-bar-left">
							Email Subscribers
						</div>
						<div class="top-bar-right">
                            @include('widgets.daterange', ['min_date' => $min_date, 'max_date' => $max_date])
							@include('widgets.dropdownbutton', [
                                'text' => 'Download',
                                'class' => 'small download-group',
                                'buttons' => [
                                    [
                                        'text' => 'Download Selected Date',
                                        'class' => 'small btnDownload',
                                        'attr' => [['action', '/data/quality/email_subscribers/csv']],
                                    ], [
                                        'text' => 'Download All Date',
                                        'class' => 'small btnDownload',
                                        'attr' => [['action', '/data/quality/email_subscribers/csv/all']],
                                    ]
                                ]
                            ])
						</div>
					</div>
					<table id="dataQualityEmailSubscribers" class="report tiny hover">
			            <thead>
			                <tr>
			                	<th>Week of</th>
			                    <th>Identified: Subscribers already in MIP database who came to the site this week</th>
			                    <th>Known: Subscribers already in MIP database who came to the site this week</th>
			                    <th>Identified: Subscribers who came to the site through an e-mail this week for the first time since MIP started collecting data</th>
			                    <th>Known: Subscribers who came to the site through an e-mail this week for the first time since MIP started collecting data</th>
			                    <th>Identified: New subscribers this week who also clicked on an e-mail this week</th>
			                    <th>Known: New subscribers this week who also clicked on an e-mail this week</th>
			                    <th>Identified e-mail newsletter subscribers THIS WEEK</th>
			                    <th>Known e-mail newsletter subscribers THIS WEEK (unique ELQs)</th>
			                    <th>Identified: New e-mail subscribers this week</th>
			                    <th>Known: New e-mail subscribers this week</th>
			                    <th>Identified: Total identified e-mail newsletter subscribers in the MIP database</th>
			                    <th>Known: Total number of known e-mail newsletter subscribers in the MIP database</th>
			                    <th>Known: Percent of subscribers in the MIP database who clicked on an e-mail this week</th>
			                    <th><span data-tooltip aria-haspopup="true" class="has-tip top" title="The number of pageviews with an ELQtrackID= code from unique Google cookie IDs (weekly unique users) that also have an Eloqua ELQ=code">E-mail newsletter clicks per week</span></th>
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
							@include('widgets.dropdownbutton', [
                                'text' => 'Download',
                                'class' => 'small download-group',
                                'buttons' => [
                                    [
                                        'text' => 'Download Selected Date',
                                        'class' => 'small btnDownload',
                                        'attr' => [['action', '/data/quality/donors/csv']],
                                    ], [
                                        'text' => 'Download All Date',
                                        'class' => 'small btnDownload',
                                        'attr' => [['action', '/data/quality/donors/csv/all']],
                                    ]
                                ]
                            ])
						</div>
					</div>
					<table id="dataQualityDonors" class="report tiny hover">
			            <thead>
			                <tr>
			                	<th>Week</th>
			                    <th>Identified: Donors already in MIP database who came to the site this week</th>
			                    <th>Known: Donors already in MIP database who came to the site this week</th>
			                    <th>Identified: Users who donated on the site for the first time since MIP started collecting data</th>
			                    <th>Known: Users who donated on the site for the first time since MIP started collecting data</th>
			                    <th>Identified donors on the site THIS WEEK</th>
			                    <th>Known donors on the site THIS WEEK</th>
			                    <th>Identified: Total identified donors in the MIP database</th>
			                    <th>Known: Total known donors in the MIP database</th>
			                    <th>Known: Percent of subscribers in the MIP database who clicked on an e-mail this week</th>
			                </tr>
			            </thead>
			            <tbody>
			            </tbody>
			        </table>
		        </div>
		        <div class="panel">
					<div class="top-bar">
						<div class="top-bar-left">
							Total Known Users
						</div>
						<div class="top-bar-right">
                            @include('widgets.daterange', ['min_date' => $min_date, 'max_date' => $max_date])
                            @include('widgets.dropdownbutton', [
                                'text' => 'Download',
                                'class' => 'small download-group',
                                'buttons' => [
                                    [
                                        'text' => 'Download Selected Date',
                                        'class' => 'small btnDownload',
                                        'attr' => [['action', '/data/quality/total_known_users/csv']],
                                    ], [
                                        'text' => 'Download All Date',
                                        'class' => 'small btnDownload',
                                        'attr' => [['action', '/data/quality/total_known_users/csv/all']],
                                    ]
                                ]
                            ])
						</div>
					</div>
					<table id="dataQualityTotalKnownUsers" class="report tiny hover">
			            <thead>
			                <tr>
			                	<th>Week of</th>
			                    <th>Known: Total known donors and/or e-mail newsletter subscribers who came to the site THIS WEEK</th>
			                    <th>Known: Total known donors and/or e-mail newsletter subscribers in the MIP database</th>
			                    <th>Known: Percent of known individuals in the MIP database who came to the site this week</th>
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
		ReportDataTable['dataQualityGAvsGTM'] = $('#dataQualityGAvsGTM').DataTable({
            'processing': true,
            'serverSide': true,
            'searching': false,
            "order": [[ 0, "desc" ]],
            'ajax': {
	            'url': '/data/quality/ga_vs_gtm',
	            'type': 'POST',
	            'data': function(data){
	            	var panel = $('#dataQualityGAvsGTM').parents('.panel');
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
                'data': 'Events'
            }, {
                'data': 'GA_Users'
            }, {
                'data': 'MIP_Users'
            }, {
                'data': 'Variance'
            }],
            'columnDefs': [{
                'targets': 0,
                'width': 60,
                'render': function (data, type, row) {
                    return moment(data).format('MM/DD/YY')
                }
            }, {
                'targets': 1,
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
                	return new Intl.NumberFormat('en-US', {style: 'percent', minimumFractionDigits: 0}).format(data);
                }
            } ]
        });
        ReportDataTable['dataQualityEmailSubscribers'] = $('#dataQualityEmailSubscribers').DataTable({
            'processing': true,
            'serverSide': true,
            'searching': false,
            'scrollX': true,
            "order": [[ 0, "desc" ]],
            'ajax': {
	            'url': '/data/quality/email_subscribers',
	            'type': 'POST',
	            'data': function(data){
                    var panel = $('#dataQualityEmailSubscribers').parents('.panel');
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
                'data': 'I_inDatabaseCameToSite'
            }, {
                'data': 'K_inDatabaseCameToSite'
            }, {
                'data': 'I_notInDatabaseCameToSite'
            }, {
                'data': 'K_notInDatabaseCameToSite'
            }, {
                'data': 'I_newSubscriberCameThroughEmail'
            }, {
                'data': 'K_newSubscriberCameThroughEmail'
            }, {
                'data': 'I_SubscribersThisWeek'
            }, {
                'data': 'K_SubscribersThisWeek'
            }, {
                'data': 'I_NewSubscribers'
            }, {
                'data': 'K_NewSubscribers'
            }, {
                'data': 'I_TotalDatabaseSubscribers'
            }, {
                'data': 'K_TotalDatabaseSubscribers'
            }, {
                'data': 'K_PercentDatabaseSubscribersWhoCame'
            }, {
                'data': 'EmailNewsletterClicks'
            }],
            'columnDefs': [{
                'targets': 0,
                'width': 60,
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
                    return new Intl.NumberFormat().format(data)
                }
            }, {
                'targets': 6,
                'render': function (data, type, row) {
                    return new Intl.NumberFormat().format(data)
                }
            }, {
                'targets': 7,
                'render': function (data, type, row) {
                    return new Intl.NumberFormat().format(data)
                }
            }, {
                'targets': 8,
                'render': function (data, type, row) {
                    return new Intl.NumberFormat().format(data)
                }
            }, {
                'targets': 9,
                'render': function (data, type, row) {
                    return new Intl.NumberFormat().format(data)
                }
            }, {
                'targets': 10,
                'render': function (data, type, row) {
                    return new Intl.NumberFormat().format(data)
                }
            }, {
                'targets': 11,
                'render': function (data, type, row) {
                    return new Intl.NumberFormat().format(data)
                }
            }, {
                'targets': 12,
                'render': function (data, type, row) {
                    return new Intl.NumberFormat().format(data)
                }
            }, {
                'targets': 13,
                'render': function (data, type, row) {
                    return new Intl.NumberFormat().format(data)
                }
            }, {
                'targets': 14,
                'render': function (data, type, row) {
                    return new Intl.NumberFormat().format(data)
                }
            }]
        });
        ReportDataTable['dataQualityDonors'] = $('#dataQualityDonors').DataTable({
            'processing': true,
            'serverSide': true,
            'searching': false,
            'scrollX': true,
            "order": [[ 0, "desc" ]],
            'ajax': {
	            'url': '/data/quality/donors',
	            'type': 'POST',
	            'data': function(data){
                    var panel = $('#dataQualityDonors').parents('.panel');
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
                'data': 'I_databaseDonorsWhoVisited'
            }, {
                'data': 'K_databaseDonorsWhoVisited'
            }, {
                'data': 'I_donatedOnSiteForFirstTime'
            }, {
                'data': 'K_donatedOnSiteForFirstTime'
            }, {
                'data': 'I_totalDonorsOnSiteThisWeek'
            }, {
                'data': 'K_totalDonorsOnSiteThisWeek'
            }, {
                'data': 'I_totalDonorsInDatabase'
            }, {
                'data': 'K_totalDonorsInDatabase'
            }, {
                'data': 'K_percentDatabaseDonorsWhoCame'
            }],
            'columnDefs': [{
                'targets': 0,
                'width': 60,
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
                    return new Intl.NumberFormat().format(data)
                }
            }, {
                'targets': 6,
                'render': function (data, type, row) {
                    return new Intl.NumberFormat().format(data)
                }
            }, {
                'targets': 7,
                'render': function (data, type, row) {
                    return new Intl.NumberFormat().format(data)
                }
            }, {
                'targets': 8,
                'render': function (data, type, row) {
                    return new Intl.NumberFormat().format(data)
                }
            }, {
                'targets': 9,
                'render': function (data, type, row) {
                    return new Intl.NumberFormat().format(data)
                }
            }]
        });
        ReportDataTable['dataQualityTotalKnownUsers'] = $('#dataQualityTotalKnownUsers').DataTable({
            'processing': true,
            'serverSide': true,
            'searching': false,
            "order": [[ 0, "desc" ]],
            'ajax': {
	            'url': '/data/quality/total_known_users',
	            'type': 'POST',
	            'data': function(data){
	            	var panel = $('#dataQualityTotalKnownUsers').parents('.panel');
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
                'data': 'K_individualsWhoCameThisWeek'
            }, {
                'data': 'K_individualsInDatabase'
            }, {
                'data': 'K_percentDatabaseIndividualsWhoCame'
            }],
            'columnDefs': [{
                'targets': 0,
                'width': 60,
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
            } ]
        });
	});
</script>
@endsection