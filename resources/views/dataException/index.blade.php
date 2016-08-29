@inject('formatter', 'App\Helpers\FormatterHelper')
@extends('layouts.main')

@section('content')
	<div class="row expanded">
		<div class="column small-9">
			<h4 class="title">Data Exceptions</h4>
            <h5 class="sec-title">Add and view a log of data exceptions</h5>
		</div>
	</div>
	<div class="row expanded">
		<div class="column small-12">
			<div class="panel">
				<div class="top-bar">
					<div class="top-bar-left"></div>
					<div class="top-bar-right bar-icon-group">
                        <form id="dateChangeForm" method="post">
                        @include('widgets.daterange', ['min_date' => $min_date, 'max_date' => $max_date])
                        {!! csrf_field() !!}
                        </form>
                        <button class="has-tip top btnAdd" title="add" data-tooltip aria-haspopup="false" data-disable-hover="false"><i class="fa fa-plus"></i></button>
                        <button class="has-tip top btnDataExceptionDownload" title="download" data-tooltip aria-haspopup="false" data-disable-hover="false"><i class="fa fa-download"></i></button>
					</div>
				</div>
                @if (count($data) > 0)
				<div class="card-columns">
                    @foreach ($data as $item)
                    <div class="data-exception card">
                        <div id="{{$item->id}}" class="box" data-id={{$item->id}}>
                            <div class="button-group">
                                <a href="javascript:;"><i class="fa fa-pencil btnEdit"></i></a>
                                <a href="javascript:;"><i class="fa fa-trash btnDelete"></i></a>
                            </div>
                            <div class="row">
                                <div class="small-12 columns date">{{date('m/d/Y h:i A', strtotime($item->created_at))}}</div>
                                <div class="small-12 columns title">
                                    {{$item->title}}
                                </div>
                                <div class="small-12 columns">
                                    <label>DATA IMPACT
                                        <div class="input">{{$item->data_impact}}</div>
                                    </label>
                                </div>
                                <div class="small-12 columns">
                                    <label>RESOLUTION
                                        <div class="input">{{$item->resolution}}</div>
                                    </label>
                                </div>
                                <div class="small-12 columns">
                                    <label>IMPACTED DATES
                                        <div class="input">{{date('m/d/Y', strtotime($item->begin_date))}} to {{date('m/d/Y', strtotime($item->end_date))}}</div>
                                    </label>
                                </div>
                                <div class="small-12 columns">
                                    <label>REPORTED BY
                                        <div class="input">{{$item->reporter->name}}</div>
                                        <div class="input email">{{$item->reporter->email}}</div>
                                    </label>
                                </div>
                                <div class="small-12 columns text-right resolved">
                                    {!! $item->resolved ? 'RESOLVED' : '&nbsp;' !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                    <div class="row">
                        <div class="small-12 column">
                            <h5 class="text-center">NO DATA EXCEPTIONS</h5>
                        </div>
                    </div>
                @endif
	        </div>
		</div>
	</div>

@endsection
@section('reveal')
    <div id="newModal" class="small reveal" data-reveal>
        <button class="close-button" data-close aria-label="Close modal" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
        <h5>Add New Data Exception</h5>
        <label class="callout alert hide"></label>
        <form>
        <div class="row">
            <fieldset class="small-12 column">
                <legend>Issue Description:</legend>
                <textarea style="height: 80px;" name="title"></textarea>
            </fieldset>
            <fieldset class="small-12 column">
                <legend>DATA IMPACT:</legend>
                <input type="text" name="data_impact" />
            </fieldset>
            <fieldset class="small-12 column">
                <legend>RESOLUTION:</legend>
                <input type="text" name="resolution" />
            </fieldset>
            <fieldset class="small-12 column">
                <legend>IMPACTED DATES:</legend>
                @include('widgets.daterange', ['dateRangeClass' => 'no_event_date_range', 'default_date_range' => 'Please select begin and end date'])
            </fieldset>
            <div class="small-12 column">
                <br />
            </div>
            <div class="small-12 column">
                <div class="button-group float-right">
                    <button class="button success btnNewSave">Save</button>
                    <button class="button alert closeModal">Cancel</button>
                </div>
            </div>
        </div>
        </form>
    </div>
    <div id="editModal" class="small reveal" data-reveal>
        <button class="close-button" data-close aria-label="Close modal" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
        <h5>Edit Data Exception</h5>
        <label class="callout alert hide"></label>
        <form>
            <input type="hidden" name="id" />
            <div class="row">
                <fieldset class="small-12 column">
                    <legend>Issue Description:</legend>
                    <textarea style="height: 80px;" name="title"></textarea>
                </fieldset>
                <fieldset class="small-12 column">
                    <legend>DATA IMPACT:</legend>
                    <input type="text" name="data_impact" />
                </fieldset>
                <fieldset class="small-12 column">
                    <legend>RESOLUTION:</legend>
                    <input type="text" name="resolution" />
                </fieldset>
                <fieldset class="small-6 column">
                    <legend>IMPACTED DATES:</legend>
                    @include('widgets.daterange', ['dateRangeClass' => 'no_event_date_range', 'default_date_range' => 'Please select begin and end date'])
                </fieldset>
                <fieldset class="small-6 column">
                    <legend>RESOLVED:</legend>
                    <div class="switch round">
                        <input class="switch-input" id="yes-no" type="checkbox" name="resolved">
                        <label class="switch-paddle" for="yes-no">
                            <span class="switch-active" aria-hidden="true">Yes</span>
                            <span class="switch-inactive" aria-hidden="true">No</span>
                        </label>
                    </div>
                </fieldset>
                <div class="small-12 column">
                    <br />
                </div>
                <div class="small-12 column">
                    <div class="button-group float-right">
                        <button class="button success btnEditSave">Save</button>
                        <button class="button alert closeModal">Cancel</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div id="deleteModal" class="small reveal" data-reveal>
        <button class="close-button" data-close aria-label="Close modal" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
        <h5>Confirm Delete</h5>
        <label class="callout alert hide"></label>
        <form>
            <div class="row">
                <fieldset class="small-12 column">
                    <legend>Are you sure want to delete selected data exception?</legend>
                </fieldset>
                <div class="small-12 column">
                    <div class="button-group float-right">
                        <button class="button success btnDeleteYes">Yes</button>
                        <button class="button alert closeModal">No</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('script')
<script>
    DefaultDateRangePickerOptions = {
        clear: function(){
            var form = $('#dateChangeForm');
            $('input[name="min_date"]', form).val('');
            $('input[name="max_date"]', form).val('');
            form.submit();
        }
    };
    $(function(){
        $(document).on('click', '.closeModal', function(){
            $(this).parents('.reveal').foundation('close');
            return false;
        });
        $(document).on('click', '.btnAdd', function(){
            $('#newModal').foundation('open');
            return false;
        });
        $(document).on('click', '.btnNewSave', function(){
            var dialog = $(this).parents('.reveal'),
                title = $('[name="title"]', dialog).val(),
                dataImpact = $('[name="data_impact"]', dialog).val(),
                resolution = $('[name="resolution"]', dialog).val(),
                dateRange = $('.no_event_date_range', dialog).daterangepicker('getRange'),
                beginDate = moment(dateRange.start).format('YYYY-MM-DD'),
                endDate = moment(dateRange.end).format('YYYY-MM-DD');
            $.ajax({
                'url': '/management/data-exception/new',
                'method': 'POST',
                'data': {
                    '_token': '{!! csrf_token() !!}',
                    'title': title,
                    'data_impact': dataImpact,
                    'resolution': resolution,
                    'begin_date': beginDate,
                    'end_date': endDate
                }
            }).done(function(result){
                window.location = '/management/data-exception';
            });
            return false;
        });
        $(document).on('click', '.btnEdit', function(){
            var box = $(this).parents('.box'),
                id = box.attr('data-id');
            $.ajax({
                'url': '/management/data-exception/' + id,
                'method': 'GET'
            }).done(function(result){
                var dialog = $('#editModal');
                $('[name="id"]', dialog).val(result.id);
                $('[name="title"]', dialog).val(result.title);
                $('[name="data_impact"]', dialog).val(result.data_impact);
                $('[name="resolution"]', dialog).val(result.resolution);
                $('.no_event_date_range', dialog).daterangepicker('setRange', {
                    start: moment(result.begin_date).toDate(),
                    end: moment(result.end_date).toDate()
                });
                $('[name="resolved"]', dialog).prop('checked', result.resolved);
                dialog.foundation('open');    
            });
            return false;
        });
        $(document).on('click', '.btnEditSave', function(){
            var dialog = $(this).parents('.reveal'),
                id = $('[name="id"]', dialog).val(),
                title = $('[name="title"]', dialog).val(),
                dataImpact = $('[name="data_impact"]', dialog).val(),
                resolution = $('[name="resolution"]', dialog).val(),
                dateRange = $('.no_event_date_range', dialog).daterangepicker('getRange'),
                beginDate = moment(dateRange.start).format('YYYY-MM-DD'),
                endDate = moment(dateRange.end).format('YYYY-MM-DD'),
                resolved = $('[name="resolved"]', dialog).prop('checked') == true ? 1 : 0;
            $.ajax({
                'url': '/management/data-exception/edit',
                'method': 'POST',
                'data': {
                    '_token': '{!! csrf_token() !!}',
                    'id': id,
                    'title': title,
                    'data_impact': dataImpact,
                    'resolution': resolution,
                    'begin_date': beginDate,
                    'end_date': endDate,
                    'resolved': resolved
                }
            }).done(function(result){
                window.location = '/management/data-exception';
            });
            return false;
        });
        $(document).on('click', '.btnDelete', function(){
            var box = $(this).parents('.box'),
                id = box.attr('data-id');

            $('#deleteModal').attr('data-id', id).foundation('open');
            return false;
        });
        $(document).on('click', '.btnDeleteYes', function(){
            var dialog = $(this).parents('.reveal');
            $.ajax({
                'url': '/management/data-exception/delete',
                'method': 'POST',
                'data': {
                    _token: '{{ csrf_token() }}',
                    'id': dialog.attr('data-id')
                }
            }).done(function(result){
                if(result && result.success){
                    $('.box[data-id="' + result.id + '"]').remove();
                }
            }).alwyas(function(){
                $('#deleteModal').foundation('close'); 
            });
            return false;
        });
        $(document).on('click', '.btnDataExceptionDownload', function () {
            var downloadForm = $('<form />', {
                action: '/management/data-exception/download',
                method: 'POST',
            });
            downloadForm.append($('{!! csrf_field() !!}'));
            downloadForm.appendTo('body');
            downloadForm.submit();
            downloadForm.remove();
            return false;
        });

        $(document).on('daterange_change', '.panel', function(){
            $('#dateChangeForm').submit();
        });
    });
</script>
@endsection