<div class="md:w-2/5 w-full rounded overflow-hidden shadow-lg bg-white">
    @if($image)
        <img class="w-full h-48 object-cover" src="{{ $image }}" alt="{{ $title }}">
    @endif
    <div class="w-full px-6 py-4">
        <div class="font-bold text-xl mb-2">{{ $title }}</div>
        <p class="text-gray-700 text-base">
            {{ $content }}
        </p>
    </div>
</div>
