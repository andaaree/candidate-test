import React from "react";
import { Link } from "@inertiajs/inertia-react";

export default function MainLayout({ children }) {
  return (
    <div className="flex h-screen bg-gray-100">
      {/* Sidebar */}
      <aside className="w-64 bg-white shadow-md">
        <div className="p-6 text-xl font-bold border-b">CLT Dashboard</div>
        <nav className="mt-6">
          <ul>
            <li className="mb-3">
              <Link
                href="/dashboard"
                className="block py-2 px-4 rounded hover:bg-gray-200"
              >
                Dashboard
              </Link>
            </li>
            <li className="mb-3">
              <Link
                href="/suppliers"
                className="block py-2 px-4 rounded hover:bg-gray-200"
              >
                Suppliers
              </Link>
            </li>
            <li className="mb-3">
              <Link
                href="/layups"
                className="block py-2 px-4 rounded hover:bg-gray-200"
              >
                Layups
              </Link>
            </li>
            <li className="mb-3">
              <Link
                href="/layers"
                className="block py-2 px-4 rounded hover:bg-gray-200"
              >
                Layers
              </Link>
            </li>
            {/* Add more nav links here */}
          </ul>
        </nav>
      </aside>

      {/* Main Content */}
      <div className="flex-1 flex flex-col overflow-auto">
        <header className="bg-white shadow px-6 py-4 flex justify-between items-center">
          <h1 className="text-2xl font-semibold text-gray-700">Dashboard</h1>
          <div>
            {/* Placeholder for user menu / notifications */}
            <button className="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
              Profile
            </button>
          </div>
        </header>
        <main className="p-6 flex-1">{children}</main>
      </div>
    </div>
  );
}
