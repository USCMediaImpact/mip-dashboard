@extends('layouts.main')

@section('content')
    <form method="POST" action="/admin/client/setting">
        {!! csrf_field() !!}
        <input type="hidden" name="client_id" value="{{$client->id}}" />
        <div class="row">
            <div class="small-12 columns">
                <div class="row align-middle">
                    <div class="small-4 columns">
                        <label for="">Enable Cron Background Sync?</label>
                        <div class="switch">
                            <input name="enable_sync" {!! $enable_sync ? 'checked' : '' !!} class="switch-input" id="enabled_sync" type="checkbox" >
                            <label class="switch-paddle" for="enabled_sync">
                                <span class="switch-active" aria-hidden="true">Yes</span>
                                <span class="switch-inactive" aria-hidden="true">No</span>
                            </label>
                        </div>
                    </div>
                    <div class="medium-3 columns">
                        <label>Google Anayslis PID: 
                            <input name="ga_id" type="text" placeholder="" value="{!! array_key_exists('ga_id', $values) ? $values['ga_id'] : '' !!}">
                        </label>
                    </div>
                    <div class="medium-3 columns">
                        <label>Big Query PID: 
                            <input name="bq_id" type="text" placeholder="" value="{!! array_key_exists('bq_id', $values) ? $values['bq_id'] : '' !!}">
                        </label>
                    </div>
                    <div class="medium-2 columns text-center">
                        <button class="button success">Save</button>
                    </div>
                </div>
                <ul class="tabs" data-tabs id="setting-tabs">
                    <li class="tabs-title is-active"><a href="#data_content" aria-selected="true">Data Content</a></li>
                    <li class="tabs-title"><a href="#data_users" aria-selected="true">Data Users</a></li>
                    <li class="tabs-title"><a href="#data_quality" aria-selected="true">Data Quality</a></li>
                </ul>
                <div class="tabs-content" data-tabs-content="setting-tabs">
                    <div class="tabs-panel is-active" id="data_content">
                        <textarea name="bq_dataContent">{!! array_key_exists('bq_dataContent', $values) ? $values['bq_dataContent'] : '' !!}</textarea>
                    </div>
                    <div class="tabs-panel" id="data_users">
                        <textarea name="bq_data_users">{!! array_key_exists('bq_data_users', $values) ? $values['bq_data_users'] : '' !!}</textarea>
                    </div>
                    <div class="tabs-panel" id="data_quality">
                        <textarea name="bq_data_quality">{!! array_key_exists('bq_data_quality', $values) ? $values['bq_data_quality'] : '' !!}</textarea>
                    </div>
                    
                </div>
            </div>
        </div>
    </form>
@endsection

@section('script')
<script>
    $(function(){
        $(document).on('change.zf.tabs', function(){
            var textAreaHeight = $(window).height() - $('textarea').offset().top - 60;
            console.log(textAreaHeight);
            $('.tabs-panel.is-active textarea').height(textAreaHeight);
        });
        $(document).trigger('change.zf.tabs');
    });
</script>
@endsection