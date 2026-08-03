<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Product Categories</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400">Organize catalog items into distinct agricultural and business categories</p>
            </div>
            <a href="{{ route('admin.products.index') }}" class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition-all">
                <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Products
            </a>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Add Category Form -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 shadow-sm space-y-4">
                <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100 border-b border-slate-100 dark:border-slate-800 pb-3">Create New Category</h2>

                <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <x-input-label for="name" :value="__('Category Name')" />
                        <x-text-input id="name" class="block mt-1 w-full text-xs" type="text" name="name" :value="old('name')" required placeholder="e.g. Fertilizer, Seeds, Machinery" />
                        <x-input-error :messages="$errors->get('name')" class="mt-1" />
                    </div>

                    <div>
                        <x-input-label for="description" :value="__('Description (Optional)')" />
                        <textarea id="description" name="description" rows="3" class="block mt-1 w-full text-xs rounded-xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 focus:border-emerald-500 focus:ring-emerald-500 text-slate-800 dark:text-slate-200" placeholder="Brief category description...">{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-1" />
                    </div>

                    <div class="flex items-center space-x-2">
                        <input type="checkbox" id="is_active" name="is_active" value="1" checked class="rounded text-emerald-600 focus:ring-emerald-500 border-slate-300 dark:border-slate-700">
                        <label for="is_active" class="text-xs text-slate-700 dark:text-slate-300">Active Category</label>
                    </div>

                    <x-primary-button class="w-full justify-center bg-emerald-600 hover:bg-emerald-500">
                        Save Category
                    </x-primary-button>
                </form>
            </div>
        </div>

        <!-- Categories Listing Table -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
                <div class="p-4 border-b border-slate-100 dark:border-slate-800">
                    <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">All Categories ({{ $categories->count() }})</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-950/50 border-b border-slate-200 dark:border-slate-800 text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                <th class="py-3.5 px-6">Category Name</th>
                                <th class="py-3.5 px-6">Slug</th>
                                <th class="py-3.5 px-6">Products</th>
                                <th class="py-3.5 px-6">Status</th>
                                <th class="py-3.5 px-6 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 text-xs">
                            @forelse($categories as $category)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition-colors">
                                    <td class="py-4 px-6">
                                        <div class="font-bold text-slate-900 dark:text-white">{{ $category->name }}</div>
                                        @if($category->description)
                                            <div class="text-[11px] text-slate-400 dark:text-slate-500 truncate max-w-xs mt-0.5">{{ $category->description }}</div>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 font-mono text-[11px] text-slate-500 dark:text-slate-400">
                                        {{ $category->slug }}
                                    </td>
                                    <td class="py-4 px-6">
                                        <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                            {{ $category->products_count }} Products
                                        </span>
                                    </td>
                                    <td class="py-4 px-6">
                                        @if($category->is_active)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300">Active</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-right">
                                        @if($category->products_count === 0)
                                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Delete this empty category?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-rose-600 hover:text-rose-700 font-semibold text-[11px]">Delete</button>
                                            </form>
                                        @else
                                            <span class="text-slate-300 dark:text-slate-700 text-[11px]">In use</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-slate-400 dark:text-slate-500">
                                        No categories defined yet. Add your first category using the form.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
