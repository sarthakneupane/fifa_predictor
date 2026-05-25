<!-- Toast Component -->
<div id="{{ $id ?? 'ajax-toast' }}" 
     data-type="{{ $type ?? 'success' }}"
     class="fixed top-0 left-1/2 transform -translate-x-1/2 -translate-y-full text-white px-8 py-4 rounded-xl shadow-xl text-lg font-semibold opacity-0 transition-all duration-500 z-50">
    <span id="{{ $id ?? 'ajax-toast-message' }}">{{ $message ?? '' }}</span>
</div>
