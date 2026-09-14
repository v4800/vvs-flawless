<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Avis clients · VVS FLAWLESS</title>
    <meta name="robots" content="noindex,follow">
    <style>
        *{box-sizing:border-box}body{margin:0;background:#080908;color:#f6f1df;font:16px/1.65 system-ui,sans-serif}main{max-width:1080px;margin:auto;padding:32px 20px}a{color:#f9d56c}h1,h2{font-family:Georgia,serif;line-height:1.2}h1{font-size:clamp(32px,5vw,52px)}.panel{border:1px solid #514329;border-radius:20px;background:linear-gradient(130deg,#17140e,#090909);padding:24px;margin:24px 0}.muted{color:#b5b5b9}.gold{color:#f9d56c}.grid{display:grid;grid-template-columns:1fr 1fr;gap:20px}label{display:block;margin-top:16px}input,select,textarea,button{font:inherit;border-radius:8px;padding:12px}input,select,textarea{width:100%;color:inherit;background:#090909;border:1px solid #777}textarea{min-height:140px;resize:vertical}input[type=checkbox]{width:auto}button{margin-top:20px;background:#f9d56c;color:#151109;border:0;cursor:pointer;font-weight:700}:focus-visible{outline:3px solid #f9d56c;outline-offset:3px}.trap{position:absolute;left:-10000px}.error{color:#ffb4b4}.review-body{white-space:pre-wrap;overflow-wrap:anywhere}.nav{display:flex;justify-content:space-between;gap:24px}@media(max-width:700px){.grid{grid-template-columns:1fr}}
    </style>
</head>
<body><main>
    <nav class="nav"><a href="/watches">← Collection</a><span>VVS FLAWLESS</span></nav>
    @if(session('review_status'))<p class="panel" role="status">{{ session('review_status') }}</p>@endif
    @if($errors->any())<div class="panel error" role="alert"><p>Veuillez corriger les champs suivants :</p><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    @yield('content')
</main></body>
</html>
