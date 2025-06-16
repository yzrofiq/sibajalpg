<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIBAJA LOGIN</title>

    <link rel="apple-touch-icon" sizes="180x180" href="{{ url('logo.png') }}"/>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ url('32x32.png') }}"/>
    <link rel="icon" type="image/png" sizes="16x16" href="{{ url('16x16.png') }}"/>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <link rel="stylesheet" href="{{ url('plugins/fontawesome-free/css/all.min.css') }}">

    <link rel="stylesheet" href="{{ url('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">

    <link rel="stylesheet" href="{{ url('dist/css/adminlte.min.css?v=3.2.0') }}">

    <script nonce="7aa6f487-d001-4f0d-b9b2-796d1bc4ec58">
    (function(w, d) {
        !function(a, e, t, r) {
            a.zarazData = a.zarazData || {},
            a.zarazData.executed = [],
            a.zaraz = {
                deferred: []
            },
            a.zaraz.q = [],
            a.zaraz._f = function(e) {
                return function() {
                    var t = Array.prototype.slice.call(arguments);
                    a.zaraz.q.push({
                        m: e,
                        a: t
                    })
                }
            };
            for (const e of ["track", "set", "ecommerce", "debug"])
                a.zaraz[e] = a.zaraz._f(e);
            a.addEventListener("DOMContentLoaded", (() => {
                var t = e.getElementsByTagName(r)[0],
                    z = e.createElement(r),
                    n = e.getElementsByTagName("title")[0];
                for (n && (a.zarazData.t = e.getElementsByTagName("title")[0].text), a.zarazData.x = Math.random(), a.zarazData.w = a.screen.width, a.zarazData.h = a.screen.height, a.zarazData.j = a.innerHeight, a.zarazData.e = a.innerWidth, a.zarazData.l = a.location.href, a.zarazData.r = e.referrer, a.zarazData.k = a.screen.colorDepth, a.zarazData.n = e.characterSet, a.zarazData.o = (new Date).getTimezoneOffset(), a.zarazData.q = []; a.zaraz.q.length;) {
                    const e = a.zaraz.q.shift();
                    a.zarazData.q.push(e)
                }
                z.defer = !0;
                for (const e of [localStorage, sessionStorage])
                    Object.keys(e).filter((a => a.startsWith("_zaraz_"))).forEach((t => {
                        try {
                            a.zarazData["z_" + t.slice(7)] = JSON.parse(e.getItem(t))
                        } catch {
                            a.zarazData["z_" + t.slice(7)] = e.getItem(t)
                        }
                    }));
                z.referrerPolicy = "origin",
                z.src = "/cdn-cgi/zaraz/s.js?z=" + btoa(encodeURIComponent(JSON.stringify(a.zarazData))),
                t.parentNode.insertBefore(z, t)
            }))
        }(w, d, 0, "script");
    })(window, document);
    </script>
</head>
<body class="hold-transition login-page ">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 pt-10">
                <img src="{{ url('banner-sibaja.jpg') }}" alt="Banner" class="w-100">
            </div>
        </div>
    </div>

    <div class="login-box" style="margin-top: -100px;">

        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="{{ url('/') }}" class="h1 d-flex justify-content-center align-items-center">
                    <img src="{{ url('logo.png') }}" alt="Logo SiBAJA" style="height: 50px;" class="mr-2">
                    <b>SIBAJA</b>
                </a>
            </div>
            <div class="card-body">

                <form action="" method="post">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" name="username" class="form-control" placeholder="Username" autofocus>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                        </div>

                    </div>
                </form>
                
            </div>

        </div>

    </div>

    <script src="{{ url('plugins/jquery/jquery.min.js') }}"></script>

    <script src="{{ url('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <script src="{{ url('dist/js/adminlte.min.js?v=3.2.0') }}"></script>
</body>
</html>

