@extends('template.index')

@section('content')
<div id="wrapper">

    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ url('/home') }}">Admin</a>
        </div>
        <!-- /.navbar-header -->

        <ul class="nav navbar-top-links navbar-right navbar-collapse">

            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                </a>
                <ul class="dropdown-menu dropdown-user">
                    <li class="divider"></li>
                    <li><a href="{{ url('/logout') }}"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                    </li>
                </ul>
                <!-- /.dropdown-user -->
            </li>
            <!-- /.dropdown -->
        </ul>
    </nav>

    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">{{ $admin->name }} ({{$admin->email}})</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Салон
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <table width="100%" class="table table-striped table-bordered table-hover" >
                            <thead>
                            <tr>
                                <th>Наименование</th>
                                <th>Email</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr class="odd gradeX">
                                <td>{{$admin->salon->name}}</td>
                                <td>{{$admin->salon->mail}}</td>
                            </tr>

                            </tbody>
                        </table>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->

                <div class="panel panel-default">
                    <div class="panel-heading" data-toggle="collapse" data-target="#usersT" aria-expanded="false" aria-controls="collapseExample">
                        Пользователи
                    </div>
                <div class="panel-body">
                    <div class="table-responsive collapse" id="usersT">
                        <table class="display table table-hover" id="example" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>Имя</th>
                                <th>Фамилия</th>
                                <th>Телефон</th>
                                <th>Балл</th>
                                <th>Кол-во заказов</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($admin->users as $item)
                                    <tr>
                                        <td>{{$item->firstname }}</td>
                                        <td>{{$item->lastname }}</td>
                                        <td>{{$item->telephone }}</td>
                                        <td>{{$item->ball }}</td>
                                        <td>{{$item->cnt }}</td>
                                        <td><button type="button" class="btn btn-default" onclick="updateUserBtn({{$item->id}});">Править</button></td>
                                    </tr>
                                    @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive -->
                </div>
                <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-6">
                <div class="panel panel-default">
                    <div class="panel-heading" data-toggle="collapse" data-target="#bidsT" aria-expanded="false" aria-controls="collapseExample">
                        Заказы
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="table-responsive collapse" id="bidsT">
                            <table class="display table table-hover" id="example_users" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>Имя</th>
                                    <th>Фамилия</th>
                                    <th>Телефон</th>
                                    <th>Балл</th>
                                    <th>Услуга</th>
                                    <th>Статус</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($admin->bids as $item)
                                    @if($item->status == 'Закончено')
                                        <tr class="warning">
                                    @elseif($item->status == 'Одобрено')
                                        <tr class="info">
                                    @elseif($item->status == 'Принято')
                                        <tr class="success">
                                    @else
                                        <tr>
                                    @endif
                                        <td>{{$item->firstname }}</td>
                                        <td>{{$item->lastname }}</td>
                                        <td>{{$item->telephone }}</td>
                                        <td>{{$item->ball }}</td>
                                        <td>{{$item->name }}</td>
                                        <td>{{$item->status }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
            </div>
            <div class="col-lg-6">
                <div class="panel panel-default">
                    <div class="panel-heading" data-toggle="collapse" data-target="#bidsNoRegT" aria-expanded="false" aria-controls="collapseExample">
                        Заказы незарегистрированных пользователей
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="table-responsive collapse" id="bidsNoRegT">
                            <table class="display table table-hover" id="example2" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>Имя</th>
                                    <th>Телефон</th>
                                    <th>Услуга</th>
                                    <th>Дата</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($admin->useBids as $item)
                                        <tr>
                                        <td>{{$item->firstname }}</td>
                                        <td>{{$item->telephone }}</td>
                                        <td>{{$item->name }}</td>
                                        <td>{{$item->date }}</td>

                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
            </div>
        </div>
        <!-- /.row -->
    </div>
    <!-- /#page-wrapper -->
</div>



<div class="modal fade" id="updateUserModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            {{ Form::open(['url' => '/updateUser',  'method' => 'post']) }}
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Правка пользователя</h4>
            </div>
            <div class="modal-body">

                {{ Form::text('firstname', old('firstname'), ['class' => 'form-control',
                                   'placeholder' => 'Имя', 'id' => 'fnameModal']) }}

                {{ Form::text('lastname', old('lastname'), ['class' => 'form-control',
                                   'placeholder' => 'Фамиля', 'id' => 'lnameModal']) }}

                {{ Form::text('telephone', old('telephone'), ['class' => 'form-control',
                                   'placeholder' => 'Телефон', 'id' => 'telephoneModal']) }}

                {{ Form::hidden('id', old('id'), ['class' => 'form-control', 'id' => 'idModal']) }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                {{Form::submit('Ок', ['class' => 'btn btn-success'])}}
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection

@section('js')

    function updateUserBtn(id){
        $.get("./updateUser", {
            id: id,
        },
        function(data){
            console.log(data);
            $('#fnameModal').val(data.fname);
            $('#lnameModal').val(data.lname);
            $('#telephoneModal').val(data.telephone);
            $('#idModal').val(data.id);
            $('#updateUserModal').modal('show');
        }, "json");
    }
    $(document).ready(function() {


    $('#example').DataTable( {
        "language": {
        "lengthMenu": "Показать _MENU_ ",
        "zeroRecords": "Нет заказов",
        "info": "Показаны с _PAGE_ по _PAGES_ страницы",
        "infoEmpty": "Нет заказов",
        "search": "Искать: ",
        "paginate": {
            "previous": "предыдущий",
            "next": "cледующий"
        },
        "infoFiltered": "(filtered from _MAX_ total records)"
        }
    } );

    $('#example2').DataTable( {
    "language": {
    "lengthMenu": "Показать _MENU_ ",
    "zeroRecords": "Нет заказов",
    "info": "Показаны с _PAGE_ по _PAGES_ страницы",
    "infoEmpty": "Нет заказов",
    "search": "Искать: ",
    "paginate": {
        "previous": "предыдущий",
        "next": "cледующий"
    },
    "infoFiltered": "(filtered from _MAX_ total records)"
    }
    } );

    $('#example_users').DataTable( {
    "language": {
    "lengthMenu": "Показать _MENU_ ",
    "zeroRecords": "Нет заказов",
    "info": "Показаны с _PAGE_ по _PAGES_ страницы",
    "infoEmpty": "Нет заказов",
    "search": "Искать: ",
    "paginate": {
        "previous": "предыдущий",
        "next": "cледующий"
    },
    "infoFiltered": "(filtered from _MAX_ total records)"
    }
    } );

    } );
@endsection
