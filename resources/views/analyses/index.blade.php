@inject('formatter', 'App\Helpers\FormatterHelper')
@extends('layouts.main')

@section('content')
	<div class="row expanded">
		<div class="column small-9">
			<h4 class="title">Analyses</h4>
            <span>Upload, view and download ad hoc analyses</span>
		</div>
	</div>
	<div class="row expanded">
		<div class="column small-12">
			<div class="panel">
				<div class="top-bar">
					<div class="top-bar-left"></div>
					<div class="top-bar-right">
                        <button><i class="fa fa-download"></i></button>
                        <button><i class="fa fa-view"></i></button>
                        <button><i class="fa fa-edit"></i></button>
                        <button><i class="fa fa-delete"></i></button>
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
                        <div class="box">
                            <a class="pdf" href="/analyses/{{$item->file_id}}" target="_blank;"></a>
                            <div class="description">
                                {{$item->description}} of {{$item->created_at}}
                            </div>
                        </div>
                        
                    </div>
                    @endforeach
                </div>
	        </div>
		</div>
	</div>

@endsection
@section('reveal')
    <div id="editModal" class="small reveal" data-reveal>
        <button class="close-button" data-close aria-label="Close modal" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
        
        <label class="callout alert hide"></label>
            <div class="row">
                <fieldset class="small-12 column">
                    <legend>Description:<label class="editName"></label></legend>
                    <textarea type="text" name="description" placeholder="Please Input Description"></textarea>
                </fieldset>
                <div class="small-12 column">
                    <div class="button-group float-right">
                        <button class="button success btnSave">Save</button>
                        <button class="button alert closeModal">Cancel</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('script')
<script>
    $(function(){
        $(document).on('change', '#form_upload', function(){
            $('#editModal').foundation('open');
            return false;
        });
        $(document).on('click', '.closeModal', function(){
            $(this).parents('.reveal').foundation('close');
            return false;
        });
        $(document).on('click', '.btnSave', function(){
            var desc = $('#editModal [name="description"]').val();
            $('#form_upload [name="description"]').val(desc);
            $('#form_upload').submit();
            return false;
        });
    });
</script>
@endsection