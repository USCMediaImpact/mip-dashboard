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
                    <li class="tabs-title is-active"><a href="#bq_prepare" aria-selected="true">Prepare</a></li>
                    <li class="tabs-title"><a href="#data_users" aria-selected="true">Data Users</a></li>
                    <li class="tabs-title"><a href="#bq_data_stories" aria-selected="true">Data Stories</a></li>
                    <li class="tabs-title"><a href="#data_quality" aria-selected="true">Data Quality</a></li>
                </ul>
                <div class="tabs-content" data-tabs-content="setting-tabs">
                    <div class="tabs-panel is-active" id="bq_prepare">
                        <div class="row">
                            <div class="small-12 columns"><button class="btnAddPrepare button tiny success float-right">Add Prepare </button></div>
                        </div>
                        @if (array_key_exists('bq_prepare', $values) && is_array($values['bq_prepare']))
                            <ul class="accordion" data-accordion data-allow-all-closed="true">
                            @foreach($values['bq_prepare'] as $i => $row)
                                
                                <li class="accordion-item" data-accordion-item>
                                    <a href="#" class="accordion-title">
                                        <div class="row">
                                            <div class="bar cloumns small-10">
                                                &nbsp;&nbsp;&nbsp;&nbsp;
                                                {{ is_array($row) && array_key_exists('table', $row) ? $row['table'] : '' }}
                                                &nbsp;&nbsp;&nbsp;&nbsp;
                                                {{ is_array($row) && array_key_exists('title', $row) ? $row['title'] : '' }}
                                            </div>
                                        </div>
                                    </a>
                                    <div class="accordion-content" data-tab-content>
                                        <div class="row align-middle">
                                            <div class="small-3 columns">
                                                <label class="middle">DataTable
                                                    <input type="text" name="bq_prepare[{{$i}}][table]" value="{{ is_array($row) && array_key_exists('table', $row) ? $row['table'] : '' }}" />
                                                </label>
                                            </div>
                                            <div class="small-7 columns">
                                                <label class="middle">Description
                                                    <input type="text" name="bq_prepare[{{$i}}][title]" value="{{ is_array($row) && array_key_exists('title', $row) ? $row['title'] : '' }}" />
                                                </label>
                                            </div>
                                            <div class="small-2 columns">
                                                <button class="btnDeletePrepare button tiny float-right">Delete</button>    
                                            </div>
                                        </div>
                                        <textarea name="bq_prepare[{{$i}}][sql]">{{ is_array($row) && array_key_exists('sql', $row) ? $row['sql'] : '' }}</textarea>
                                    </div>
                                </li>
                            @endforeach
                            </ul>
                        @else
                            <ul class="accordion" data-accordion>
                                <li class="accordion-item" data-accordion-item>
                                    <a href="#" class="accordion-title">
                                    </a>
                                    <div class="accordion-content" data-tab-content>
                                        <div class="row align-middle">
                                            <div class="small-3 columns">
                                                <label class="middle">DataTable
                                                    <input type="text" name="bq_prepare[0][table]" value="" />
                                                    
                                                </label>
                                            </div>
                                            <div class="small-7 columns">
                                                <label class="middle">Description
                                                    <input type="text" name="bq_prepare[0][title]" value="" />
                                                </label>
                                            </div>
                                            <div class="small-2 columns">
                                                  
                                            </div>                                            
                                        </div>
                                        <textarea name="bq_prepare[0][sql]"></textarea>
                                    </div>
                                </li>
                        @endif
                    </div>
                    <div class="tabs-panel" id="data_users">
                        <div class="row">
                            <div class="columns small-12 text-right">
                                @include('layouts/checkbox', ['name'=>'data_users_dimension', 'keys'=>['daily', 'weekly', 'monthly'], 'values'=>$values])
                            </div>
                        </div>
                        
                        <textarea name="bq_data_users">{!! array_key_exists('bq_data_users', $values) ? $values['bq_data_users'] : '' !!}</textarea>
                    </div>
                    <div class="tabs-panel" id="bq_data_stories">
                        <div class="row">
                            <div class="columns small-12 text-right">
                                @include('layouts/checkbox', ['name'=>'data_stories_dimension', 'keys'=>['daily', 'weekly', 'monthly'], 'values'=>$values])
                            </div>
                        </div>

                        <textarea name="bq_data_stories">{!! array_key_exists('bq_data_stories', $values) ? $values['bq_data_stories'] : '' !!}</textarea>
                    </div>
                    <div class="tabs-panel" id="data_quality">
                        <div class="row">
                            <div class="columns small-12 text-right">
                                @include('layouts/checkbox', ['name'=>'data_quality_dimension', 'keys'=>['daily', 'weekly', 'monthly'], 'values'=>$values])
                            </div>
                        </div>

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
        $(document).on('click', '.btnEditPrepareTitle', function(){
            var row = $(this).parents('.row').find('.bar').toggleClass('hide');
            return false;
        });
        $(document).on('click', '.btnAddPrepare', function(){
            var count = $(this).parents('.tabs-panel').find('.accordion > li').size(),
                form = $(this).parents('form');
            form.append($('<input type="hidden" name="bq_prepare[' + count + '][\'title\']" />'));
            form.append($('<input type="hidden" name="bq_prepare[' + count + '][\'table\']" />'));
            form.append($('<input type="hidden" name="bq_prepare[' + count + '][\'sql\']" />'));
            form[0].submit();
        });
        $(document).on('click', '.btnDeletePrepare', function(){
            $(this).parents('.accordion-item').remove();
            return false;
        });
    });
</script>
@endsection