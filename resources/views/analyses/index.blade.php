@inject('formatter', 'App\Helpers\FormatterHelper')
@extends('layouts.main')

@section('content')
	<div class="row expanded">
		<div class="column small-9">
			<h4 class="title">Analyses</h4>
            <h5 class="sec-title">Upload, view and download ad hoc analyses</h5>
		</div>
	</div>
	<div class="row expanded">
		<div class="column small-12">
			<div class="panel">
				<div class="top-bar">
					<div class="top-bar-left"></div>
					<div class="top-bar-right bar-icon-group">
                        <button class="has-tip top btnPdfDownload" title="download" data-tooltip aria-haspopup="false" data-disable-hover="false"><i class="fa fa-download"></i></button>
                        <button class="has-tip top btnPdfEdit disabled" title="edit" data-tooltip aria-haspopup="false" data-disable-hover="false"><i class="fa fa-edit"></i></button>
                        <button class="has-tip top btnPdfDelete" title="delete" data-tooltip aria-haspopup="false" data-disable-hover="false"><i class="fa fa-remove"></i></button>
					</div>
				</div>
				<div class="row small-up-1 medium-up-2 large-up-3">
                    <div class="column analyses">
                        <form id="form_upload" enctype="multipart/form-data" action="{{action('AnalysesController@upload')}}" method="POST">
                        {!! csrf_field() !!}
                        <input type="hidden" name="description" />
                        <div class="box upload">
                            <button class="analyses_btn_upload">
                                <i class="fa fa-plus fa-2x"></i>
                            </button>
                            <input type="file" name="content" />
                            <label>Upload a file</label>
                        </div>

                        </form>
                    </div>
                    @foreach ($data as $item)
                    <div class="column analyses">
                        <div id="{{$item->file_id}}" class="box">
                            <a class="pdf" href="/analyses/{{$item->file_id}}" style="background-image: url({{$item->screen_shot ? $item->screen_shot : '/images/pdf.png'}})">
                            </a>
                            <div class="description">{{$item->description}}</div>
                            <input class="checkpdf" type="checkbox" />
                        </div>
                        
                    </div>
                    @endforeach
                </div>
	        </div>
		</div>
	</div>

@endsection
@section('reveal')
    <div id="uploadModal" class="small reveal" data-reveal>
        <button class="close-button" data-close aria-label="Close modal" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
        <h5>Upload File</h5>
        <label class="callout alert hide"></label>
        <div class="row">
            <fieldset class="small-12 column">
                <legend>Description:<label class="editName"></label></legend>
                <textarea style="height: 80px;" name="description" placeholder="Please Input Description"></textarea>
            </fieldset>
            <div class="small-12 column">
                <div class="button-group float-right">
                    <button class="button success btnEditPdfSave">Save</button>
                    <button class="button alert closeModal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <div id="editModal" class="small reveal" data-reveal>
        <button class="close-button" data-close aria-label="Close modal" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
        <h5>Edit File Description</h5>
        <label class="callout alert hide"></label>
        <form>
            <div class="row">
                <fieldset class="small-12 column">
                    <legend>Description:<label class="editName"></label></legend>
                    <input type="hidden" name="file_id" />
                    <textarea  style="height: 80px;" name="description" placeholder="please input description"></textarea>
                </fieldset>
                <div class="small-12 column">
                    <div class="button-group float-right">
                        <button class="button success btnEditPdfSave">Save</button>
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
                    <legend>Are you sure want to delete these selected files?</legend>
                </fieldset>
                <div class="small-12 column">
                    <div class="button-group float-right">
                        <button class="button success btnDeletePdfYes">Yes</button>
                        <button class="button alert closeModal">No</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('script')
<script>
    $(function(){
        $(document).on('click', '.closeModal', function(){
            $(this).parents('.reveal').foundation('close');
            return false;
        });
        $(document).on('change', '#form_upload', function(){
            $('#uploadModal').foundation('open');
            return false;
        });
        $(document).on('click', '.btnUploadPdfSave', function(){
            var desc = $('#uploadModal [name="description"]').val();
            $('#form_upload [name="description"]').val(desc);
            $('#form_upload').submit();
            return false;
        });
        $(document).on('click', '.btnPdfDownload', function () {
            var files = $('.box.active').map(function () {
                    return $(this).attr('id');
                }),
                downloadForm = $('<form />', {
                    action: '/analyses/download',
                    method: 'POST'
                });
            downloadForm.append($('{!! csrf_field() !!}'));
            $('.box.active').each(function(index){
                downloadForm.append($('<input type="hidden" name="file_id[]" value="' + $(this).attr('id') + '" />'));
            });
            downloadForm.appendTo('body');
            downloadForm.submit();
            downloadForm.remove();
            return false;
        });
        $(document).on('click', '.checkpdf', function(){
            var checked = $(this).prop('checked');
            $(this).parents('.box')[checked ? 'addClass' : 'removeClass']('active');
            checkToolbarStatus();
        });
        $(document).on('click', '.btnPdfEdit', function(){
            if($('.box.active').size() > 0){
                var activeBox = $('.box.active').eq(0),
                    dialog = $('#editModal'),
                    fileId = activeBox.attr('id'),
                    desc = activeBox.find('.description').text();
                
                dialog.find('[name="file_id"]').val(fileId);
                dialog.find('[name="description"]').val(desc);
                dialog.foundation('open');
            }
        });
        $(document).on('click', '.btnEditPdfSave', function(){
            var dialog = $(this).parents('.reveal'),
                fileId = dialog.find('[name="file_id"]').val(),
                desc = dialog.find('[name="description"]').val();
            $.ajax({
                'url': '/analyses/edit',
                'method': 'POST',
                'data': {
                    _token: '{{ csrf_token() }}',
                    file_id: fileId, 
                    description: desc
                }
            }).done(function(result){
                if(result && result.success && result.file_id){
                    $('#' + result.file_id).find('.description').text(result.description);
                    dialog.foundation('close');
                }
            }).fail(function(){
                window.location.reload(false);
            }).always(function(){
                dialog.find('form')[0].reset();
            });
            return false;
        });
        $(document).on('click', '.btnPdfDelete', function(){
            var files = $('.box.active').map(function () {
                return $(this).attr('id');
            });
            if(files.length > 0){
                $('#deleteModal').foundation('open');
            }
        });
        $(document).on('click', '.btnDeletePdfYes', function(){
            var files = [];
            $('.box.active').each(function () {
                files.push($(this).attr('id'));
            });
            postData = $.param({
                _token: '{{ csrf_token() }}',
                'file_id[]': files
            });
            $.ajax({
                'url': '/analyses/delete',
                'method': 'POST',
                'data': postData
            }).done(function(result){
                if(result && result.success){
                    $.each(files, function(){
                        $('#' + this).parents('.analyses').remove();
                    });
                }
                $('#deleteModal').foundation('close');
            });
            return false;
        });
        //restore checked status
        $('.checkpdf:checked').each(function(){
            $(this).parents('.box').addClass('active');
        });
        //restore edit button status
        function checkToolbarStatus(){
            var activeBoxSize = $('.box.active').size();
            $('.btnPdfEdit')[activeBoxSize == 1 ? 'removeClass' : 'addClass']('disabled');
            $('.btnPdfDownload, .btnPdfDelete')[activeBoxSize > 0 ? 'removeClass' : 'addClass']('disabled');
        }
        checkToolbarStatus();
    });
</script>
@endsection