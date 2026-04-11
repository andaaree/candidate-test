<!-- Modal Overlay -->
    <div id="supplierModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

        <!-- Card -->
        <div class="bg-white w-full max-w-md rounded-2xl shadow-xl p-6 relative">

            <!-- Close Button -->
            <button
                onclick="toggleModal(false)"
                class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 text-xl"
            >
                ✕
            </button>

            <!-- Title -->
            <h2 class="text-xl font-semibold mb-4">Add Supplier</h2>

            <!-- Form -->
            <form action="{{ route('supplier.store') }}" method="POST" class="space-y-4">
                @csrf

                <input
                    type="text"
                    name="name"
                    placeholder="Supplier Name"
                    class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                @error('name')
                    <div class="invalid-feedback">
                        {{$message}}
                    </div>
                @enderror
                <!-- Actions -->
                <div class="flex justify-end gap-2 pt-2">
                    <button
                        type="button"
                        onclick="toggleModal(false)"
                        class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300"
                    >
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700"
                    >
                        Save
                    </button>
                </div>
            </form>

        </div>
    </div>
