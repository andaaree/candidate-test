@section('navbar')
<!-- Navbar -->
<nav
  class="flex-no-wrap relative flex w-full items-center justify-between bg-slate-100 py-2 shadow-md shadow-black/5 lg:flex-wrap lg:justify-start lg:py-4">
  <div class="flex w-full flex-wrap items-center justify-between px-3">
    <!-- Hamburger button for mobile view -->
    <button
      class="block border-0 bg-transparent px-2 text-neutral-500 hover:no-underline hover:shadow-none focus:no-underline focus:shadow-none focus:outline-none focus:ring-0 lg:hidden"
      type="button"
      data-twe-collapse-init
      data-twe-target="#navbarSupportedContent1"
      aria-controls="navbarSupportedContent1"
      aria-expanded="false"
      aria-label="Toggle navigation">
      <!-- Hamburger icon -->
      <span class="[&>svg]:w-7">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 24 24"
          fill="currentColor"
          class="h-7 w-7">
          <path
            fill-rule="evenodd"
            d="M3 6.75A.75.75 0 013.75 6h16.5a.75.75 0 010 1.5H3.75A.75.75 0 013 6.75zM3 12a.75.75 0 01.75-.75h16.5a.75.75 0 010 1.5H3.75A.75.75 0 013 12zm0 5.25a.75.75 0 01.75-.75h16.5a.75.75 0 010 1.5H3.75a.75.75 0 01-.75-.75z"
            clip-rule="evenodd" />
        </svg>
      </span>
    </button>

    <!-- Collapsible navigation container -->
    <div
      class="!visible hidden flex-grow basis-[100%] items-center lg:!flex lg:basis-auto"
      id="navbarSupportedContent1"
      data-twe-collapse-item>
      <!-- Logo -->
      <a
        class="mb-4 me-5 ms-2 mt-3 flex items-center text-neutral-900 hover:text-neutral-900 focus:text-neutral-900 dark:hover:text-neutral-400 dark:focus:text-neutral-400 lg:mb-0 lg:mt-0"
        href="#">
        <img
          src="https://tecdn.b-cdn.net/img/logo/te-transparent-noshadows.webp"
          style="height: 15px"
          alt="TE Logo"
          loading="lazy" />
      </a>
      <!-- Left navigation links -->
      <ul
        class="list-style-none me-auto flex flex-col ps-0 lg:flex-row"
        data-twe-navbar-nav-ref>
        <li class="mb-4 lg:mb-0 lg:pe-2" data-twe-nav-item-ref>
          <!-- Dashboard link -->
          <a
            class="@if($items[0]['label'] == 'Dashboard') active text-green-800 @else text-neutral-700 @endif transition duration-200 hover:text-neutral-700 hover:ease-in-out focus:text-neutral-700 disabled:text-black/30 motion-reduce:transition-none dark:hover:text-neutral-300 dark:focus:text-neutral-300 lg:px-2 [&.active]:text-black/90 dark:[&.active]:text-zinc-400"
            href="/dashboard"
            >Dashboard</a
          >
        </li>
        <li class="mb-4 lg:mb-0 lg:pe-2" data-twe-nav-item-ref>
          <!-- Suppliers link -->
          <a
            class="@if($items[0]['label'] == 'Supplier') active text-green-800 @else text-neutral-700 @endif transition duration-200 hover:text-neutral-700 hover:ease-in-out focus:text-neutral-700 disabled:text-black/30 motion-reduce:transition-none dark:hover:text-neutral-300 dark:focus:text-neutral-300 lg:px-2 [&.active]:text-black/90 dark:[&.active]:text-zinc-400"
            href="/supplier"
            >Supplier</a
          >
        </li>
        <li class="mb-4 lg:mb-0 lg:pe-2" data-twe-nav-item-ref>
          <!-- Layups link -->
          <a
            class="text-neutral-700 transition duration-200 hover:text-neutral-700 hover:ease-in-out focus:text-neutral-700 disabled:text-black/30 motion-reduce:transition-none dark:hover:text-neutral-300 dark:focus:text-neutral-300 lg:px-2 [&.active]:text-black/90 dark:[&.active]:text-zinc-400"
            href="/#"
            >Reports</a
          >
        </li>
        </li>
      </ul>
    </div>

    <!-- Right elements -->
    <div class="relative flex items-center">
      <!-- Container with two dropdown menus -->
      <div class="relative">
        <a title='Profile' href="/profile" class="rounded-lg shadow-md mx-2 p-1"><span class="material-icons">person</span>
        </a>
      </div>
      <div class="relative">
        <form action="/logout" method="post"><button title="Logout" type="submit" class="rounded-lg shadow-lg p-2">@csrf<span class="material-icons">logout</span></button></form>
      </div>
    </div>
  </div>
</nav>
<!-- Navbar -->

@endsection
