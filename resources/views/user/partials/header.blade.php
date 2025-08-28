<header class="fixed top-0 left-0 right-0 z-50 bg-white rounded-b-[40px] shadow-lg px-6 py-4 max-w-7xl mx-auto"
    x-data="{ openMenu: null, mobileOpen: false }" @click.away="openMenu = null">
    <div class="flex items-center justify-between">
        {{-- Logo --}}
        <a href="{{ url('user/dashboard') }}">
            <img src="{{ asset('uploads/logo/hsbl.png') }}" alt="HSBL Riau Pos Logo" class="h-14 w-14 rounded-md" />
        </a>

        {{-- Toggle Button (mobile) --}}
        <div class="md:hidden">
            <button @click="mobileOpen = !mobileOpen" class="relative w-8 h-8 focus:outline-none">
                {{-- Hamburger icon --}}
                <svg x-show="!mobileOpen" x-cloak class="w-8 h-8 absolute right-0 top-0" fill="none"
                    stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                {{-- Close (X) icon --}}
                <svg x-show="mobileOpen" x-cloak class="w-8 h-8 absolute right-0 top-0" fill="none"
                    stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Navigation --}}
        <nav :class="mobileOpen ? 'block' : 'hidden'"
            class="w-full md:flex md:items-center md:space-x-6 text-sm font-normal md:w-auto mt-4 md:mt-0">
            <ul class="flex flex-col md:flex-row gap-y-2 md:gap-y-0 md:gap-x-6">
                @php
                $menu = [
                    ['label' => 'Home', 'url' => 'user/dashboard'],
                    ['label' => 'News', 'url' => 'user/news'],
                    ['label' => 'Schedules & Results', 'url' => route('user.schedule_result')],
                    ['label' => 'Statistics', 'url' => '/statistics_0301'],
                    ['label' => 'Gallery', 'url' => '#', 'submenu' => [
                        ['label' => 'Videos', 'url' => 'user/videos'],
                        ['label' => 'Photos', 'url' => '/photos_0501'],
                    ]],
                    ['label' => 'Riau Pos - Honda HSBL History', 'url' => '/Riau-Pos-Honda-HSBL-History_0601'],
                    ['label' => 'Developer', 'url' => '/Developer_0601'],
                ];
                @endphp

                @foreach($menu as $index => $item)
                    @if(isset($item['submenu']))
                        <li class="relative">
                            <button @click="openMenu === {{ $index }} ? openMenu = null : openMenu = {{ $index }}" type="button"
                                class="hover:underline hover:text-[#71BBB2] flex items-center gap-1 w-full md:w-auto"
                                :class="openMenu === {{ $index }} ? 'font-bold text-teal-600' : ''">
                                {{ $item['label'] }}
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <ul x-show="openMenu === {{ $index }}" x-transition x-cloak
                                class="bg-white md:absolute md:left-0 md:mt-2 w-max rounded shadow-md z-50">
                                @foreach($item['submenu'] as $sub)
                                    <li>
                                        <a href="{{ url($sub['url']) }}"
                                            class="block px-4 py-2 text-xs whitespace-nowrap hover:bg-gray-100 hover:text-[#71BBB2]">
                                            {{ $sub['label'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @else
                        <li>
                            <a href="{{ url($item['url']) }}"
                                class="hover:underline hover:text-[#71BBB2] @if(request()->is(ltrim($item['url'], '/'))) font-bold text-teal-600 @endif">
                                {{ $item['label'] }}
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </nav>
    </div>
</header>

{{-- Alpine.js untuk interaktivitas --}}
<script src="//unpkg.com/alpinejs" defer></script>

{{-- Tailwind tambahan untuk x-cloak --}}
<style>
    [x-cloak] {
        display: none !important;
    }
</style>
