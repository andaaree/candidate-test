<x-layouts.main
    title="Dashboard"
    :breadcrumbs="[
        $items
    ]"
>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    <!-- Card -->
    <div class="bg-white p-5 rounded-xl shadow">
        <h2 class="text-gray-500 text-sm">Total Suppliers</h2>
        <p class="text-2xl font-bold mt-2">120</p>
    </div>

    <div class="bg-white p-5 rounded-xl shadow">
        <h2 class="text-gray-500 text-sm">Total Layups</h2>
        <p class="text-2xl font-bold mt-2">340</p>
    </div>

    <div class="bg-white p-5 rounded-xl shadow">
        <h2 class="text-gray-500 text-sm">Total Layers</h2>
        <p class="text-2xl font-bold mt-2">980</p>
    </div>

</div>

</x-layouts.main>
