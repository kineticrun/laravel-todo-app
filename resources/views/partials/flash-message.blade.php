@if (session('success'))
    <div id="flash-message" class="relative bg-green-100 border border-green-400 text-emerald-700 px-4 py-3 rounded mb-4"
        role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
        <button type="button" onclick="document.getElementById('flash-message').style.display='none'"
            class="absolute top-0 right-0 mt-2 mr-3 text-emerald-500 hover:text-emerald-600 font-bold text-2xl focus:outline-none">
            &times;
        </button>
    </div>
@endif
