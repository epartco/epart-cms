{{-- resources/views/components/admin/media-library-modal.blade.php --}}
@props(['id' => 'media-library-modal'])

<div x-data="mediaLibraryModal()"
     x-show="showModal"
     x-on:open-media-modal.window="openModal($event.detail)"
     x-on:keydown.escape.window="closeModal()"
     id="{{ $id }}"
     class="fixed inset-0 z-[10000] overflow-y-auto" {{-- Increased z-index --}}
     style="display: none;">

    {{-- Removed z-index from overlay to avoid conflict with potential TinyMCE overlay --}}
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        {{-- This element is to trick the browser into centering the modal contents. --}}
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div x-show="showModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full"
             @click.away="closeModal()">

            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Media Library
                        </h3>
                        <div class="mt-4">
                            {{-- Search/Filter Bar (Optional) --}}
                            {{-- <input type="text" placeholder="Search media..." class="w-full border-gray-300 rounded-md shadow-sm mb-4"> --}}

                            {{-- Media Grid --}}
                            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-4 max-h-96 overflow-y-auto border p-4 rounded" x-ref="mediaGrid">
                                <template x-if="loading">
                                    <p class="col-span-full text-center text-gray-500">Loading media...</p>
                                </template>
                                <template x-if="!loading && mediaItems.length === 0">
                                     <p class="col-span-full text-center text-gray-500">No media found.</p>
                                </template>
                                <template x-for="item in mediaItems" :key="item.id">
                                    {{-- Added .stop to prevent click event from bubbling up to @click.away --}}
                                    <div @click.stop="selectMedia(item)"
                                         class="cursor-pointer border-2 border-transparent hover:border-indigo-500 rounded p-1 transition"
                                         :class="{ 'border-indigo-500': selectedMedia && selectedMedia.id === item.id }">
                                        <img :src="item.thumbnail_url" :alt="item.alt_text || item.name" class="w-full h-24 object-cover rounded">
                                        <p class="text-xs text-center truncate mt-1" x-text="item.name"></p>
                                    </div>
                                </template>
                            </div>
                            {{-- Pagination (Optional) --}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button @click="insertMedia()"
                        :disabled="!selectedMedia"
                        type="button"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                    Insert Selected
                </button>
                <button @click="closeModal()"
                        type="button"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function mediaLibraryModal() {
        return {
            showModal: false,
            loading: false,
            mediaItems: [],
            selectedMedia: null,
            callback: null, // TinyMCE callback function

            openModal(detail) {
                this.callback = detail.callback;
                this.showModal = true;
                this.loadMedia();
                this.selectedMedia = null; // Reset selection
            },
            closeModal() {
                this.showModal = false;
                this.mediaItems = []; // Clear items
                this.callback = null;
            },
            loadMedia() {
                this.loading = true;
                // Use direct URL path with debugging options
                fetch('/admin/media/list-json', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(errorData => {
                                throw new Error(errorData.error || 'Network response was not ok');
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Check if there's an error message in the response
                        if (data.error) {
                            throw new Error(data.error);
                        }
                        
                        // Access the media items array from the paginated response
                        this.mediaItems = data.data || []; // Use data.data, default to empty array if missing
                        // TODO: Implement pagination controls if needed (load more, page numbers)
                    })
                    .catch(error => {
                        console.error('Error fetching media:', error);
                        
                        // Log more detailed error information
                        if (error.stack) {
                            console.error('Error stack:', error.stack);
                        }
                        
                        // Show error message in the grid instead of using alert
                        this.mediaItems = [];
                        this.$refs.mediaGrid.innerHTML = `
                            <div class="col-span-full p-4 text-center">
                                <div class="text-red-500 mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <p class="text-red-600 font-medium">미디어 라이브러리를 불러오는 중 오류가 발생했습니다.</p>
                                <p class="text-gray-600 mt-1">오류 메시지: ${error.message || '알 수 없는 오류가 발생했습니다.'}</p>
                                <p class="text-gray-600 mt-1">라우트: /admin/media/list-json</p>
                                <button @click="loadMedia()" class="mt-3 px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                                    다시 시도
                                </button>
                            </div>
                        `;
                    })
                    .finally(() => {
                        this.loading = false;
                    });
            },
            selectMedia(item) {
                this.selectedMedia = item;
            },
            insertMedia() {
                if (this.selectedMedia && this.callback) {
                    // Pass the URL and alt text to the TinyMCE callback
                    this.callback(this.selectedMedia.url, { alt: this.selectedMedia.alt_text || this.selectedMedia.name });
                    this.closeModal();
                }
            }
        }
    }
</script>
