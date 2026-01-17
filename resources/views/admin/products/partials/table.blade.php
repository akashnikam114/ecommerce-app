<div class="overflow-x-auto shadow-md sm:rounded-lg border border-gray-200">
    <table class="w-full text-sm text-left text-gray-500">
        <thead class="text-xs text-white uppercase bg-gray-800">
            <tr>
                <th scope="col" class="px-6 py-4 font-bold">Product Name</th>
                <th scope="col" class="px-6 py-4 font-bold">SKU</th>
                <th scope="col" class="px-6 py-4 font-bold">Price</th>
                <th scope="col" class="px-6 py-4 font-bold">Stock</th>
                <th scope="col" class="px-6 py-4 font-bold text-center">Status</th>
                <th scope="col" class="px-6 py-4 font-bold text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($products as $product)
            <tr class="bg-white hover:bg-gray-50 transition duration-150">
                <td class="px-6 py-4 font-bold text-gray-900">{{ $product->name }}</td>
                <td class="px-6 py-4 font-mono text-gray-600">{{ $product->sku }}</td>
                <td class="px-6 py-4 font-bold text-green-700">₹{{ number_format($product->price, 2) }}</td>
                <td class="px-6 py-4">{{ $product->stock }}</td>
                <td class="px-6 py-4 text-center">
                    <button onclick="toggleStatus({{ $product->id }})"
                            id="status-btn-{{ $product->id }}"
                            class="px-3 py-1 rounded-full text-xs font-bold {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                    </button>
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="flex justify-end gap-3">
                        <button onclick='openEditModal({!! $product->toJson() !!})'
                                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs font-bold shadow-sm">
                            Edit
                        </button>
                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs font-bold shadow-sm">
                                Delete
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-6 py-10 text-center text-gray-400">No products found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $products->links() }}</div>
