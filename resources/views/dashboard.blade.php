<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- {{ __("How to use trade summarizer") }} -->
                    <p class="text-lg font-semibold">How to use trade summarizer</p>
                    <div class="w-full flex flex-row flex-wrap p-4 gap-4 justify-center">
                        <livewire:card 
                            title="1. CLICK ON NOTE IN THE NAVIGATION BAR" 
                            content="This will redirect you to the note summary creation page" 
                            image="{{ Storage::url('images/no1_navbar.png') }}" 
                        />
                        <livewire:card 
                            title="2. ENTER TITLE AND YOUR WEEKLY TRADING NOTES IN THE FORM" 
                            content="Title will be used to saved your note as and your note will be used to generate the summary" 
                            image="{{ Storage::url('images/no2_note_insert.png') }}" 
                        />
                        <livewire:card 
                            title="3. CLICK ON GENERATE SUMMARY BUTTON" 
                            content="Trading summary will be generated based on the notes you entered" 
                            image="{{ Storage::url('images/no3_generate_summary.png') }}" 
                        />
                        <livewire:card 
                            title="4. CLICK ON SAVE BUTTON" 
                            content="If you're satisfied with the summary, you can save it" 
                            image="{{ Storage::url('images/no4_save_summary.png') }}" 
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
