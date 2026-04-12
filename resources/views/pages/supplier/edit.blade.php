@extends('layouts.main')

@include('partials.navsupp')
@section('content')

<div class="space-y-6">
    <!-- 📍 Breadcrumb -->
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <h2 class="text-xl font-semibold text-gray-800">
            {{ $pageName }}
        </h2>

        <nav>
            <ol class="flex items-center gap-1.5">
                @foreach ($items as $item)
                    @if($loop->first) <span>/</span> @endif
                    @if (!$loop->first)
                    <svg class="stroke-current mx-1" width="17" height="16" viewBox="0 0 17 16" fill="none">
                        <path
                        d="M6.0765 12.667L10.2432 8.50033L6.0765 4.33366"
                        stroke-width="1.2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        />
                    </svg>
                    @endif
                    <li class="text-sm">
                    <a href="{{ $item['url'] }}" class="text-black hover:text-gray-600">
                        {{ $item['label'] }}
                    </a>
                    </li>
                @endforeach
            </ol>
        </nav>
    </div>

    <div class="my-4 flex items-center justify-center overflow-hidden max-w-screen-xl mx-auto">
        <div class="card p-6 bg-white shadow-lg rounded-lg w-1/2 border-gray-600">
            <div class="card-header my-2"><h1 class="card-title text-xl">Edit Supplier {{$supplier->id}}</h1></div>
            <div class="card-body my-4">
                <form action="{{ route('supplier.update',$supplier->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="w-full max-w-sm min-w-[200px]">
                    <input name="supplier_name" value="{{$supplier->name}}" class="w-full bg-transparent border @error('supplier_name') placeholder:text-red-400 text-red-700 border-red-600 focus:border-red-700 hover:border-red-400 @enderror text-sm  rounded-md px-3 py-2 transition duration-300 ease focus:outline-none shadow-sm focus:shadow" placeholder="Supplier Name" />
                </div>
                @error('supplier_name')
                <p class="flex items-center mt-2 text-xs text-slate-400">
                    {{ $message }}
                </p>
                @enderror
                <div class="max-w-sm min-w-sm w-fit my-2 p-2">
                    <div class="relative">
                        <button
                        {{-- onclick="submitImport()" --}}
                            type="submit" class="btn p-3 w-full shadow-lg border-neutral-300 rounded-md"><span class="material-icons">download</span>
                            Save
                        </button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
</script>
@endsection
