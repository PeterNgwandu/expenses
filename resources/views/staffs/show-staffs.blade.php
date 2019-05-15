@extends('layout.app')

@section('content')
<div class="mdk-drawer-layout js-mdk-drawer-layout mydata" data-fullbleed data-push data-has-scrolling-region>
    <div class="mdk-drawer-layout__content mdk-header-layout__content--scrollable">

        <div class="container">


            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-earnings">
                        <div class="card-header bg-faded">
                            <div class="row align-items-center">
                                <div class="col-lg-6">
                                    <h4 class="card-title">Registered Staffs</h4>
                                </div>
                            </div>
                        </div>

                        <div class="card-group">
                            <div class="card card-body bg-light ">
                              <div class="col-lg-12">
                                <table id="data-table" class="table table-sm table-bordered table-striped table-dark mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="text-center">#ID</th>
                                            <th scope="col" class="text-center">Name</th>
                                            <th scope="col" class="text-center">Email</th>
                                            <th scope="col" class="text-center">Department</th>
                                            <th scope="col" class="text-center">Staff Level</th>
                                            <th scope="col" class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       @foreach($users as $user)

                                        <tr>
                                            <td scope="col" class="align-middle text-center">{{ $user->id }}</td>
                                            <td scope="col" class="align-middle text-center">{{ $user->username ? $user->username : 'Admin' }}</td>
                                            <td scope="col" class="align-middle text-center">{{ $user->email }}</td>
                                            <td scope="col" class="align-middle text-center">{{ $user->department }}</td>
                                            <td scope="col" class="align-middle text-center">{{ $user->stafflevel }}</td>
                                            <td scope="col" class="align-middle text-center">
                                              <a class="delete-user" href="#" id="{{$user->id}}">
                                                <span>
                                                     <i style="cursor: pointer;" class="material-icons md-10 align-middle mb-1 text-danger">delete_forever</i>
                                        				</span>
                                              </a>
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
        </div>

    </div>
</div>

@endsection
