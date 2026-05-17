<article class="group rounded-2xl border border-slate-100 bg-white p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg">
    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-teal-50 text-teal-500">
        {!! $icon !!}
    </div>

    <h3 class="mt-5 text-xl font-bold text-slate-900">{{ $title }}</h3>
    <p class="mt-3 text-sm leading-7 text-gray-500">{{ $description }}</p>

    <a href="{{ $href ?? '#' }}" class="mt-5 inline-flex items-center gap-2 rounded-full border border-teal-100 px-4 py-2 text-sm font-semibold text-teal-600 transition hover:border-teal-500 hover:bg-teal-50">
        Learn More
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5l7 7-7 7" />
        </svg>
    </a>
</article>