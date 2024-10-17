<div x-data="countryDropdown()" x-cloak class="ml-3 relative">
    <div>
        <button @click="open = !open; if (open) loadCountries()" type="button" class="flex items-center text-base font-medium text-gray-500 hover:text-gray-900" id="region-menu" aria-expanded="false" aria-haspopup="true">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418" />
            </svg>
            <span class="hidden md:inline" x-text="countryCode">{{ $countryCode }}</span>
            <svg class="ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </button>
    </div>
    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="z-50 origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="region-menu" style="max-height: 300px; overflow-y: auto;">
        <div class="px-4 py-2 text-xs text-gray-500">{{ __('Select Region') }}:</div>
        <div class="px-4 py-2">
            <input type="text" x-model="searchQuery" @input="searchCountries" placeholder="{{ __('Search') }}..." class="block w-full px-2 py-1 text-sm border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
        </div>
        <template x-if="filteredCountries.length > 0">
            <div>
                <template x-for="country in filteredCountries" :key="country.id">
                    <a :href="'/set/country/' + country.id" @click="setCountry(country)" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" x-text="country.name"></a>
                </template>
            </div>
        </template>
        <template x-if="filteredCountries.length === 0">
            <div class="px-4 py-2 text-sm text-gray-500">No results found</div>
        </template>
    </div>
</div>

@push('scripts')
<script>
function countryDropdown() {
    return {
        open: false,
        countries: [],
        filteredCountries: [],
        searchQuery: '',
        countryCode: '{{ $countryCode }}',
        loadCountries() {
            if (this.countries.length === 0) {
                fetch('/api/countries')
                    .then(response => response.json())
                    .then(data => {
                        this.countries = data;
                        this.filteredCountries = data;
                    })
                    .catch(error => console.error('Error fetching countries:', error));
            }
        },
        searchCountries() {
            this.filteredCountries = this.countries.filter(country => 
                country.name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                country.code.toLowerCase().includes(this.searchQuery.toLowerCase())
            );
        },
        setCountry(country) {
            this.countryCode = country.code;
            this.open = false;
        }
    };
}
</script>
@endpush