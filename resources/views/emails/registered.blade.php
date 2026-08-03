<x-emails.layout title="Welcome to Ceylon AG">
    <div class="greeting">Hello {{ $user->name }},</div>

    <p>Thank you for registering your business <strong>{{ $user->business_name }}</strong> with the Ceylon AG Agricultural Distribution Network.</p>

    <p>Your registration details have been received successfully and are currently pending administrative review and verification.</p>

    <table class="data-table">
        <tr>
            <td class="data-label">Business Name</td>
            <td class="data-val">{{ $user->business_name }}</td>
        </tr>
        <tr>
            <td class="data-label">NIC Number</td>
            <td class="data-val">{{ $user->nic }}</td>
        </tr>
        <tr>
            <td class="data-label">Phone Number</td>
            <td class="data-val">{{ $user->phone }}</td>
        </tr>
        <tr>
            <td class="data-label">District & Province</td>
            <td class="data-val">{{ $user->district }}, {{ $user->province }}</td>
        </tr>
        <tr>
            <td class="data-label">Account Status</td>
            <td class="data-val" style="color: #d97706;">Pending Verification</td>
        </tr>
    </table>

    <p>Once our management team approves your client partner account, you will receive an activation email granting full access to product allocation and inventory ordering features.</p>
</x-emails.layout>
