<span>Hello,</span>
<h4>{{ $user->name }}</h4>
<p>Use this one time OTP to verify your existence.
    <b>{{ $otp }}</b>
</p>

<p>Thanks,<br>
    {{ config('app.name') }}</p>
