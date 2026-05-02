@if (Session::has('success'))
    <div class="mb-6 flex items-center p-4 text-emerald-800 border-t-4 border-emerald-300 bg-emerald-50 rounded-lg shadow-sm animate-in fade-in slide-in-from-top-2 duration-300" role="alert">
        <svg class="flex-shrink-0 w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
        </svg>
        <div class="ml-3 text-sm font-semibold">
            {{ Session::get('success') }}
        </div>
    </div>
@endif

@if (Session::has('error'))
    <div class="mb-6 flex items-center p-4 text-rose-800 border-t-4 border-rose-300 bg-rose-50 rounded-lg shadow-sm animate-in fade-in slide-in-from-top-2 duration-300" role="alert">
        <svg class="flex-shrink-0 w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
        </svg>
        <div class="ml-3 text-sm font-semibold">
            {{ Session::get('error') }}
        </div>
    </div>
@endif

@if ($errors->any())
    <div class="mb-6 flex flex-col p-4 text-rose-800 border-t-4 border-rose-300 bg-rose-50 rounded-lg shadow-sm animate-in fade-in slide-in-from-top-2 duration-300" role="alert">
        <div class="flex items-center mb-2">
            <svg class="flex-shrink-0 w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
            </svg>
            <div class="ml-3 text-sm font-bold">
                Please correct the following errors:
            </div>
        </div>
        <ul class="ml-8 list-disc text-xs font-medium">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

