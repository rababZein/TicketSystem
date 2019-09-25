@extends('layouts.master')

@section('title', 'Role Management')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Roles Table</h3>
                <div class="card-tools">
                    <button type="submit" data-toggle="modal" data-target="#newRole" class="btn btn-success btn-sm">
                        <i class="fas fa-plus fa-fw"></i>
                        <span class="d-none d-lg-inline">New Role</span>
                    </button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th style="width: 80%">Role name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $key => $role)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td><a href="{{ route('roles.show',$role->id) }}">{{ $role->name }}</a></td>
                            <td>
                                @can('role-edit')
                                <a class="btn btn-primary btn-xs" href="{{ route('roles.edit',$role->id) }}">
                                    <i class="fas fa-edit fa-fw"></i>
                                </a>
                                @endcan
                                @can('role-delete')
                                {!! Form::open(['method' => 'DELETE','route' => ['roles.destroy',
                                $role->id],'style'=>'display:inline']) !!}
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
                {{ $roles->links() }}
            </div>
        </div>
    </div>
</div>

@endsection