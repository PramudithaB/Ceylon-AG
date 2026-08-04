<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('My Official Price Quotations') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-slate-200">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Formal Quotations</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Review formal estimations, download PDF documents, and print official quotations issued to your account.</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-700">
                        <thead class="bg-slate-50 text-slate-500 uppercase font-semibold border-b border-slate-200">
                            <tr>
                                <th class="px-4 py-3">Quotation #</th>
                                <th class="px-4 py-3">Date</th>
                                <th class="px-4 py-3">Valid Until</th>
                                <th class="px-4 py-3">Grand Total</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($quotations as $q)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-4 py-3.5 font-bold text-emerald-700">
                                        <a href="{{ route('client.quotations.show', $q->id) }}" class="hover:underline">
                                            {{ $q->quotation_number }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3.5 text-slate-600">{{ $q->quotation_date->format('M d, Y') }}</td>
                                    <td class="px-4 py-3.5 text-slate-600">{{ $q->expiry_date->format('M d, Y') }}</td>
                                    <td class="px-4 py-3.5 font-extrabold text-slate-900">LKR {{ number_format($q->grand_total, 2) }}</td>
                                    <td class="px-4 py-3.5">
                                        @if($q->status === 'accepted')
                                            <span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-800 font-bold text-[10px] rounded-full">Accepted</span>
                                        @elseif($q->status === 'sent')
                                            <span class="px-2.5 py-0.5 bg-blue-100 text-blue-800 font-bold text-[10px] rounded-full">Issued</span>
                                        @elseif($q->status === 'expired')
                                            <span class="px-2.5 py-0.5 bg-amber-100 text-amber-800 font-bold text-[10px] rounded-full">Expired</span>
                                        @else
                                            <span class="px-2.5 py-0.5 bg-slate-100 text-slate-700 font-bold text-[10px] rounded-full">{{ ucfirst($q->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3.5 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('client.quotations.show', $q->id) }}" class="px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-lg transition-colors">
                                                View
                                            </a>
                                            <a href="{{ route('client.quotations.pdf', $q->id) }}" class="px-3 py-1 bg-slate-800 hover:bg-slate-900 text-white font-semibold text-xs rounded-lg transition-colors">
                                                PDF
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-slate-500">
                                        No price quotations issued to your account yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $quotations->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
