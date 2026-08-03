<x-emails.layout title="Reset Password">
    <div class="greeting">Hello {{ $user->name }},</div>

    <p>You are receiving this email because we received a password reset request for your Ceylon AG account.</p>

    <div class="btn-container">
        <a href="{{ $url }}" class="btn">Reset Account Password</a>
    </div>

    <p style="font-size: 12px; color: #64748b;">This password reset link will expire in {{ $count }} minutes.</p>
    <p style="font-size: 12px; color: #64748b;">If you did not request a password reset, no further action is required.</p>
</x-emails.layout>
