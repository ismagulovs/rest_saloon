@extends('template.index')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Вход</h3>
                    </div>
                    <div class="panel-body">
                        {!! Form::open(['url' => '/login',  'method' => 'post']) !!}
                            <fieldset>
                                <div class="form-group">
                                    {{ Form::email('email', old('email'), ['class' => 'form-control',
                                    'placeholder' => 'E-mail',  'autofocus']) }}
                                </div>
                                <div class="form-group">
                                    {{ Form::password('password', ['class' => 'form-control',
                                    'placeholder' => 'Пароль',]) }}
                                </div>
                                <!-- Change this to a button or input when using this as a form -->
                                {{Form::submit('Войти', ['class' => 'btn btn-lg btn-success btn-block'])}}
                            </fieldset>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
