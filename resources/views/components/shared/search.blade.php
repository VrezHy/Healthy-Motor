<form action="{{ url()->current() }}" method="GET" class="flex items-center gap-2">
    <input 
        type="text" 
        name="search" 
        value="{{ request('search') }}" 
        placeholder="Cari data..." 
        class="border px-3 py-2 rounded-lg"
    >
    <button type="submit" class="bg-indigo-400 text-white px-4 py-2 rounded-lg">Cari</button>
    
    @if(request('search'))
        <a href="{{ url()->current() }}" class="text-gray-500">Reset</a>
    @endif
</form>