@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="small-12 column">
            <div class="table-wrapper">
                <div class="table-toolbar">
                    <button class="button" data-open="inviteUserModal">
                        <i class="fa fa-user-plus margin-right-sm"></i>
                        <span>Invite User</span>
                    </button>    
                </div>
                <table class="dataTable accountTable">
                    <thead>
                        <tr>
                            <td>Email</td>
                            <td>Name</td>
                            <td>Roles</td>
                            <td>Create Date</td>
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
    <div id="inviteUserModal" class="small reveal" data-reveal>
        <button class="close-button" data-close aria-label="Close modal" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
        <h5>Invite User</h5>
        <label class="callout success">Please input you need invited user email. system will send invite email to this address</label>
        <form>
            {!! csrf_field() !!}
            <div class="row">
                <fieldset class="small-12 column">
                    <legend>Email:</legend>
                    <input type="email" name="email" id="" placeholder="invited user email" />
                </fieldset>
                <fieldset class="small-12 column">
                    <legend>Roles:</legend>
                    @foreach($roles as $index=>$role)
                        <input name="role" id="{{'ckbRole-' . $index}}" type="checkbox" value="{{$role->id}}">
                        <label for="{{'ckbRole-' . $index}}">{{$role->name}}</label>
                    @endforeach
                </fieldset>
                <div class="small-12 column">
                    <div class="button-group float-right">
                        <button class="button success" id="btnSubmitInvite">Save</button>
                        <button class="button alert">Cancel</button>
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
        $('.accountTable').DataTable({
            'processing': true,
            'serverSide': true,
            'ajax': '/auth/account/all',
            'dom': 'Bfrtip',
            'columns': [{
                'data': 'email'
            }, {
                'data': 'name'
            }, {
                'data': ''
            }, {
                'data': 'created_at'
            }],
            'columnDefs': [{
                'targets': 0,
                'width': 200
            }, {
                'targets': 1,
                'width': 160
            }, {
                'targets': 2,
                'bSortable': false,
                'render': function (data, type, row) {
                    var roles = $.map(row.roles, function (v, i) {
                        return '<span class="label warning">' + v.name + '</span>';
                    });
                    return roles.join('');
                }
            }, {
                'targets': 3,
                'width': 160
            }]
        });

        /**
         * register invite user click event
         */
        $(document).on('click', '#btnSubmitInvite', function(){
            var form = $(this).parents('form');

            $.ajax({
                url: '/auth/account/invite',
                method: 'POST',
                data: form.serialize()
            }).done(function(result){
                console.log(result);
                $('#inviteUserModal').foundation('close');
            });
            return false;
        });
    });
</script>
@endsection