<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Trade Weekly Summarizer') }}
        </h2>
    </x-slot>
    
    <div>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>

    <div x-data="{ sidebarOpen: false }" class="flex h-screen bg-gray-200">
        <div :class="sidebarOpen ? 'block' : 'hidden'" @click="sidebarOpen = false" class="fixed inset-0 z-20 transition-opacity bg-black opacity-50 lg:hidden"></div>

        <div :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'" class="fixed inset-y-0 left-0 z-30 w-64 overflow-y-auto transition duration-300 transform bg-gray-900 lg:translate-x-0 lg:static lg:inset-0">

            <nav class="mt-10">
                <a class="flex items-center px-6 py-2 mt-4 text-gray-100 bg-gray-700 bg-opacity-25" href="#">
                    <i class="fa-solid fa-plus"></i>
                        
                    <span class="mx-3">New Note</span>
                </a>

                <a class="flex items-center px-6 py-2 mt-4 text-gray-500 hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100"
                    href="#">
                    <i class="fa-solid fa-list"></i>

                    <span class="mx-3">Note 1</span>
                </a>

                <a class="flex items-center px-6 py-2 mt-4 text-gray-500 hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100"
                    href="#">
                    <i class="fa-solid fa-list"></i>

                    <span class="mx-3">Note 2</span>
                </a>
                
                <a class="flex items-center px-6 py-2 mt-4 text-gray-500 hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100"
                    href="#">
                    <i class="fa-solid fa-list"></i>

                    <span class="mx-3">Note 3</span>
                </a>

            </nav>
        </div>
        <div class="flex flex-col flex-1 overflow-hidden">
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
                <div class="container px-6 py-8 mx-auto">
                    <h3 class="text-3xl font-medium text-gray-700">New Note</h3>

                    <div class="mt-8">

                    </div>

                    <div class="flex flex-col mt-8">
                        <div class="py-2 -my-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                            <div
                                class="inline-block min-w-full overflow-hidden align-middle border-b border-gray-200 shadow sm:rounded-lg">
                                <form class="w-full">
                                    <div class="flex flex-wrap -mx-3 mb-6">
                                      <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
                                          Title
                                        </label>
                                        <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-red-500 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white" id="grid-first-name" type="text" placeholder="Enter title of your note...">
                                        <p class="text-red-500 text-xs italic">Please fill out this field.</p>
                                      </div>
                                      <div class="w-full md:w-1/2 px-3">
                                        <label for="summary" class="block text-sm font-medium text-gray-700">
                                            Summary
                                        </label>
                                        <textarea 
                                            id="summary" 
                                            name="summary" 
                                            rows="4" 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm resize-none overflow-hidden"
                                            placeholder="Summary"
                                        ></textarea>

                                        {{-- BUTTONS --}}
                                        <div class="mt-6">  
                                            <a class="mr-2 px-6 py-2 min-w-[120px] text-center text-white bg-violet-600 border border-violet-600 rounded active:text-violet-500 hover:bg-transparent hover:text-violet-600 focus:outline-none focus:ring"
                                                href="/download">
                                                Summarize
                                            </a>
                                            
                                            <a class="mr-2 px-6 py-2 min-w-[120px] text-center text-violet-600 border border-violet-600 rounded hover:bg-violet-600 hover:text-white active:bg-indigo-500 focus:outline-none focus:ring"
                                                href="/download">
                                                Copy    
                                            </a>

                                            <a class="px-6 py-2 min-w-[120px] text-center text-violet-600 border border-violet-600 rounded hover:bg-violet-600 hover:text-white active:bg-indigo-500 focus:outline-none focus:ring"
                                                href="/download">
                                                Save    
                                            </a>
                                        </div>
                                    
                                      </div>
                                    </div>
                                    <div class="flex flex-wrap -mx-3 mb-6">
                                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                                            <label for="notes" class="block text-sm font-medium text-gray-700">
                                                Notes
                                            </label>
                                            <textarea 
                                                id="notes" 
                                                name="notes" 
                                                rows="4" 
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm resize-none overflow-hidden"
                                                placeholder="Notes"
                                            ></textarea>
                                        </div>
                                    </div>
                                  </form>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    </div>
</x-app-layout>