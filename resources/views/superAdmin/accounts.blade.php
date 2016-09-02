@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="small-12 columns">
            <div class="panel client-info">
                <div class="top-bar">
                    <div class="top-bar-left">
                        Details
                    </div>
                    <div class="top-bar-right">
                        <button class="button small btnEditDetail">Edit</button>
                    </div>
                </div>
                <div class="row">
                    <div class="small-12 columns">
                        <table class="report">
                            <thead>
                                <tr>
                                    <th>Client Name</th>
                                    <th>Data Type</th>
                                    <th>Data Source</th>
                                    <th>Data Source/Account ID</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($clients as $detail)
                                @if($detail->gtm)
                                <tr>
                                    <td>{{$detail->name}}</td>
                                    <td>Web Analytics</td>
                                    <td>MIP Google Tag Manager</td>
                                    <td>{{$detail->gtm}}</td>
                                </tr>
                                @endif
                                @if($detail->email_newsletter)
                                <tr>
                                    <td>{{$detail->name}}</td>
                                    <td>Email Newsletter</td>
                                    <td>{{$detail->email_newsletter}}</td>
                                    <td></td>
                                </tr>
                                @endif
                                @if($detail->ga)
                                <tr>
                                    <td>{{$detail->name}}</td>
                                    <td>Web Analytics</td>
                                    <td>Google Analytics</td>
                                    <td>{{$detail->ga}}</td>
                                </tr>
                                @endif
                                @if($detail->logo)
                                <tr>
                                    <td>{{$detail->name}}</td>
                                    <td>Logo File</td>
                                    <td><a href="/storage/{{$detail->code}}/logo" target="_blank;">{{$detail->logo}}</a></td>
                                    <td></td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="small-12 column">
            <div class="panel">
                <div class="top-bar">
                    <div class="top-bar-left">
                        Users
                    </div>
                    <div class="top-bar-right">
                        <button class="button" data-open="inviteModal">
                            <i class="fa fa-user-plus margin-right-sm"></i>
                            <span>Invite User</span>
                        </button>  
                    </div>
                </div>
                <table class="accountTable report">
                    <thead>
                        <tr>
                            <th>Client</th>
                            <th>Email</th>
                            <th>Name</th>
                            <th>Roles</th>
                            <th>Created</th>
                            <th>Last Active</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('reveal')
    <div id="inviteModal" class="small reveal" data-reveal>
        <button class="close-button" data-close aria-label="Close modal" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
        <h5>Invite User</h5>
        <label class="callout success">Please input the email address of the new user. Our system will send invite email to this address.</label>
        <label class="callout alert hide"></label>
        <form>
            {!! csrf_field() !!}
            <div class="row">
                <fieldset class="small-12 column">
                    <legend>Client:</legend>
                    <select style="width: 100%;" name="client_id">
                        @foreach($clients as $row)
                        <option value="{{ $row['id'] }}">{{ $row['name'] }}</option>
                        @endforeach
                    </select>
                </fieldset>
                <fieldset class="small-12 column">
                    <legend>Name:</legend>
                    <input type="text" name="name" id="" placeholder="name" />
                </fieldset>
                <fieldset class="small-12 column">
                    <legend>Email:</legend>
                    <input type="email" name="email" id="" placeholder="email address" />
                </fieldset>
                <fieldset class="small-12 column">
                    <legend>Admin Roles for User:</legend>
                    @foreach($roles as $index=>$role)
                        <input name="role[]" id="{{'ckbInviteRole-' . $index}}" type="checkbox" value="{{$role->id}}">
                        <label for="{{'ckbInviteRole-' . $index}}">{{$role->name}}</label>
                    @endforeach
                </fieldset>
                <div class="small-12 column">
                    <div class="button-group float-right">
                        <button class="button success" id="btnSubmitInvite">Save</button>
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
        <h5 class="title">Edit User</h5>
        <label class="callout alert hide"></label>
        <form>
            {!! csrf_field() !!}
            <input type="hidden" name="id" />
            <div class="row">
                <fieldset class="small-12 column">
                    <legend>Email:<label class="email"></label></legend>
                </fieldset>
                <fieldset class="small-12 column">
                    <legend>Name:</legend>
                    <input type="text" name="name" id="" placeholder="invited user name" />
                </fieldset>
                <fieldset class="small-12 column">
                    <legend>Roles:</legend>
                    @foreach($roles as $index=>$role)
                        <input name="role[]" id="{{'ckbEditRole-' . $index}}" type="checkbox" value="{{$role->id}}">
                        <label for="{{'ckbEditRole-' . $index}}">{{ $role->name }}</label>
                    @endforeach
                </fieldset>
                <fieldset class="small-12 column">
                    <legend>Client:</legend>
                    <select name="client_id" style="width: 100%">
                    <option value="">--Select--</option>
                    @foreach($clients as $value)
                    <option value="{{ $value['id'] }}">{{ $value['name'] }}</option>
                    @endforeach
                    </select>
                </fieldset>
                <div class="small-12 columns">
                    <br />
                </div>
                <div class="small-12 column">
                    <div class="button-group float-right">
                        <button class="button success" id="btnSave">Save</button>
                        <button class="button alert closeModal">Cancel</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div id="editDetailModal" class="small reveal" data-reveal>
        <button class="close-button" data-close aria-label="Close modal" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
        <h5>Edit Details</h5>
        <label class="callout alert hide"></label>
        <form id="editDetailForm" method="POST"  enctype="multipart/form-data" action="{{action('SuperAdmin\AccountController@saveClientInfo')}}">
        {!! csrf_field() !!}
        <div class="row">
            <fieldset class="small-12 column">
                <input type="hidden" name="client_id" value="" />
                <legend>Client:</legend>
                <select class="clientDetailSelector" style="width: 100%">
                <option value="">--Select--</option>
                @foreach($clients as $value)
                <option value="{{ $value['id'] }}">{{ $value['name'] }}</option>
                @endforeach
                </select>
            </fieldset>
            <fieldset class="small-12 column">
                <legend>Name:</legend>
                <input type="text" name="name" />
            </fieldset>
            <fieldset class="small-12 column">
                <legend>GA code:</legend>
                <input type="text" name="ga" />
            </fieldset>
            <fieldset class="small-12 column">
                <legend>Email Newsletter:</legend>
                <input type="text" name="email_newsletter" />
            </fieldset>
            <fieldset class="small-12 column">
                <legend>GTM code:</legend>
                <input type="text" name="gtm" />
            </fieldset>
            <fieldset class="small-12 column">
                <legend>Logo Image:</legend>
                <input type="file" name="logo" />
            </fieldset>
            <div class="small-12 column">
                <div class="button-group float-right">
                    <button class="button success" id="btnSaveDetail">Save</button>
                    <button class="button alert closeModal">Cancel</button>
                </div>
            </div>
        </div>
        </form>
    </div>
