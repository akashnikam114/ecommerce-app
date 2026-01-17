<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-xl text-gray-800 leading-tight">Product Management</h2>
            <button onclick="openAddModal()" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 transition ease-in-out duration-150 shadow-md">
                + Add New Product
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-100 border-l-4 border-emerald-500 text-emerald-700 shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-xl rounded-lg overflow-hidden border border-slate-200 p-6">
                <div class="mb-6">
                    <div class="relative max-w-sm">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                        </span>
                        <input type="text" id="search" placeholder="Search name or SKU..."
                               class="block w-full pl-10 pr-3 py-2 border border-slate-300 rounded-md leading-5 bg-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition duration-150">
                    </div>
                </div>

                <div id="product-table-container">
                    @include('admin.products.partials.table')
                </div>
            </div>
        </div>
    </div>

    @include('admin.products.partials.modals')

    <script>
        document.getElementById('search').addEventListener('input', function(e) {
            fetch(`{{ route('admin.products.index') }}?search=${e.target.value}`, {
                headers: { "X-Requested-With": "XMLHttpRequest" }
            })
            .then(res => res.text())
            .then(html => document.getElementById('product-table-container').innerHTML = html);
        });

        function toggleStatus(id) {
            fetch(`/admin/products/${id}/toggle`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                const btn = document.getElementById(`status-btn-${id}`);
                if(data.is_active) {
                    btn.innerText = 'Active';
                    btn.className = 'px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800';
                } else {
                    btn.innerText = 'Inactive';
                    btn.className = 'px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800';
                }
            })
            .catch(err => {
                console.error('Error:', err);
                alert('Failed to update status');
            });
        }

        function openAddModal() {
            document.getElementById('modal-title').innerText = 'Create New Product';
            document.getElementById('product-form').action = "{{ route('admin.products.store') }}";
            document.getElementById('method_field').innerHTML = '';
            document.getElementById('product-form').reset();
            document.getElementById('product-modal').classList.remove('hidden');
        }

        function openEditModal(product) {
            document.getElementById('modal-title').innerText = 'Edit Product';
            document.getElementById('product-form').action = `/admin/products/${product.id}`;
            document.getElementById('method_field').innerHTML = '<input type="hidden" name="_method" value="PUT">';

            document.getElementsByName('name')[0].value = product.name;
            document.getElementsByName('sku')[0].value = product.sku;
            document.getElementsByName('price')[0].value = product.price;
            document.getElementsByName('stock')[0].value = product.stock;

            document.getElementById('product-modal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('product-modal').classList.add('hidden');
        }
    </script>
</x-app-layout>
