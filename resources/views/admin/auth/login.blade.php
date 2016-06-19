<!DOCTYPE html>
<html>
<head>
  <title>后台登录</title>
  <link rel="stylesheet" type="text/css" href="/dash-assets/semantic.min.css">
  <style type="text/css">
    body {
      background-color: #DADADA;
    }
    body > .grid {
      height: 100%;
    }
    .image {
      margin-top: -100px;
    }
    .column {
      max-width: 450px;
    }
  </style>
</head>

<body>
<div class="ui middle aligned center aligned grid">
  <div class="column">
    <h2 class="ui teal image header">
      <div class="content">
        管理后台登录
      </div>
    </h2>
    {!! Form::open(['route' => 'admin.auth.login', 'class' => 'ui large form']) !!}

      <div class="ui stacked segment">
        <div class="field">
          <div class="ui left icon input">
            <i class="user icon"></i>
            <input type="text" name="username" placeholder="用户名" required>
          </div>
        </div>
        <div class="field">
          <div class="ui left icon input">
            <i class="lock icon"></i>
            <input type="password" name="password" placeholder="密码" required>
          </div>
        </div>
        <button class="ui fluid large teal submit button" type="submit">登陆</button>
      </div>
      @if ( $errors->any() )
      <div class="ui message">
        {{$errors->first()}}
      </div>
      @endif
    {!! Form::close() !!}
  </div>
</div>
</body>
<script type="text/javascript" src="/js/jquery-3.0.0.min.js"></script>
<script src="/dash-assets/semantic.min.js"></script>
</html>