@endsection

@section('script')
<script>
    $(function () {
        /**
         * init account datatable
         */
        var dataTable = $('.accountTable').DataTable({
            'processing': true,
            'serverSide': true,
            'searching': false,
            'ajax': '/admin/account/all',
            'dom': 'Bfrtip',
            'columns': [{
                'data': ''
            }, {
                'data': 'email'
            }, {
                'data': 'name'
            }, {
                'data': ''
            }, {
                'data': 'created_at'
            }, {
                'data': 'last_login_date'
            }, {
                'data': ''
            }],
            'columnDefs': [{
                'targets': 0,
                'width': 160,
                'render': function (data, type, row) {
                    return row.client && row.client.code ? row.client.code : '';
                }
            }, {
                'targets': 1,
                'width': 200
            }, {
                'targets': 2,
                'width': 160
            }, {
                'targets': 3,
                'bSortable': false,
                'render': function (data, type, row) {
                    var roles = $.map(row.roles, function (v, i) {
                        return '<span class="label warning">' + v.name + '</span>';
                    });
                    return roles.join('');
                }
            }, {
                'targets': 4,
                'width': 160
            }, {
                'targets': 5,
                'width': 160
            }, {
                'targets': 6,
                'bSortable': false,
                'width': 180,
                'render': function (data, type, row) {
                    return '<button class="btnEdit tiny button" userId="' + row.id + '">Edit</button>' + 
                        '<button class="btnRemove tiny button" userId="' + row.id + '">Remove</button>';
                }
            }]
        });
        
        /**
         * register edit user click event
         */
        $(document).on('click', '.btnEdit', function () {
            var userId = $(this).attr('userId'),
                dialog = $('#editModal'),
                form = dialog.find('form');
            $.getJSON('/admin/account/' + userId).done(function (result) {
                form[0].reset();
                form.find('[name="id"]').val(result.id);
                form.find('[name="name"]').val(result.name);
                form.find('.email').text(result.email);
                var roles = $.map(result.roles, function (item) {
                    return item.id;
                });
                form.find('[name="role[]"]').each(function () {
                    if ($.inArray(parseInt($(this).val()), roles) > -1) {
                        $(this).prop('checked', true);
                    }
                });
                
                if(result && result.client && result.client.id){
                    form.find('[name="client_id"]').val(result.client.id);    
                }
                
                dialog.foundation('open');
            });
        });

        /**
         * register edit user submit button click event
         */
        $(document).on('click', '#btnSave', function(){
            var form = $(this).parents('form'),
                dialog = form.parents('.reveal');
            $.ajax({
                url: '/admin/account',
                method: 'POST',
                data: form.serialize()
            }).done(function (result) {
                if (result && result.success) {
                    dataTable.ajax.reload();
                    noty({text: 'save success.'});
                    dialog.foundation('close');
                } else {
                    dialog.find('.callout.alert')
                        .removeClass('hide')
                        .text(result.message ? result.message : 'unkown error!');
                }
            });
            return false;
        });

        /**
         * register remove user click event
         */
        $(document).on('click', '.btnRemove', function () {
            var urserId = $(this).attr('userId');
            noty({
                layout: 'center',
                modal: true,
                timeout: false,
                force: true,
                text: '<h5 class="text-left">Comfirm</h5><p>Do you want to remove this account?</p>',
                buttons: [{
                    addClass: 'button tiny',
                    text: 'Ok',
                    onClick: function ($noty) {
                        console.log('okay click', $noty);
                        $.ajax({
                            url: '/admin/account/' + urserId + '?_token={!! csrf_token() !!}',
                            method: 'DELETE'
                        }).done(function () {
                            dataTable.ajax.reload();
                        }).always(function () {
                            $noty.close();
                        });
                    }
                }, {
                    addClass: 'button tiny',
                    text: 'Cancel',
                    onClick: function ($noty) {
                        console.log('cancel click');
                        $noty.close();
                    }
                }]
            });
        });

        /**
         * register invite user click event
         */
        $(document).on('click', '#btnSubmitInvite', function () {
            var form = $(this).parents('form'),
                dialog = form.parents('.reveal');
            $.ajax({
                url: '/admin/account/invite',
                method: 'POST',
                data: form.serialize()
            }).done(function (result) {
                if (result && result.success) {
                    dataTable.ajax.reload();
                    noty({text: 'invite email have been send.'});
                    dialog.foundation('close');
                } else {
                    dialog.find('.callout.alert')
                        .removeClass('hide')
                        .text(result.message ? result.message : 'unkown error!');
                }
            });
            return false;
        });

        /**
         * register close modal button click event
         */
        $(document).on('click', '.closeModal', function(){
            $(this).parents('.reveal').foundation('close');
            return false;
        });

        /**
         * register clear invite account form when open modal
         */
        $('#inviteUserModal').on('open.zf.reveal', function(){
            $(this).find('form')[0].reset();
        });
        $(document).on('change', '.clientDetailSelector', function () {
            console.log('clientDetailSelector change');
            var dialog = $('#editDetailModal');
            $.ajax({
                'url': '/admin/detail/',
                'method': 'GET',
                'data': {
                    'client_id': $(this).val()
                }
            }).done(function(result){
                $('#editDetailForm')[0].reset();
                $('[name="client_id"]', dialog).val(result.id);
                $('[name="name"]', dialog).val(result.name);
                $('[name="gtm"]', dialog).val(result.gtm);
                $('[name="email_newsletter"]', dialog).val(result.email_newsletter);
                $('[name="ga"]', dialog).val(result.ga);
            });
        });
        $(document).on('click', '.btnEditDetail', function(){
            $('#editDetailForm')[0].reset();
            $('#editDetailModal').foundation('open'); 
        });

        $(document).on('click', '.btnSaveDetail', function(){
            $('#editDetailForm').submit();
        });
    });
</script>
@endsection