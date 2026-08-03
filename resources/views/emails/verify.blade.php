<x-emails.layout title="Verify Email Address">
    <div class="greeting">Hello {{ $user->name }},</div>

    <p>Please click the button below to verify your email address and activate security credentials for your Ceylon AG account.</p>

    <div class="btn-container">
        <a href="{{ $url }}" class="btn">Verify Email Address</a>
    </div>

    <p style="font-size: 12px; color: #64748b; margin-top: 24px;">If you did not create an account with Ceylon AG, no further action is required.</p>
</x-emails.layout>
