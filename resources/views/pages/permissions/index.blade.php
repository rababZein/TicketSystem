@extends('layouts.master')

@section('title', 'Permissions Management')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Permissions Table</h3>
                <div class="card-tools">
                    @can('permission-create')
                    <button type="submit" data-toggle="modal" data-target="#newPermission"
                        class="btn btn-success btn-sm">
                        <i class="fas fa-plus fa-fw"></i>
                        <span class="d-none d-lg-inline">New Permission</span>
                    </button>
                    @endcan
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th style="width:80%">Permission name</th>
                            <th>action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($permissions as $key => $permission)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td>{{ $permission->name }}</td>
                            <td>
                                @can('permission-edit')
                                <a class="btn btn-primary btn-xs"
                                    href="{{ route('permissions.edit',$permission->id) }}">
                                    <i class="fas fa-edit fa-fw"></i>
                                </a>
                                @endcan
                                @can('permission-delete')
                                {!! Form::open(['method' => 'DELETE','route' =>
                                ['permissions.destroy',$permission->id],'style'=>'display:inline']) !!}
                                {{  Form::button('<i class="fas fa-trash fa-fw"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs']) }}
                                {!! Form::close() !!}
                                @endcan
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
            <div class="card-footer clearfix">
                {{ $permissions->links() }}
            </div>
        </div>
        <!-- /.card -->
    </div>
</div>

{{-- create permission modal --}}
<div class="modal fade" id="newPermission" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">New Permission</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form method="post" action="#" id="form" class="form-horizontal">
                <div class="modal-body">
                    <div class="alert alert-danger create" style="display:none"></div>
                    <div class="form-group error">
                        <label for="inputName" class="col-sm-12 control-label">permission name</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control has-error" id="name" name="name"
                                placeholder="permission Name" value="">
                        </div>
                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
@endsection