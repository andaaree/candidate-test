@extends('layouts.main')

@include('partials.navsupp')
@section('js')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
<style>
        .card { border-radius: 16px; }
        .dropzone { border: 2px dashed #adb5bd; border-radius: 16px; background: #fff; }
        .dropzone .dz-message { margin: 3rem 0; }
        .small-muted { font-size: 12px; color: #6c757d; }
        .session-pill { font-size: 12px; }
        .mono { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; }
    </style>
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
@endsection
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
            <div class="card-header my-2"><h1 class="card-title text-xl">Import Layup Data</h1></div>
            <div class="card-body my-4">
            </div>
        </div>
    </div>
</div>
@endsection
