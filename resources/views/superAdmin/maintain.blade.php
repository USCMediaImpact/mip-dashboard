@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="small-12 columns">
            <div class="panel client-info">
                <div class="top-bar">
                    <div class="top-bar-left">
                        Data Sync Monitor
                    </div>
                </div>
                <div class="row">
                    <div class="small-12 columns">
                        <table class="report text-center">
                            <thead>
                                <tr>
                                    <th rowspan="2">Week</th>
                                    @foreach($result as $item)
                                    <th colspan="3">{{$item['client']}}</th>
                                    @endforeach
                                    <th rowspan="2">Action</th>
                                </tr>
                                <tr>
                                    @foreach($result as $item)
                                    <th>Users</th>
                                    <th>Stories</th>
                                    <th>Data Quality</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>

                                @foreach($weeks as $date)
                                {{-- */$thisDate = date('Y-m-d', $date); $is_sync = false;/* --}}
                                <tr>
                                    <td>{{$thisDate}}</td>
                                    @foreach($result as $item)
                                    @if(array_key_exists($thisDate, $item['users']))
                                    {{-- */$is_sync = true;/* --}}
                                    <td>
                                        @if($item['users'][$thisDate]) 
                                            Ready
                                        @else
                                            <button class="button tiny btnReady" code="{{$item['code']}}" date="{{$thisDate}}" table="data_users">mark as ready</button>
                                        @endif
                                    </td>
                                    @else
                                    <td></td>
                                    @endif
                                    @if(array_key_exists($thisDate, $item['stories']))
                                    <td>
                                        @if($item['stories'][$thisDate]) 
                                            Ready
                                        @else
                                            <button class="button tiny btnReady" code="{{$item['code']}}" date="{{$thisDate}}" table="data_stories">mark as ready</button>
                                        @endif
                                    </td>
                                    @else
                                    <td></td>
                                    @endif
                                    @if(array_key_exists($thisDate, $item['quality']))
                                    <td>
                                        @if($item['quality'][$thisDate]) 
                                            Ready
                                        @else
                                            <button class="button tiny btnReady" code="{{$item['code']}}" date="{{$thisDate}}" table="data_quality">mark as ready</button>
                                        @endif
                                    </td>
                                    @else
                                    <td></td>
                                    @endif
                                    @endforeach
                                    <td>
                                        @if(!$is_sync)
                                        <button class="button tiny btnSync">Sync</button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('reveal')
    <div id="confirmModal" class="small reveal" data-reveal>
        <button class="close-button" data-close aria-label="Close modal" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
        <h5>Confirm</h5>
        <label class="callout alert hide"></label>
        <form>
            <div class="row">
                <fieldset class="small-12 column">
                    <legend>Are you sure want mark this data to ready?</legend>
                </fieldset>
                <div class="small-12 column">
                    <div class="button-group float-right">
                        <button class="button success btnConfirm">Yes</button>
                        <button class="button alert closeModal">No</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('script')
<script>
    $(function () {
        $(document).on('click', '.btnReady', function(){
            var button = $(this),
                code = button.attr('code'),
                table = button.attr('table'),
                date = button.attr('date');

            $('#confirmModal').data('post', {
                code: code,
                table: table,
                date: date,
                button: $(this)
            }).foundation('open'); 
        });
        $(document).on('click', '.btnConfirm', function(){
            var postData = $('#confirmModal').data('post'),
                button = postData.button;
            postData = {
                _token: '{{ csrf_token() }}',
                code: postData.code,
                table: postData.table,
                date: postData.date
            };
            $.ajax({
                method: 'POST',
                data: postData
            }).done(function(){
                button.replaceWith('Ready');
                $('#confirmModal').foundation('close'); 
            });
            return false;
        });
    });
</script>
@endsection