<!doctype html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

</head>
<body>
<p>尊敬的先生/女士：</p>
<p>我们收到了一项请求，要求通过您的电子邮件地址访问您的 社团管理系统 帐号 {{ $user_name }}。您的 验证码为：</p>
<h1 style="text-align: center">{{ $code }}</h1>

<p>如果您并未请求此验证码，则可能是他人正在尝试访问以下 社团管理系统 帐号：{{ $user_name }}。<span style="font-weight: bold">请勿将此验证码转发给或提供给任何人。</span></p>
<p>此致</p>


</body>
</html>
