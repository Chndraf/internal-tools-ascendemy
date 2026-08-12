<nav class="bg-surface dark:bg-surface shadow-sm sticky top-0 z-50">
    <div class="flex justify-between items-center w-full px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto h-20">
        <!-- Brand -->
        <a href="/" class="text-2xl font-bold text-primary flex items-center gap-2">
            <span class="material-symbols-outlined">widgets</span>
            OmniTools
        </a>
        <!-- Desktop Links -->
        <div class="hidden md:flex items-center gap-6">
            <!-- Menu Tools mengarah ke home -->
            <a href="{{ route('home') }}" class="text-on-surface hover:text-primary font-medium transition-colors">Tools</a>

            @if(!request()->is('/'))
                <a href="#cara-kerja" class="text-on-surface hover:text-primary font-medium transition-colors">
                    Cara Kerja
                </a>
            @endif
            <a class="text-on-surface-variant dark:text-on-surface-variant font-medium hover:text-primary dark:hover:text-primary transition-colors duration-200" href="#faq">FAQ</a>
        </div>
        <!-- Mobile Menu Toggle -->
        <button aria-label="Menu" class="md:hidden text-on-surface">
            <span class="material-symbols-outlined" data-icon="menu">menu</span>
        </button>
    </div>
</nav>