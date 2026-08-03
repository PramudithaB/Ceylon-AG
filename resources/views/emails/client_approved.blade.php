<x-emails.layout title="Account Approved">
    <div class="greeting">Congratulations {{ $user->name }}!</div>

    @if($status === 'approved')
        <p>We are pleased to inform you that your Ceylon AG partner account for <strong>{{ $user->business_name }}</strong> has been officially <strong>Approved and Activated</strong> by system administration.</p>

        <p>You can now sign in to access product assignments, record retail sales, submit payment slips, and track inventory health.</p>

        <div class="btn-container">
            <a href="{{ $loginUrl }}" class="btn">Sign In To Dashboard</a>
        </div>
    @else
        <p>Your Ceylon AG partner account status for <strong>{{ $user->business_name }}</strong> has been updated to: <strong style="color: #e11d48;">{{ strtoupper($status) }}</strong>.</p>
        <p>If you believe this is an error, please contact your Ceylon AG regional area manager.</p>
    @endif
</x-emails.layout>
