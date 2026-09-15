<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Admin</title>
  <!-- base:css -->
  <link rel="stylesheet" href="{{url('adminassets/login/assets/vendors/typicons/typicons.css')}}">
  <link rel="stylesheet" href="{{url('adminassets/login/assets/vendors/css/vendor.bundle.base.css')}}">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="{{url('adminassets/login/assets/css/vertical-layout-light/style.css')}}">
  <!-- endinject -->
   <link rel="shortcut icon" href="{{url('adminassets/login/assets/image/favicoinicon.png')}}" />
</head>

<body>
 <div class="login-page-bg">
  <div class="login-wrap">

    <div class="login-left">
      <div class="geo-circle" style="width:280px;height:280px;top:-80px;left:-80px;"></div>
      <div class="geo-circle" style="width:160px;height:160px;bottom:60px;right:-40px;"></div>
      <div class="geo-circle" style="width:80px;height:80px;bottom:160px;left:40px;"></div>
      <div class="dot-grid"></div>

      <div>
        <div class="brand-tag">Control Panel</div>
        <div class="brand-name">Admin<span>Panel</span></div>
        <div class="left-tagline">Manage your store, products,<br>orders and analytics — all in one place.</div>
      </div>

      <div class="stat-row">
        <div><div class="stat-num">99.9%</div><div class="stat-label">Uptime</div></div>
        <div class="divider-vert"></div>
        <div><div class="stat-num">256-bit</div><div class="stat-label">Encryption</div></div>
        <div class="divider-vert"></div>
        <div><div class="stat-num">Secure</div><div class="stat-label">Session</div></div>
      </div>
    </div>

    <div class="login-right">
      <div class="login-eyebrow">Welcome back</div>
      <div class="login-title">Sign in to continue</div>
      <div class="login-sub">Enter your credentials to access the dashboard.</div>

      <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="field-wrap">
          <label class="field-label">Email address</label>
          <div class="field-input-wrap">
            <svg class="field-icon" viewBox="0 0 16 16" fill="none">
              <rect x="1" y="3" width="14" height="10" rx="2" stroke="currentColor" stroke-width="1.2"/>
              <path d="M1 5l7 5 7-5" stroke="currentColor" stroke-width="1.2"/>
            </svg>
            <input class="field-input" type="email" name="email" placeholder="admin@example.com" value="{{ old('email') }}" />
          </div>
          @error('email')<small style="color:red;font-size:11px;">{{ $message }}</small>@enderror
        </div>

        <div class="field-wrap">
          <label class="field-label">Password</label>
          <div class="field-input-wrap">
            <svg class="field-icon" viewBox="0 0 16 16" fill="none">
              <rect x="3" y="7" width="10" height="8" rx="1.5" stroke="currentColor" stroke-width="1.2"/>
              <path d="M5 7V5a3 3 0 016 0v2" stroke="currentColor" stroke-width="1.2"/>
              <circle cx="8" cy="11" r="1" fill="currentColor"/>
            </svg>
            <input class="field-input" type="password" name="password" placeholder="••••••••" />
          </div>
          @error('password')<small style="color:red;font-size:11px;">{{ $message }}</small>@enderror
        </div>

        <div class="remember-row">
          <input type="checkbox" name="remember" id="remember" />
          <label for="remember">Keep me signed in</label>
        </div>

        <button type="submit" class="sign-in-btn">Sign in</button>
      </form>

      <div class="footer-note">Protected by <span>SSL encryption</span> · Admin access only</div>
    </div>

  </div>
</div>
  <!-- container-scroller -->
  <!-- base:js -->
  <script src="{{url('adminassets/login/assets/vendors/js/vendor.bundle.base.js')}}"></script>
  <!-- endinject -->
  <!-- inject:js -->
  <script src="{{url('adminassets/login/assets/js/off-canvas.js')}}"></script>
  <script src="{{url('adminassets/login/assets/js/hoverable-collapse.js')}}"></script>
  <script src="{{url('adminassets/login/assets/js/template.js')}}"></script>
  <script src="{{url('adminassets/login/assets/js/settings.js')}}"></script>
  <script src="{{url('adminassets/login/assets/js/todolist.js')}}"></script>
  <!-- endinject -->
   <style>
* { box-sizing: border-box; margin: 0; padding: 0; }

.login-page-bg {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f4f3ff;
    padding: 20px;
}
.login-wrap {
    width: 100%;
    max-width: 860px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 8px 40px rgba(0,0,0,0.1);
}
.login-left {
    background: #1a1a2e;
    padding: 48px 40px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    position: relative;
    overflow: hidden;
}
.geo-circle {
    position: absolute;
    border-radius: 50%;
    border: 0.5px solid rgba(255,255,255,0.08);
}
.dot-grid {
    position: absolute;
    bottom: 0; right: 0;
    width: 120px; height: 120px;
    opacity: 0.06;
    background-image: radial-gradient(circle, white 1px, transparent 1px);
    background-size: 10px 10px;
}
.brand-tag { font-size: 11px; letter-spacing: 2px; color: rgba(255,255,255,0.35); text-transform: uppercase; }
.brand-name { font-size: 24px; font-weight: 600; color: #fff; margin-top: 8px; }
.brand-name span { color: #7c6af7; }
.left-tagline { font-size: 13px; color: rgba(255,255,255,0.45); line-height: 1.7; margin-top: 12px; }
.stat-row { display: flex; gap: 24px; margin-top: auto; padding-top: 40px; }
.stat-num { font-size: 20px; font-weight: 600; color: #fff; }
.stat-label { font-size: 11px; color: rgba(255,255,255,0.35); margin-top: 2px; }
.divider-vert { width: 0.5px; background: rgba(255,255,255,0.1); }

.login-right {
    padding: 48px 40px;
    background: #fff;
    display: flex;
    flex-direction: column;
    justify-content: center;
}
.login-eyebrow { font-size: 11px; letter-spacing: 2px; color: #aaa; text-transform: uppercase; margin-bottom: 6px; }
.login-title { font-size: 22px; font-weight: 600; color: #1a1a2e; margin-bottom: 4px; }
.login-sub { font-size: 13px; color: #888; margin-bottom: 28px; }

.field-wrap { margin-bottom: 16px; }
.field-label { font-size: 12px; color: #666; margin-bottom: 6px; display: block; }
.field-input-wrap { position: relative; }
.field-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 14px; height: 14px; opacity: 0.35; color: #333; }
.field-input {
    width: 100%;
    padding: 10px 12px 10px 36px;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    font-size: 13px;
    background: #f9f9f9;
    color: #1a1a2e;
    outline: none;
    transition: border-color 0.2s;
}
.field-input:focus { border-color: #7c6af7; background: #fff; }

.remember-row { display: flex; align-items: center; gap: 8px; margin-bottom: 24px; }
.remember-row input[type=checkbox] { accent-color: #7c6af7; width: 14px; height: 14px; }
.remember-row label { font-size: 12px; color: #888; }

.sign-in-btn {
    width: 100%;
    padding: 12px;
    background: #1a1a2e;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    letter-spacing: 0.5px;
    transition: opacity 0.2s;
}
.sign-in-btn:hover { opacity: 0.85; }
.footer-note { font-size: 11px; color: #bbb; text-align: center; margin-top: 20px; }
.footer-note span { color: #7c6af7; }

@media (max-width: 600px) {
    .login-wrap { grid-template-columns: 1fr; }
    .login-left { display: none; }
}
</style>
</body>

</html>
