@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default panel-demo">
                    <div class="panel-heading">
                        {{ trans('aktiv8me.forms.resend.resend') }}
                    </div>

                    <div class="panel-body">
                        <div class="alert alert-info">
                            <p>
                                {{ trans('aktiv8me.forms.resend.info', ['max' => config('aktiv8me.max_tokens') - 1]) }}
                            </p>
                        </div>

                        <form class="form-horizontal" method="POST" action="{{ route('register.resend') }}">
                            {{ csrf_field() }}

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
                                           required
                                           autofocus>

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-8 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ trans('aktiv8me.forms.resend.submit') }}
                                    </button>

                                    <a class="btn btn-link" href="{{ route('register') }}">
                                        {{ trans('aktiv8me.forms.resend.back') }}
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
