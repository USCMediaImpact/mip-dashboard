@inject('formatter', 'App\Helpers\FormatterHelper')
@extends('layouts.main')

@section('content')
	@if ($have_data)
		<div class="row expanded">
			<div class="column small-9">
				<h4 class="title">Email Newsletters</h4>
                {{-- <h5 class="sub-title">with Data from MailChimp</h5> --}}
			</div>
		</div>
		<div class="row expanded">
			<div class="column small-12">
				<div class="panel">
					<div class="top-bar">
						<div class="top-bar-left">
							Email Newsletter Performance
						</div>
						<div class="top-bar-right">
                            @include('widgets.daterange', ['min_date' => $min_date, 'max_date' => $max_date])
							<button class="button small btnDownload" action='/data/newsletter/csv'>Download</button>
						</div>
					</div>
					<table id="dataNewsLetter" class="report tiny hover">
			            <thead>
			                <tr>
			                	<th>Newsletter</th>
			                    <th>Frequency</th>
			                    <th>Deliveries</th>
			                    <th>Opens</th>
			   					<th>Unique Opens</th>
			   					<th>Clicks</th>
                                <th>Open Rate</th>
                                <th>Click to Delivery Rate</th>
                                <th>Average Total Clicks Per Unique Open</th>
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
	        <div>Email Newsletter Coming soon.</div>
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
		ReportDataTable['dataNewsLetter'] = $('#dataNewsLetter').DataTable({
            'processing': true,
            'serverSide': true,
            'searching': false,
            "order": [[ 0, "desc" ]],
            'ajax': {
	            'url': '/data/newsletter',
	            'type': 'POST',
	            'data': function(data){
                    var panel = $('#dataNewsLetter').parents('.panel');
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
                'data': ''
            }, {
                'data': ''
            }, {
                'data': ''
            }, {
                'data': ''
            }, {
                'data': ''
            }, {
                'data': ''
            }, {
                'data': ''
            }, {
                'data': ''
            }],
            'columnDefs': [{
                'targets': 0,
                'render': function (data, type, row) {
                    return data;
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
                	return new Intl.NumberFormat('en-US', {style: 'percent', minimumFractionDigits: 0}).format(data);
                }
            } ]
        });
	});
</script>
@endsection