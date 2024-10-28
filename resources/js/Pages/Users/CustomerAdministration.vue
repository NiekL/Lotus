<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';

// Tabel data
const members = ref([
    { name: 'ZorgPlus', place: 'Zwolle', memberSince: '01-01-2020' },
    { name: 'HulpHaven', place: 'Staphorst', memberSince: '15-06-2021' },
    { name: 'DefensieDiensten', place: 'Amsterdam', memberSince: '22-03-2022' },
    { name: 'ZorgZorg', place: 'Rotterdam', memberSince: '10-11-2023' },
    { name: 'HulpHelder', place: 'Utrecht', memberSince: '05-09-2024' },
    { name: 'VeiligheidsVrienden', place: 'Eindhoven', memberSince: '12-12-2021' },
    { name: 'ZorgZorgelijk', place: 'Haarlem', memberSince: '18-08-2022' },
    { name: 'HulpHorizon', place: 'Nijmegen', memberSince: '21-07-2023' },
    { name: 'ZorgNetwerk', place: 'Zwolle', memberSince: '30-06-2024' },
    { name: 'DefensieDirect', place: 'Almere', memberSince: '07-04-2023' },
    { name: 'HulpHulp', place: 'Den Haag', memberSince: '03-01-2022' },
    { name: 'ZorgZeker', place: 'Groningen', memberSince: '28-02-2024' },
    { name: 'HulpHuis', place: 'Amersfoort', memberSince: '25-05-2022' },
    { name: 'DefensieDienst', place: 'Leiden', memberSince: '14-11-2021' },
    { name: 'ZorgZorgzaam', place: 'Haarlem', memberSince: '19-02-2023' },
    { name: 'HulpHulplijn', place: 'Maastricht', memberSince: '10-09-2022' },
    { name: 'DefensieDomein', place: 'Arnhem', memberSince: '24-07-2024' },
    { name: 'ZorgZorgeloos', place: 'Utrecht', memberSince: '09-05-2021' },
    { name: 'HulpHulplijn', place: 'Delft', memberSince: '06-08-2023' },
    { name: 'DefensieDuo', place: 'Rotterdam', memberSince: '12-12-2022' }
]);


// Sort state
const sortKey = ref('name');
const sortOrder = ref('asc'); // 'asc' of 'desc'

// Sorteren functie
const sortData = (key) => {
  if (sortKey.value === key) {
    sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc';
  } else {
    sortKey.value = key;
    sortOrder.value = 'asc';
  }

  members.value.sort((a, b) => {
    if (a[key] < b[key]) return sortOrder.value === 'asc' ? -1 : 1;
    if (a[key] > b[key]) return sortOrder.value === 'asc' ? 1 : -1;
    return 0;
  });
};
</script>


<style>
.sort-button {
  cursor: pointer;
}
</style>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Klanten administratie</h2>
    </template>

    <div class="py-8">
      <div class="mx-auto sm:px-6 lg:px-8">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
          <h2 class="mb-2 text-md font-semibold uppercase">Klanten administratie</h2>
          <hr class="mb-4">
          <!-- Sorteerbare tabel -->
          <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
              <thead class="text-gray-700 uppercase text-sm bg-gray-50">
              <tr>
                <th scope="col" class="px-6 py-3 sort-button" @click="sortData('name')">
                  Naam
                  <span v-if="sortKey === 'name'">{{ sortOrder === 'asc' ? '▲' : '▼' }}</span>
                </th>
                <th scope="col" class="px-6 py-3 sort-button" @click="sortData('place')">
                  Plaats
                  <span v-if="sortKey === 'place'">{{ sortOrder === 'asc' ? '▲' : '▼' }}</span>
                </th>
                <th scope="col" class="px-6 py-3 sort-button" @click="sortData('memberSince')">
                  Lid sinds
                  <span v-if="sortKey === 'memberSince'">{{ sortOrder === 'asc' ? '▲' : '▼' }}</span>
                </th>
                <th scope="col" class="px-6 py-3">
                  Bekijken
                </th>
              </tr>
              </thead>
              <tbody>
              <tr v-for="(member, index) in members" :key="index" class="odd:bg-white even:bg-gray-50 border-b hover:bg-gray-200">
                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                  {{ member.name }}
                </td>
                <td class="px-6 py-4">
                  {{ member.place }}
                </td>
                <td class="px-6 py-4">
                  {{ member.memberSince }}
                </td>
                <td class="px-6 py-4">
                  <a :href="route('users.viewcustomer')" class="inline-flex items-center justify-center w-6 h-6 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200 ease-in-out">
                    <i class="fa-solid fa-arrow-right"></i>
                  </a>
                </td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

      <div class="pb-8">
          <div class="mx-auto sm:px-6 lg:px-8">
              <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                  <h2 class="mb-2 text-md font-semibold uppercase">Nieuwe klant aanmaken</h2>
                  <hr class="mb-4">
                  <a :href="route('users.adduser')" class="bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                      <i class="fa-solid fa-plus"></i> Voeg nieuwe klant toe
                  </a>

              </div>
          </div>
      </div>
  </AuthenticatedLayout>
</template>

