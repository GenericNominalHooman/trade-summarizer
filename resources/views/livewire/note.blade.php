<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Trade Summarizer') }}
    </h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div>
                    <div x-data="{ sidebarOpen: false }" class="flex h-screen">
                        <div :class="sidebarOpen ? 'block' : 'hidden'" @click="sidebarOpen = false" class="fixed inset-0 z-20 transition-opacity bg-black opacity-50 lg:hidden"></div>
                
                        <!-- Hamburger button(mobile) -->
                        <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden inline-flex items-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 hover:bg-opacity-25 focus:outline-none bg-gray-700 focus:text-white transition-colors duration-200">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{'hidden': sidebarOpen, 'inline-flex': !sidebarOpen }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{'hidden': !sidebarOpen, 'inline-flex': sidebarOpen }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                        <!-- Trading Notes Lists Sidebar -->
                        <div :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'" class="fixed inset-y-0 left-0 z-30 w-64 overflow-y-auto transition duration-300 transform bg-gray-900 lg:translate-x-0 lg:static lg:inset-0">
                
                            <nav class="mt-10">
                                <a class="flex items-center px-6 py-2 mt-4 text-gray-100 bg-gray-700 bg-opacity-25" href="#">
                                    <i class="fa-solid fa-plus"></i>
                                    <button wire:click="updateIsCreateNote(true)" class="mx-3">New Note</button>
                                </a>
                
                                <!-- Listing out all of user's prior note -->
                                @if ($exisitingNote)
                                    @foreach ($exisitingNote as $note)
                                        <a class="flex items-center px-6 py-2 mt-4 text-gray-500 hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100"
                                        href="#">
                                            <i class="fa-solid fa-list"></i>
                                                
                                            <button wire:click="loadNote({{ $note }})" class="mx-3">{{ $note->title }}</button>
                                        </a>
                                    @endforeach
                                @endif
                
                            </nav>
                        </div>
                        <div class="flex flex-col flex-1 overflow-hidden">
                            <main class="flex-1 overflow-x-hidden overflow-y-auto">
                                @if ($is_new_note)
                                    <!-- New Note Form -->
                                    <div class="container px-6 py-8 mx-auto">
                                        <h3 class="text-3xl font-medium text-gray-700">{{ __('New Note') }}</h3>
                
                                        <div class="mt-8">
                
                                        </div>
                
                                        <div class="flex flex-col mt-8">
                                            <div class="py-2 -my-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                                                <div
                                                    class="inline-block min-w-full overflow-hidden align-middle border-b border-gray-200 shadow sm:rounded-lg p-4">
                                                    <form class="w-full">
                                                        <div class="flex flex-wrap -mx-3 mb-6">
                                                            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                                                                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="title">
                                                                    Title
                                                                </label>
                                                                <input wire:model="new_title" class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white" type="text" placeholder="Enter title">
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Chart Image Field -->
                                                        <div class="flex items-center justify-center w-full mb-2">
                                                            <label for="new-dropzone-file" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                                                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                                    <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                                                    </svg>
                                                                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload charts images</span> or drag and drop</p>
                                                                    <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG only</p>
                                                                </div>
                                                                <input wire:model="new_chart_image" id="new-dropzone-file" type="file" class="hidden" accept="image/*" />
                                                            </label>
                                                        </div>
                                                        
                                                        <!-- Image Preview -->
                                                        @if ($new_chart_image)
                                                        <div class="mb-4">
                                                            <p class="text-sm font-medium text-gray-700 mb-2">Image Preview:</p>
                                                            <img src="{{ $new_chart_image->temporaryUrl() }}" alt="Chart Preview" class="max-w-full h-auto max-h-64 rounded-lg shadow-md">
                                                        </div>
                                                        @endif

                                                        <div class="flex flex-wrap -mx-3 mb-6">
                                                            <div class="w-full px-3">
                                                                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="note">
                                                                    Trading Note
                                                                </label>
                                                                <textarea wire:model="new_note" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" rows="6" placeholder="Enter your trading notes"></textarea>
                                                            </div>
                                                        </div>

                                                        <div class="flex flex-wrap -mx-3 mb-6">
                                                            <div class="w-full px-3">
                                                                <button type="button" wire:click="generateSummary" class="bg-gray-700 text-white font-bold py-2 px-4 rounded hover:bg-opacity-25">
                                                                    Generate AI Summary
                                                                </button>
                                                            </div>
                                                        </div>
                
                                                        <div class="flex flex-wrap -mx-3 mb-6">
                                                            <div class="w-full px-3">
                                                                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="summary">
                                                                    AI Generated Summary
                                                                </label>
                                                                <textarea wire:model="new_summary" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" rows="12" placeholder="AI-generated summary will appear here" ></textarea>
                                                            </div>
                                                        </div>
                
                                                        <div class="flex flex-wrap -mx-3 mb-6">
                                                            <div class="w-full px-3">
                                                                <button wire:click="save" type="button" class="bg-white hover:bg-gray-100 text-gray-700 font-bold py-2 px-4 rounded border border-gray-700">
                                                                    Save Note
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    @if ($note)
                                        <!-- Existing Note Form -->
                                        <div class="container px-6 py-8 mx-auto">
                                            <h3 class="text-3xl font-medium text-gray-700">{{ $selected_title }}</h3>
                
                                            <div class="mt-8">
                
                                            </div>
                
                                            <div class="flex flex-col mt-8">
                                                <div class="py-2 -my-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                                                    <div
                                                        class="inline-block min-w-full overflow-hidden align-middle border-b border-gray-200 shadow sm:rounded-lg p-4">
                                                        <form class="w-full">
                                                            <div class="flex flex-wrap -mx-3 mb-6">
                                                                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                                                                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="title">
                                                                        Title
                                                                    </label>
                                                                    <input wire:model="selected_title" class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white" type="text" placeholder="Enter title">
                                                                </div>
                                                            </div>
                                                            
                                                            <!-- Chart Image Field -->
                                                            <div class="flex items-center justify-center w-full mb-2">
                                                                <label for="edit-dropzone-file" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                                                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                                        <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                                                        </svg>
                                                                        <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload charts images</span> or drag and drop</p>
                                                                        <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG only</p>
                                                                    </div>
                                                                    <input wire:model="chart_image" id="edit-dropzone-file" type="file" class="hidden" accept="image/*" />
                                                                </label>
                                                            </div>
                                                            
                                                            <!-- Image Preview -->
                                                            @if ($chart_image)
                                                            <div class="mb-4">
                                                                <p class="text-sm font-medium text-gray-700 mb-2">Image Preview:</p>
                                                                @if (is_string($chart_image))
                                                                    <img src="{{ Storage::url($chart_image) }}" alt="Chart" class="max-w-full h-auto max-h-64 rounded-lg shadow-md">
                                                                @else
                                                                    <img src="{{ $chart_image->temporaryUrl() }}" alt="Chart Preview" class="max-w-full h-auto max-h-64 rounded-lg shadow-md">
                                                                @endif
                                                            </div>
                                                            @endif

                                                            <div class="flex flex-wrap -mx-3 mb-6">
                                                                <div class="w-full px-3">
                                                                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="note">
                                                                        Trading Note
                                                                    </label>
                                                                    <textarea wire:model="selected_note" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" rows="6" placeholder="Enter your trading notes"></textarea>
                                                                </div>
                                                            </div>
                
                                                            <div class="flex flex-wrap -mx-3 mb-6">
                                                                <div class="w-full px-3">
                                                                    <button type="button" wire:click="generateSummary" class="bg-gray-700 text-white font-bold py-2 px-4 rounded hover:bg-opacity-25">
                                                                        Generate AI Summary
                                                                    </button>
                                                                </div>
                                                            </div>
                
                                                            <div class="flex flex-wrap -mx-3 mb-6">
                                                                <div class="w-full px-3">
                                                                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="summary">
                                                                        AI Generated Summary
                                                                    </label>
                                                                    <textarea wire:model="selected_summary" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" rows="12" placeholder="AI-generated summary will appear here" ></textarea>
                                                                </div>
                                                            </div>
                
                                                            <div class="flex flex-wrap -mx-3 mb-6">
                                                                <div class="w-full p-3 flex justify-begin">
                                                                    <button wire:click="update" type="button" class="bg-white hover:bg-gray-100 text-gray-700 font-bold py-2 px-4 rounded border border-gray-700">
                                                                        Update Note
                                                                    </button>
                                                                    <button wire:click="delete" type="button" class="ml-2 bg-white hover:bg-gray-100 text-gray-700 font-bold py-2 px-4 rounded border border-gray-700">
                                                                        Delete Note
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </main>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>