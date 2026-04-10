import React from "react";
import MasterLayout from "../Layouts/MainLayout";
import { InertiaLink } from "@inertiajs/inertia-react";

export default function CustomDash() {
  return (
    <MasterLayout>
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div className="bg-white p-6 rounded shadow">
          <h2 className="text-lg font-semibold">Suppliers</h2>
          <p className="text-gray-500 mt-2">Manage your supplier records</p>
          <InertiaLink
            href="/suppliers"
            className="mt-4 inline-block text-blue-500 hover:underline"
          >
            View Suppliers
          </InertiaLink>
        </div>

        <div className="bg-white p-6 rounded shadow">
          <h2 className="text-lg font-semibold">Layups</h2>
          <p className="text-gray-500 mt-2">Manage CLT Layups under suppliers</p>
        </div>

        <div className="bg-white p-6 rounded shadow">
          <h2 className="text-lg font-semibold">Layers</h2>
          <p className="text-gray-500 mt-2">Manage Layers within each Layup</p>
        </div>
      </div>
    </MasterLayout>
  );
}
