@extends('layouts.admin')

@section('title', 'Edit Service - Admin Panel')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <a href="{{ route('admin.services.index') }}" class="text-sm font-semibold text-[#7a2a2a] hover:text-[#5c1a1a]">Back to Services</a>
        <h1 class="text-xl font-semibold text-[#2c2420] mt-2">Edit Service</h1>
        <p class="text-sm text-[#6b5e57] mt-1">Update the service information used on the dashboard.</p>
    </div>

    @if($errors->any())
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="max-w-3xl overflow-hidden rounded-xl border border-[#e5e0db] bg-white">
        <form method="POST" action="{{ route('admin.services.update', $service) }}" class="p-6 space-y-5" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-[#8b7e76] mb-2">Title</label>
                <input type="text" name="title" value="{{ old('title', $service->title) }}" class="w-full rounded-lg border border-[#e5e0db] px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#7a2a2a]/20 focus:border-[#7a2a2a]" required>
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-[#8b7e76] mb-2">Description</label>
                <textarea name="description" rows="4" class="w-full rounded-lg border border-[#e5e0db] px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#7a2a2a]/20 focus:border-[#7a2a2a]" required>{{ old('description', $service->description) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-[#8b7e76] mb-2">Image</label>
                <input type="file" name="image" accept="image/*" class="w-full rounded-lg border border-[#e5e0db] px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#7a2a2a]/20 focus:border-[#7a2a2a]">

                @php
                    $img = $service->image_url;
                    $imgSrc = $img && preg_match('/^https?:\/\//i', $img) ? $img : ($img ? asset('storage/' . $img) : null);
                @endphp

                @if($imgSrc)
                    <div class="mt-3 overflow-hidden rounded-lg border border-[#e5e0db] bg-[#faf8f5]">
                        <img src="{{ $imgSrc }}" alt="" class="w-full h-48 object-cover" onerror="this.style.display='none'" />
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-[#8b7e76] mb-2">Route name</label>
                    <input type="text" name="route_name" value="{{ old('route_name', $service->route_name) }}" class="w-full rounded-lg border border-[#e5e0db] px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#7a2a2a]/20 focus:border-[#7a2a2a]" placeholder="mhc">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-[#8b7e76] mb-2">Order</label>
                    <input type="number" name="order" value="{{ old('order', $service->order) }}" class="w-full rounded-lg border border-[#e5e0db] px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#7a2a2a]/20 focus:border-[#7a2a2a]" required>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <input id="is_active" type="checkbox" name="is_active" value="1" {{ old('is_active', $service->is_active) ? 'checked' : '' }} class="h-4 w-4 rounded border-[#e5e0db] text-[#7a2a2a] focus:ring-[#7a2a2a]/20">
                <label for="is_active" class="text-sm text-[#2c2420]">Active</label>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('admin.services.index') }}" class="inline-flex items-center rounded-lg border border-[#e5e0db] bg-white px-4 py-2 text-sm font-semibold text-[#2c2420] hover:bg-[#faf8f5]">Cancel</a>
                <button type="submit" class="inline-flex items-center rounded-lg bg-[#7a2a2a] px-4 py-2 text-sm font-semibold text-white hover:bg-[#5c1a1a]">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
