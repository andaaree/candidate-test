import React, {useState} from 'react';
import { Inertia } from '@inertiajs/inertia';
import { Head } from "@inertiajs/react";
import { InertiaLink } from '@inertiajs/inertia-react';
import MainLayout from '@/Layouts/MainLayout';

export default function Index({suppliers}) {
  const handleDelete = (id) => {
    if(confirm("Delete this supplier?")) {
      Inertia.delete(route('suppliers.destroy', id));
    }
  };

  return (
    <MainLayout>
      <Head title="Suppliers" />
      {suppliers}
    </MainLayout>
  );
}
