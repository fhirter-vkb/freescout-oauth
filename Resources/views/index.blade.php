<form class="form-horizontal margin-top margin-bottom" method="POST" action="" id="oauth_form">
    {{ csrf_field() }}

    <div class="form-group{{ $errors->has('settings.oauth.active') ? ' has-error' : '' }} margin-bottom-10">
        <label for="oauth.active" class="col-sm-2 control-label">{{ __('Active') }}</label>

        <div class="col-sm-6">
            <input id="oauth.active" type="checkbox" class=""
                   name="settings[oauth.active]"
                   @if (old('settings[oauth.active]', $settings['oauth.active']) == 'on') checked="checked" @endif
            />
        </div>
    </div>

    <div class="form-group{{ $errors->has('settings.oauth.client_id') ? ' has-error' : '' }} margin-bottom-10">
        <label for="oauth.client_id" class="col-sm-2 control-label">{{ __('Client ID') }}</label>

        <div class="col-sm-6">
            <input id="oauth.client_id" type="text" class="form-control input-sized-lg"
                   name="settings[oauth.client_id]" value="{{ old('settings.oauth.client_id', $settings['oauth.client_id']) }}">
            @include('partials/field_error', ['field'=>'settings.oauth.client_id'])
        </div>
    </div>
    <div class="form-group{{ $errors->has('settings.oauth.client_secret') ? ' has-error' : '' }} margin-bottom-10">
        <label for="oauth.client_secret" class="col-sm-2 control-label">{{ __('Client Secret') }}</label>

        <div class="col-sm-6">
            <input id="oauth.client_secret" type="text" class="form-control input-sized-lg"
                   name="settings[oauth.client_secret]" value="{{ old('settings.oauth.client_secret', $settings['oauth.client_secret']) }}">
        </div>
    </div>
    <div class="form-group{{ $errors->has('settings.oauth.auth_url') ? ' has-error' : '' }} margin-bottom-10">
        <label for="oauth.auth_url" class="col-sm-2 control-label">{{ __('Authorization Endpoint URL') }}</label>

        <div class="col-sm-6">
            <input id="oauth.auth_url" type="text" class="form-control input-sized-lg"
                   name="settings[oauth.auth_url]" value="{{ old('settings.oauth.auth_url', $settings['oauth.auth_url']) }}">
        </div>
    </div>
    <div class="form-group{{ $errors->has('settings.oauth.token_url') ? ' has-error' : '' }} margin-bottom-10">
        <label for="oauth.token_url" class="col-sm-2 control-label">{{ __('Token Endpoint URL') }}</label>

        <div class="col-sm-6">
            <input id="oauth.token_url" type="text" class="form-control input-sized-lg"
                   name="settings[oauth.token_url]" value="{{ old('settings.oauth.token_url', $settings['oauth.token_url']) }}">
        </div>
    </div>
    <div class="form-group{{ $errors->has('settings.oauth.user_url') ? ' has-error' : '' }} margin-bottom-10">
        <label for="oauth.user_url" class="col-sm-2 control-label">{{ __('User Info Endpoint URL') }}</label>

        <div class="col-sm-6">
            <input id="oauth.user_url" type="text" class="form-control input-sized-lg"
                   name="settings[oauth.user_url]" value="{{ old('settings.oauth.user_url', $settings['oauth.user_url']) }}">
        </div>
    </div>

    <div class="form-group">
        <label for="oauth.user_url" class="col-sm-2 control-label">{{ __('OAuth callback URL') }}</label>
        <a href="{{ route('oauth_callback')  }}">{{ route('oauth_callback')  }}</a>
    </div>

    <div class="form-group margin-top margin-bottom">
        <div class="col-sm-6 col-sm-offset-2">
            <button type="submit" class="btn btn-primary">
                {{ __('Save') }}
            </button>
        </div>
    </div>
</form>