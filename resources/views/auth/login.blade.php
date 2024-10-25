<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('AdminLTE') }}/dist/img/logo-unj.png">
    <title>Presensi Elektro | Log in</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('AdminLTE') }}/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('AdminLTE') }}/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('AdminLTE') }}/dist/css/adminlte.min.css?v=3.2.0">
    <script nonce="6a1c82fc-03c3-4287-a987-b063c07ce20d">
        try {
            (function(w,d){!function(c,d,e,f){if(c.zaraz)console.error("zaraz is loaded twice");else{c[e]=c[e]||{};c[e].executed=[];c.zaraz={deferred:[],listeners:[]};c.zaraz._v="5714";c.zaraz.q=[];c.zaraz._f=function(g){return async function(){var h=Array.prototype.slice.call(arguments);c.zaraz.q.push({m:g,a:h})}};for(const i of["track","set","debug"])c.zaraz[i]=c.zaraz._f(i);c.zaraz.init=()=>{var j=d.getElementsByTagName(f)[0],k=d.createElement(f),l=d.getElementsByTagName("title")[0];l&&(c[e].t=d.getElementsByTagName("title")[0].text);c[e].x=Math.random();c[e].w=c.screen.width;c[e].h=c.screen.height;c[e].j=c.innerHeight;c[e].e=c.innerWidth;c[e].l=c.location.href;c[e].r=d.referrer;c[e].k=c.screen.colorDepth;c[e].n=d.characterSet;c[e].o=(new Date).getTimezoneOffset();if(c.dataLayer)for(const p of Object.entries(Object.entries(dataLayer).reduce(((q,r)=>({...q[1],...r[1]})),{})))zaraz.set(p[0],p[1],{scope:"page"});c[e].q=[];for(;c.zaraz.q.length;){const s=c.zaraz.q.shift();c[e].q.push(s)}k.defer=!0;for(const t of[localStorage,sessionStorage])Object.keys(t||{}).filter((v=>v.startsWith("_zaraz_"))).forEach((u=>{try{c[e]["z_"+u.slice(7)]=JSON.parse(t.getItem(u))}catch{c[e]["z_"+u.slice(7)]=t.getItem(u)}}));k.referrerPolicy="origin";k.src="/cdn-cgi/zaraz/s.js?z="+btoa(encodeURIComponent(JSON.stringify(c[e])));j.parentNode.insertBefore(k,j)};["complete","interactive"].includes(d.readyState)?zaraz.init():c.addEventListener("DOMContentLoaded",zaraz.init)}}(w,d,"zarazData","script");})(window,document)
        } catch(e) {
            throw fetch("/cdn-cgi/zaraz/t"),e;
        };
    </script>
    <style>
        body {
            background: url("{{ asset('img/gedungunj.png') }}") no-repeat center center fixed;
            background-size: cover;
        }
    </style>
</head>
<body class="hold-transition login-page">
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="{{ route('login') }}" class="h1"><b>SI</b>KIAR</a>
            </div>
            <div class="card-header text-center">
                <img src="{{ asset('img') }}/unj.png" alt="unj" style="width: 200px; height: auto; display: block; margin-left: auto; margin-right: auto;">
            </div>
            <div class="card-body">
                <p class="login-box-msg">Selamat Datang di SIKIAR</p>
                <p class="login-box-msg">Sistem Presensi Berbasis QR</p>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('login') }}" method="post">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control" placeholder="Email" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember" name="remember">
                                <label for="remember">
                                    Remember Me
                                </label>
                            </div>
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                        </div>
                    </div>
                </form>
                <p class="mb-0">
                    <a href="{{ route('register') }}" class="text-center">Register Account</a>
                </p>
            </div>
        </div>
    </div>

    <script src="{{ asset('AdminLTE') }}/plugins/jquery/jquery.min.js"></script>
    <script src="{{ asset('AdminLTE') }}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('AdminLTE') }}/dist/js/adminlte.min.js?v=3.2.0"></script>
</body>
</html>
