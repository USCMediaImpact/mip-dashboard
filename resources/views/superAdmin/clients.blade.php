@extends('layouts.main')

@section('content')
    <form action="">
    <div class="row">
        <div class="small-12 column">
            <div class="table-wrapper">
                <div class="table-toolbar">
                    <button class="button btnNew">
                        <i class="fa fa-user-plus margin-right-sm"></i>
                        <span>Add Client</span>
                    </button>    
                </div>
                <table class="dataTable accountTable">
                    <thead>
                        <tr>
                            <td>Name</td>
                            <td>Code</td>
                            <td>WebSite</td>
                            <td>Create Date</td>
                            <td>Action</td>
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
    <div id="editModal" class="small reveal" data-reveal>
        <button class="close-button" data-close aria-label="Close modal" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="title">Edit Client</h5>
        <label class="callout alert hide"></label>
        <form>
            {!! csrf_field() !!}
            <input type="hidden" name="id" />
            <div class="row">
                <fieldset class="small-12 column">
                    <legend>Name:<label class="editName"></label></legend>
                    <input type="text" name="name" placeholder="client name" />
                </fieldset>
                <fieldset class="small-12 column">
                    <legend>Code:</legend>
                    <input type="text" name="code" placeholder="client short name" />
                </fieldset>
                <fieldset class="small-12 column">
                    <legend>Website:</legend>
                    <input type="text" name="website" placeholder="client website" />
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
    $(function () {
        /**
         * init account datatable
         */
        var dataTable = $('.dataTable').DataTable({
            'processing': true,
            'serverSide': true,
            'ajax': '/admin/client/all',
            'dom': 'Bfrtip',
            'columns': [{
                'data': 'name'
            }, {
                'data': 'code'
            }, {
                'data': 'website'
            }, {
                'data': 'created_at'
            }, {
                'data': ''
            }],
            'columnDefs': [{
                'targets': 0,
            }, {
                'targets': 1,
                'width': 80
            }, {
                'targets': 2,
                'width': 180
            }, {
                'targets': 3,
                'width': 180
            }, {
                'targets': 4,
                'bSortable': false,
                'width': 120,
                'render': function (data, type, row) {
                    return '<button class="btnEdit tiny button" clientId="' + row.id + '">Edit</button>' + 
                        '<button class="btnRemove tiny button" clientId="' + row.id + '">Remove</button>' +
                        '<a class="tiny button" href="setting/' + row.id + '">Set</button>';
                }
            }]
        });
        
        /**
         * register edit user click event
         */
        $(document).on('click', '.btnEdit, .btnNew', function () {
            var clientId = $(this).attr('clientId'),
                dialog = $('#editModal'),
                form = dialog.find('form');
            if (clientId) {
                $.getJSON('/admin/client/' + clientId).done(function (result) {
                    form[0].reset();
                    dialog.find('.title').text('Edit Client');
                    form.find('[name="id"]').val(result.id);
                    form.find('[name="name"]').val(result.name);
                    form.find('[name="code"]').val(result.code);
                    form.find('[name="website"]').val(result.website);
                    dialog.foundation('open');
                });
            } else {
                form[0].reset();
                form.find('[name="id"]').val('');
                dialog.find('.title').text('New Client');
                dialog.foundation('open');
            }
        });

        /**
         * register edit user submit button click event
         */
        $(document).on('click', '.btnSave', function(){
            var form = $(this).parents('form'),
                dialog = form.parents('.reveal');
            $.ajax({
                url: '/admin/client',
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
            var clientId = $(this).attr('clientId');
            noty({
                layout: 'center',
                modal: true,
                timeout: false,
                force: true,
                text: '<h5 class="text-left">Comfirm</h5><p>Do you want to remove this client?</p>',
                buttons: [{
                    addClass: 'button tiny',
                    text: 'Ok',
                    onClick: function ($noty) {
                        console.log('okay click', $noty);
                        $.ajax({
                            url: '/admin/client/' + clientId + '?_token={!! csrf_token() !!}',
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
                        $noty.close();
                    }
                }]
            });
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
    });
</script>
@endsection