<div id="product-modal" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-60 overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-[500px] shadow-2xl rounded-xl bg-white transition-all transform scale-100">
        <div class="flex items-center justify-between border-b pb-3">
            <h3 class="text-xl font-bold text-gray-800" id="modal-title">Add Product</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        </div>

        <form id="product-form" method="POST" class="mt-4">
            @csrf
            <div id="method_field"></div>

            <div class="space-y-4 text-left">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Product Name</label>
                    <input type="text" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">SKU (Unique)</label>
                        <input type="text" name="sku" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Price ($)</label>
                        <input type="number" step="0.01" name="price" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Stock Quantity</label>
                    <input type="number" name="stock" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" id="is_active_check" value="1" class="w-4 h-4 text-blue-600">
                    <label for="is_active_check" class="text-sm font-bold text-gray-700">Set as Active</label>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-8 border-t pt-4">
                <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md font-bold hover:bg-gray-300 transition">Cancel</button>
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-md font-bold hover:bg-indigo-700 shadow-md transition">Save Product</button>
            </div>
        </form>
    </div>
</div>
