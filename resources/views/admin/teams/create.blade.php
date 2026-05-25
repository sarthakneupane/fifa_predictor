@extends('layouts.app')
@section('title', 'Add Team – Admin')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-600 mb-6">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-300 transition-colors">Admin</a>
        <span>/</span>
        <a href="{{ route('admin.teams.index') }}" class="hover:text-gray-300 transition-colors">Teams</a>
        <span>/</span>
        <span class="text-gray-400">Add Team</span>
    </nav>

    <h1 class="font-display text-5xl text-white tracking-wide mb-8">Add Team</h1>

    {{-- Validation errors --}}
    @if($errors->any())
    <div class="bg-red-900/30 border border-red-700/50 rounded-xl p-4 mb-6">
        <p class="text-red-400 text-xs font-heading uppercase tracking-wider mb-2">Please fix the following:</p>
        <ul class="text-red-300 text-sm space-y-1">
            @foreach($errors->all() as $error)
                <li>• {{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.teams.store') }}"
          enctype="multipart/form-data"
          class="glass-card rounded-2xl p-6 sm:p-8 space-y-6">
        @csrf

        {{-- Name --}}
        <div>
            <label for="name" class="block text-gray-400 text-xs font-heading uppercase tracking-wider mb-2">
                Country / Team Name *
            </label>
            <input id="name" type="text" name="name"
                   value="{{ old('name') }}"
                   required maxlength="100"
                   placeholder="e.g. Brazil"
                   class="w-full bg-white/5 border @error('name') border-red-600 @else border-white/10 @enderror focus:border-fifa-red rounded-xl px-4 py-3 text-white text-sm outline-none transition-colors placeholder-gray-700">
            @error('name')
                <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
            @enderror
        </div>

        {{-- Short name + Flag emoji (side by side) --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="short_name" class="block text-gray-400 text-xs font-heading uppercase tracking-wider mb-2">
                    3-Letter Code *
                </label>
                <input id="short_name" type="text" name="short_name"
                       value="{{ old('short_name') }}"
                       required maxlength="3"
                       placeholder="e.g. BRA"
                       style="text-transform: uppercase"
                       class="w-full bg-white/5 border @error('short_name') border-red-600 @else border-white/10 @enderror focus:border-fifa-red rounded-xl px-4 py-3 text-white text-sm outline-none transition-colors placeholder-gray-700 font-heading tracking-widest">
                @error('short_name')
                    <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="flag_emoji" class="block text-gray-400 text-xs font-heading uppercase tracking-wider mb-2">
                    Flag Emoji
                </label>
                <div class="flex items-center gap-3">
                    <input id="flag_emoji" type="text" name="flag_emoji"
                           value="{{ old('flag_emoji') }}"
                           maxlength="10"
                           placeholder="🇧🇷"
                           class="flex-1 bg-white/5 border @error('flag_emoji') border-red-600 @else border-white/10 @enderror focus:border-fifa-red rounded-xl px-4 py-3 text-white text-2xl outline-none transition-colors placeholder-gray-700">
                    <span id="emoji-preview" class="text-3xl w-10 text-center leading-none select-none">
                        {{ old('flag_emoji', '🏳️') }}
                    </span>
                </div>
                <p class="text-gray-700 text-xs mt-1.5">Copy flag emoji from <a href="https://emojipedia.org/flags" target="_blank" class="text-gray-500 hover:text-gray-300 underline">Emojipedia</a></p>
                @error('flag_emoji')
                    <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Group + Confederation (side by side) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="group_id" class="block text-gray-400 text-xs font-heading uppercase tracking-wider mb-2">
                    Group *
                </label>
                <select id="group_id" name="group_id" required
                        class="w-full bg-white/5 border @error('group_id') border-red-600 @else border-white/10 @enderror focus:border-fifa-red rounded-xl px-4 py-3 text-white text-sm outline-none transition-colors">
                    <option value="">Select group…</option>
                    @foreach($groups as $group)
                        <option value="{{ $group->id }}" {{ old('group_id') == $group->id ? 'selected' : '' }}>
                            {{ $group->name }} ({{ $group->teams->count() }}/4 teams)
                        </option>
                    @endforeach
                </select>
                @error('group_id')
                    <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="confederation" class="block text-gray-400 text-xs font-heading uppercase tracking-wider mb-2">
                    Confederation
                </label>
                <select id="confederation" name="confederation"
                        class="w-full bg-white/5 border @error('confederation') border-red-600 @else border-white/10 @enderror focus:border-fifa-red rounded-xl px-4 py-3 text-white text-sm outline-none transition-colors">
                    <option value="">Select…</option>
                    @foreach(['UEFA', 'CONMEBOL', 'AFC', 'CAF', 'CONCACAF', 'OFC'] as $conf)
                        <option value="{{ $conf }}" {{ old('confederation') === $conf ? 'selected' : '' }}>
                            {{ $conf }}
                        </option>
                    @endforeach
                </select>
                @error('confederation')
                    <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Flag image upload --}}
        <div>
            <label class="block text-gray-400 text-xs font-heading uppercase tracking-wider mb-2">
                Flag Image <span class="text-gray-700 normal-case font-body">(optional, JPG/PNG, max 2MB)</span>
            </label>
            <div class="flex items-start gap-4">
                {{-- Drop zone --}}
                <label for="flag"
                       class="flex-1 flex flex-col items-center justify-center gap-3 border-2 border-dashed border-white/10 hover:border-fifa-red/40 rounded-xl px-4 py-8 cursor-pointer transition-colors group">
                    <div id="upload-preview" class="hidden">
                        <img id="preview-img" class="max-h-16 rounded border border-fifa-border" alt="Preview">
                    </div>
                    <div id="upload-placeholder" class="flex flex-col items-center gap-2 text-center">
                        <svg class="w-8 h-8 text-gray-700 group-hover:text-gray-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <div>
                            <span class="text-gray-500 text-sm group-hover:text-gray-300 transition-colors">Click to upload</span>
                            <p class="text-gray-700 text-xs mt-0.5">PNG, JPG up to 2MB</p>
                        </div>
                    </div>
                    <input id="flag" type="file" name="flag" accept="image/*" class="sr-only">
                </label>
            </div>
            <p class="text-gray-700 text-xs mt-2">If no image is uploaded, the emoji flag will be used instead.</p>
            @error('flag')
                <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
            @enderror
        </div>

        {{-- Preview card --}}
        <div class="bg-white/[0.03] border border-white/5 rounded-xl p-4">
            <p class="text-gray-600 text-xs font-heading uppercase tracking-wider mb-3">Live Preview</p>
            <div class="flex items-center gap-3">
                <span id="preview-emoji" class="text-4xl leading-none select-none">{{ old('flag_emoji', '🏳️') }}</span>
                <div>
                    <div id="preview-name" class="font-heading text-white uppercase tracking-wider">
                        {{ old('name', 'Team Name') }}
                    </div>
                    <div id="preview-code" class="text-gray-600 text-xs font-heading tracking-widest mt-0.5">
                        {{ old('short_name', 'CODE') }} · {{ old('confederation', 'CONF') }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-4 pt-2 border-t border-fifa-border">
            <button type="submit"
                    class="flex-1 bg-fifa-red hover:bg-red-700 text-white font-heading uppercase tracking-wider py-3.5 rounded-xl transition-all hover:scale-[1.01] text-sm">
                ➕ Add Team
            </button>
            <a href="{{ route('admin.teams.index') }}"
               class="px-5 py-3.5 border border-white/10 hover:border-white/30 text-gray-400 hover:text-white rounded-xl text-sm font-heading uppercase tracking-wider transition-colors">
                Cancel
            </a>
        </div>
    </form>

</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {

    // ── Emoji preview ──────────────────────────────────────────────────────
    $('#flag_emoji').on('input', function () {
        const val = $(this).val().trim();
        const display = val || '🏳️';
        $('#emoji-preview').text(display);
        $('#preview-emoji').text(display);
    });

    // ── Name + code live preview ───────────────────────────────────────────
    $('#name').on('input', function () {
        const val = $(this).val().trim() || 'Team Name';
        $('#preview-name').text(val);
    });

    $('#short_name').on('input', function () {
        const val = $(this).val().toUpperCase().trim() || 'CODE';
        $(this).val(val);
        updateCode();
    });

    $('#confederation').on('change', function () {
        updateCode();
    });

    function updateCode() {
        const code = $('#short_name').val().toUpperCase() || 'CODE';
        const conf = $('#confederation').val() || 'CONF';
        $('#preview-code').text(code + ' · ' + conf);
    }

    // ── Flag image upload preview ──────────────────────────────────────────
    $('#flag').on('change', function () {
        const file = this.files[0];
        if (!file) return;

        if (file.size > 2 * 1024 * 1024) {
            alert('File is too large. Maximum size is 2MB.');
            $(this).val('');
            return;
        }

        const reader = new FileReader();
        reader.onload = function (e) {
            $('#preview-img').attr('src', e.target.result);
            $('#upload-preview').removeClass('hidden');
            $('#upload-placeholder').addClass('hidden');
        };
        reader.readAsDataURL(file);
    });

});
</script>
@endpush
