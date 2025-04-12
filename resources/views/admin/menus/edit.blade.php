@extends('layouts.admin')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-semibold text-gray-700 mb-6">메뉴 수정: {{ $menu->name }}</h1>

        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            {{-- Form to update menu name, slug, and description --}}
            <form action="{{ route('admin.menus.update', $menu->id) }}" method="POST">
                @csrf
                @method('PUT') {{-- or PATCH --}}

                {{-- Name Field --}}
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                        메뉴 이름 <span class="text-red-500">*</span>
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror" id="name" name="name" type="text" placeholder="예: 메인 네비게이션" value="{{ old('name', $menu->name) }}" required>
                    @error('name')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>


                {{-- Description Field --}}
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                        설명 (선택 사항)
                    </label>
                    <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('description') border-red-500 @enderror" id="description" name="description" rows="3" placeholder="메뉴에 대한 간단한 설명">{{ old('description', $menu->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Menu Items Management Section (Alpine.js) --}}
                {{-- Use the $menuItems variable passed from the controller --}}
                <div x-data="menuItemsManager({{ $menu->id }}, {{ json_encode($menuItems->toArray()) }})" class="mb-6 p-4 bg-gray-100 rounded border border-gray-300">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">메뉴 항목 관리 (드래그하여 순서 변경)</h3>

                    {{-- Display Existing Menu Items using Alpine template --}}
                    <div id="menu-items-list" class="space-y-3 mb-4" x-ref="itemsList"> {{-- Add x-ref --}}
                        <template x-for="item in items" :key="item.id">
                            {{-- Add menu-item class --}}
                            <div class="flex items-center justify-between p-3 bg-white border rounded shadow-sm menu-item" :data-item-id="item.id">
                                {{-- Drag Handle --}}
                                <div class="cursor-move mr-2 text-gray-400 hover:text-gray-600 handle">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    </svg>
                                </div>
                                <div class="flex-grow mr-4">
                                    <span class="font-medium" x-text="item.title"></span>
                                    <span class="text-sm text-gray-500 ml-2" x-text="'(' + item.url + ')'"></span>
                                    {{-- Display Parent Info --}}
                                    <template x-if="item.parent_id">
                                        {{-- Ensure IDs are compared as numbers --}}
                                        <span class="text-xs text-gray-400 ml-2 italic" x-text="'(Parent: ' + (items.find(p => Number(p.id) === Number(item.parent_id))?.title || 'Unknown') + ')'"></span>
                                    </template>
                                </div>
                                <div class="flex-shrink-0">
                                    {{-- Edit/Delete buttons will call Alpine methods --}}
                                    <button type="button" @click="startEdit(item)" class="text-blue-600 hover:text-blue-800 text-sm px-2 py-1 mr-1">수정</button>
                                    <button type="button" @click="deleteItem(item.id)" class="text-red-600 hover:text-red-800 text-sm px-2 py-1">삭제</button>
                                    {{-- Add drag handle later --}}
                                </div>
                            </div>
                        </template>
                        <template x-if="items.length === 0">
                             <p class="text-gray-500 text-center py-3">아직 메뉴 항목이 없습니다.</p>
                        </template>
                    </div>

                    {{-- Add/Edit Menu Item Form --}}
                    <div id="add-edit-menu-item-form" class="mt-4 pt-4 border-t border-gray-300">
                         <h4 class="text-md font-semibold text-gray-700 mb-3" x-text="editingItem.id ? '항목 수정' : '새 항목 추가'"></h4>
                         {{-- Bind inputs to Alpine state --}}
                         <input type="hidden" x-model="editingItem.id">
                         <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                             <div>
                                 <label for="item-title" class="block text-sm font-medium text-gray-700">항목 이름 <span class="text-red-500">*</span></label>
                                 <input type="text" id="item-title" x-model="editingItem.title" placeholder="예: 회사소개" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                             </div>
                             <div>
                                 <label for="item-url" class="block text-sm font-medium text-gray-700">URL <span class="text-red-500">*</span></label>
                                 <input type="text" id="item-url" x-model="editingItem.url" placeholder="예: /about-us or https://example.com" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                             </div>
                             {{-- Parent Item Selector --}}
                             <div>
                                <label for="item-parent" class="block text-sm font-medium text-gray-700">부모 항목</label>
                                <select id="item-parent" x-model="editingItem.parent_id" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">-- 없음 --</option>
                                    <template x-for="parentOption in items" :key="parentOption.id">
                                        {{-- Disable selecting itself as parent --}}
                                        <option :value="parentOption.id" x-text="parentOption.title" :disabled="parentOption.id === editingItem.id"></option>
                                    </template>
                                </select>
                            </div>
                            {{-- Target Selector --}}
                            <div>
                                <label for="item-target" class="block text-sm font-medium text-gray-700">열기 방식</label>
                                <select id="item-target" x-model="editingItem.target" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="_self">현재 창 (_self)</option>
                                    <option value="_blank">새 창 (_blank)</option>
                                </select>
                            </div>
                         </div>
                         {{-- Add/Update item button calls Alpine method --}}
                         <div class="flex items-center space-x-3">
                             <button type="button" @click="saveItem" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm" :disabled="loading" :class="{ 'opacity-50 cursor-not-allowed': loading }">
                                 <span x-show="!loading" x-text="editingItem.id ? '항목 업데이트' : '항목 추가'"></span>
                                 <span x-show="loading" x-text="editingItem.id ? '업데이트 중...' : '추가 중...'"></span>
                             </button>
                             <button type="button" x-show="editingItem.id" @click="cancelEdit" class="text-gray-600 hover:text-gray-800 text-sm py-2 px-4 rounded border border-gray-300">
                                 취소
                             </button>
                         </div>
                         <p x-show="error" x-text="error" class="text-red-500 text-xs italic mt-2"></p>
                    </div>
                </div>

                {{-- Form Submission Buttons --}}
                <div class="flex items-center justify-between mt-6">
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                        메뉴 정보 업데이트
                    </button>
                    <a href="{{ route('admin.menus.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                        취소
                     </a>
                 </div>
             </form>
         </div>
     </div>
 @endsection

 <script>
 // import Sortable from 'sortablejs'; // Keep this removed

 document.addEventListener('alpine:init', () => {
     // Accept parentOptions as the third argument
     Alpine.data('menuItemsManager', (menuId, initialItems = [], initialParentOptions = []) => ({
         menuId: menuId,
         items: initialItems, // This list has depth and is ordered for display
         parentOptions: initialParentOptions, // Flat list for the dropdown
         editingItem: { id: null, title: '', url: '', target: '_self', parent_id: null }, // Holds data for add/edit form
         loading: false,
         error: '',

         // Initialize by setting items
         init() {
             // console.log('Menu items manager initialized for menu:', this.menuId, this.items);
             // Reset form to 'add' mode initially
             this.resetForm();
             this.initSortable(); // Initialize SortableJS
         },

         // Reset form fields and set to 'add' mode
         resetForm() {
             this.editingItem = { id: null, title: '', url: '', target: '_self', parent_id: null };
             this.error = '';
         },

         // Prepare the form for editing an existing item
         startEdit(item) {
             // Assign properties individually to the editingItem state
             this.editingItem.id = item.id;
             this.editingItem.title = item.title;
             this.editingItem.url = item.url;
             this.editingItem.target = item.target || '_self'; // Ensure target has a default
             this.editingItem.parent_id = item.parent_id;
             this.error = '';
             // Scroll to the form for better UX
             document.getElementById('add-edit-menu-item-form').scrollIntoView({ behavior: 'smooth' });
         },

         // Cancel editing and reset the form
         cancelEdit() {
             this.resetForm();
         },

         // Save (Add or Update) Menu Item
         async saveItem() {
             this.loading = true;
             this.error = '';
             const isUpdating = !!this.editingItem.id;
             const url = isUpdating
                 ? `/admin/menus/${this.menuId}/items/${this.editingItem.id}`
                 : `/admin/menus/${this.menuId}/items`;
             const method = isUpdating ? 'PUT' : 'POST';

             // Basic validation
             if (!this.editingItem.title.trim() || !this.editingItem.url.trim()) {
                 this.error = '항목 이름과 URL은 필수입니다.';
                 this.loading = false;
                 return;
             }

             try {
                 const response = await fetch(url, {
                     method: method,
                     headers: {
                         'Content-Type': 'application/json',
                         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // Get CSRF token
                     },
                     body: JSON.stringify({
                         title: this.editingItem.title,
                         url: this.editingItem.url,
                         target: this.editingItem.target || '_self', // Ensure target has a value
                         parent_id: this.editingItem.parent_id,
                         // order will be handled by backend or separate update function
                     })
                 });

                 if (!response.ok) {
                     const errorData = await response.json();
                     throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
                 }

                 const savedItem = await response.json();

                 if (isUpdating) {
                     // Find and update the item in the list
                     const index = this.items.findIndex(i => i.id === savedItem.id);
                     if (index !== -1) {
                         this.items.splice(index, 1, savedItem);
                     }
                 } else {
                     // Add the new item to the list
                     this.items.push(savedItem);
                 }
                 this.resetForm(); // Reset form after successful save

             } catch (err) {
                 console.error('Error saving menu item:', err);
                 this.error = `저장 중 오류 발생: ${err.message}`;
             } finally {
                 this.loading = false;
             }
         },

         // Delete Menu Item
         async deleteItem(itemId) {
             if (!confirm('정말로 이 항목을 삭제하시겠습니까? 하위 항목이 있다면 함께 삭제될 수 있습니다.')) {
                 return;
             }

             this.loading = true; // Use loading state for delete as well
             this.error = '';

             try {
                 const response = await fetch(`/admin/menus/${this.menuId}/items/${itemId}`, {
                     method: 'DELETE',
                     headers: {
                         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                     }
                 });

                 if (!response.ok) {
                      const errorData = await response.json();
                     throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
                 }

                 // Remove item from the list
                 this.items = this.items.filter(i => i.id !== itemId);

             } catch (err) {
                 console.error('Error deleting menu item:', err);
                 this.error = `삭제 중 오류 발생: ${err.message}`;
             } finally {
                 this.loading = false;
             }
         },

         // Initialize SortableJS on the items list
         initSortable() {
             const el = this.$refs.itemsList;
             if (!el) {
                 console.error('Sortable target element not found.');
                 return;
             }
             new Sortable(el, {
                 animation: 150,
                 handle: '.handle', // Use the handle for dragging
                 ghostClass: 'bg-blue-100', // Class for the drop placeholder
                 onEnd: (evt) => {
                     // Get the new order of item IDs
                     const itemElements = Array.from(evt.target.children);
                     const orderedIds = itemElements
                         .filter(node => node.matches('.menu-item')) // Ensure we only get actual items
                         .map(node => node.getAttribute('data-item-id'));
                     this.updateOrder(orderedIds);
                 },
             });
         },

         // Update item order on the backend
         async updateOrder(orderedIds) {
             this.loading = true; // Indicate loading state
             this.error = '';
             console.log('Updating order:', orderedIds); // Debugging

             try {
                 const response = await fetch(`/admin/menus/${this.menuId}/items/update-order`, {
                     method: 'POST', // Use POST or PUT as defined in your routes
                     headers: {
                         'Content-Type': 'application/json',
                         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                     },
                     body: JSON.stringify({
                         orderedIds: orderedIds // Send flat list for now
                         // If sending structure: structure: structure
                     })
                 });

                 if (!response.ok) {
                     const errorData = await response.json();
                     throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
                 }

                 // Optionally, re-fetch or re-order items in the frontend if needed,
                 // but SortableJS already visually reordered them.
                 // Maybe show a success message.
                 console.log('Order updated successfully');


             } catch (err) {
                 console.error('Error updating menu item order:', err);
                 this.error = `순서 업데이트 중 오류 발생: ${err.message}`;
                 // Optionally revert the visual order if the backend update fails
             } finally {
                 this.loading = false;
             }
         }

     }));
 });
 </script>
