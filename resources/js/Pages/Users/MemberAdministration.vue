<script setup>
import { ref, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';

// Haal leden op van de server via Inertia's usePage hook
const { members } = usePage().props;

//User Roles
const page = usePage();
const userRoles = computed(() => page.props.auth.user?.roles || []);

const isAdmin = computed(() => userRoles.value.includes("admin"));
const isCoordinator = computed(() => userRoles.value.includes("coordinator"));
const isSecretaris = computed(() => userRoles.value.includes("secretaris"));
</script>


<style>
.sort-button {
  cursor: pointer;
}
</style>

<template>
  <Head title="Leden administratie" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Leden Administratie</h2>
    </template>

    <div class="py-8">
      <div class="mx-auto px-2 sm:px-6 lg:px-8">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
          <h2 class="mb-2 text-md font-semibold uppercase">Leden Overzicht</h2>
          <hr class="mb-4">
          <!-- Sorteerbare tabel -->
          <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
              <thead class="text-gray-700 uppercase text-sm bg-gray-50">
              <tr>
                <th scope="col" class="px-2 py-1 sm:px-4 sm:py-2 lg:px-6 lg:py-4">
                </th>
                <th scope="col" class="px-2 py-1 sm:px-4 sm:py-2 lg:px-6 lg:py-4 sort-button" @click="sortData('name')">
                  Naam
                </th>
                <th scope="col" class="px-2 py-1 sm:px-4 sm:py-2 lg:px-6 lg:py-4">
                  E-mail
                </th>
                <th scope="col" class="px-2 py-1 sm:px-4 sm:py-2 lg:px-6 lg:py-4">
                  Bekijken
                </th>
              </tr>
              </thead>
              <tbody>
              <tr v-for="(member, index) in members" :key="index" class="odd:bg-white even:bg-gray-50 border-b hover:bg-gray-200">
                <td class="px-2 py-1 sm:px-4 sm:py-2 lg:px-6 lg:py-4">
                    <i class="fa-regular fa-user text-xl self-baseline"></i>
                </td>
                <td class="px-2 py-1 sm:px-4 sm:py-2 lg:px-6 lg:py-4 font-medium text-gray-900 whitespace-nowrap">
                  {{ member.name }}
                </td>
                <td class="px-2 py-1 sm:px-4 sm:py-2 lg:px-6 lg:py-4">
                  {{ member.email }}
                </td>
                <td class="px-2 py-1 sm:px-4 sm:py-2 lg:px-6 lg:py-4">
                    <td class="px-2 py-1 sm:px-4 sm:py-2 lg:px-6 lg:py-4">
                        <a :href="route('users.viewmember', member.id)" class="inline-flex items-center justify-center w-6 h-6 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200 ease-in-out">
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </td>
                </td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div v-if="isAdmin || isCoordinator || isSecretaris" class="pb-8">
      <div class="mx-auto px-2 sm:px-6 lg:px-8">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
          <h2 class="mb-2 text-md font-semibold uppercase">Nieuwe gebruiker aanmaken</h2>
          <hr class="mb-4">
          <a :href="route('users.adduser')" class="bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
            <i class="fa-solid fa-plus"></i> Voeg nieuwe gebruiker toe
          </a>

        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

