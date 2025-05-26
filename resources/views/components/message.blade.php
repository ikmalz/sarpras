@if(Session::has('success'))
<div id="popup-message" class="fixed top-8 left-1/2 transform -translate-x-1/2 -translate-y-full transition-all duration-500 ease-out bg-white border border-black/10 px-16 py-4 rounded-lg shadow-lg flex items-center gap-3 z-50">
    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
    </svg>
    {{ Session::get('success') }}
</div>
@endif

@if(Session::has('error'))
<div id="popup-message" class="fixed top-8 left-1/2 transform -translate-x-1/2 -translate-y-full transition-all duration-500 ease-out bg-white border border-red-500 px-5 py-3 rounded-lg shadow-lg flex items-center gap-3 z-50">
    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
    </svg>
    {{ Session::get('error') }}
</div>
@endif

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var popup = document.getElementById('popup-message');
        if(popup) {
            setTimeout(function() {
                popup.classList.remove('-translate-y-full');
            }, 100);

            setTimeout(function(){
                popup.classList.add('-translate-y-full');
            }, 3000);

            setTimeout(function(){
                popup.remove();
            }, 3500);
        }
    })
</script>