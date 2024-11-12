<script setup>
import { defineProps } from 'vue';

const props = defineProps({
    lotusRequests: Array, // Definieer de lotusRequests prop
    tableTitle: String,
});

// Functie om de tijd te formatteren
const formatTime = (timeString) => {
    const date = new Date(`1970-01-01T${timeString}Z`);
    return date.toLocaleTimeString('nl-NL', { hour: '2-digit', minute: '2-digit' });
};
</script>

<style scoped>
/* Voeg hier eventuele stijlen voor je tabelcomponent toe */
</style>

<template>
    <div class="bg-white overflow-scroll shadow-sm rounded-md sm:rounded-lg border border-gray-200">
        <div class="p-6">
            <h2 class="mb-2 text-md uppercase font-semibold">{{ tableTitle }}</h2>
            <hr class="mb-4">

            <!-- Check of lotusRequests leeg is -->
            <div v-if="lotusRequests.length === 0" class="text-gray-600 py-4">
                Er zijn momenteel geen aanvragen beschikbaar.
            </div>

            <div v-else class="relative overflow-x-auto">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                    <thead class="text-gray-700 uppercase text-sm bg-gray-50">
                    <tr>
                        <th scope="col" class="px-2 py-1 sm:px-4 sm:py-2 lg:px-6 lg:py-4">Aanvraag</th>
                        <th scope="col" class="px-2 py-1 sm:px-4 sm:py-2 lg:px-6 lg:py-4">Datum</th>
                        <th scope="col" class="px-2 py-1 sm:px-4 sm:py-2 lg:px-6 lg:py-4">Plaats</th>
                        <th scope="col" class="px-2 py-1 sm:px-4 sm:py-2 lg:px-6 lg:py-4">Tijd</th>
                        <th scope="col" class="px-2 py-1 sm:px-4 sm:py-2 lg:px-6 lg:py-4">Aanmeldingen</th>
                        <th scope="col" class="px-2 py-1 sm:px-4 sm:py-2 lg:px-6 lg:py-4">Bekijken</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="request in lotusRequests" :key="request.id" class="odd:bg-white even:bg-gray-50 border-b hover:bg-gray-200">
                        <th scope="row" class="px-2 py-1 sm:px-4 sm:py-2 lg:px-6 lg:py-4 font-medium text-gray-900 whitespace-nowrap">
                            {{ request.name }}
                        </th>
                        <td class="px-2 py-1 sm:px-4 sm:py-2 lg:px-6 lg:py-4">
                            {{ new Date(request.date).toLocaleDateString('nl-NL') }}
                        </td>
                        <td class="px-2 py-1 sm:px-4 sm:py-2 lg:px-6 lg:py-4">
                            {{ request.city }}
                        </td>
                        <td class="px-2 py-1 sm:px-4 sm:py-2 lg:px-6 lg:py-4">
                            {{ formatTime(request.arrival_time) }} - {{ formatTime(request.end_time) }}
                        </td>
                        <td class="px-2 py-1 sm:px-4 sm:py-2 lg:px-6 lg:py-4">
                            {{ request.filled_spots}} / <strong>{{ request.amount_lotus }}</strong>
                        </td>
                        <td class="px-2 py-1 sm:px-4 sm:py-2 lg:px-6 lg:py-4">
                            <a :href="route('lotus-requests.viewlotusrequest', { id: request.id })" class="inline-flex items-center justify-center w-6 h-6 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-200 ease-in-out">
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>


