@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default panel-demo">
                    <div class="panel-heading">
                        {{ trans('aktiv8me.forms.register.register') }}
                    </div>

                    <div class="panel-body">
                        <form class="form-horizontal" method="POST" action="{{ route('register') }}">
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="name" class="col-md-4 control-label">
                                    {{ trans('aktiv8me.forms.register.name') }}
                                </label>

                                <div class="col-md-6">
                                    <input id="name"
                                           type="text"
                                           class="form-control"
                                           name="name"
                                           value="{{ old('name') }}"
                                           required
                                           autofocus>

                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email" class="col-md-4 control-label">
                                    {{ trans('aktiv8me.forms.common.email') }}
                                </label>

                                <div class="col-md-6">
                                    <input id="email"
                                           type="email"
                                           class="form-control"
                                           name="email"
                                           value="{{ old('email') }}"
                                           required>

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="password" class="col-md-4 control-label">
                                    {{ trans('aktiv8me.forms.common.password') }}
                                </label>

                                <div class="col-md-6">
                                    <input id="password"
                                           type="password"
                                           class="form-control"
                                           name="password"
                                           required>

                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password-confirm" class="col-md-4 control-label">
                                    {{ trans('aktiv8me.forms.register.confirm') }}
                                </label>

                                <div class="col-md-6">
                                    <input id="password-confirm"
                                           type="password"
                                           class="form-control"
                                           name="password_confirmation"
                                           required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ trans('aktiv8me.forms.register.register') }}
                                    </button>

                                    @if (config('aktiv8me.max_tokens'))
                                        <a class="btn btn-link" href="{{ route('register.resend') }}">
                                            {{ trans('aktiv8me.forms.register.activate') }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
