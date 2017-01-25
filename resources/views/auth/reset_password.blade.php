@extends('layouts.app')

@section('content')
<div class="reset-password-page">
  <div class="container">
      <div class="header">
        <div class="app-content">
          @include('shared.top_bar')
        </div>
      </div>
  </div>
  <div class="container">
    <div class="app-content">
      @include('shared.reset_password_component')
      <h1 class="reset-header">忘记密码</h1>
      <reset-password-form>
      </reset-password-form>
    </div>
  </div>
</div>

@endsection
