<script setup>
import { defineProps } from 'vue';

const props = defineProps({
    activeUserLotusRequests: Array, // Definieer de userLotusRequests prop
    tableTitle: String,
});

// Functie om de tijd te formatteren
const formatTime = (timeString) => {
    const date = new Date(`1970-01-01T${timeString}`);
    return date.toLocaleTimeString('nl-NL', { hour: '2-digit', minute: '2-digit' });
};
</script>

<style scoped>
/* Voeg hier eventuele stijlen voor je tabelcomponent toe */
</style>

<template>
    <div class="bg-sky-50 overflow-scroll shadow-sm rounded-md sm:rounded-lg border border-gray-200">
        <div class="p-6">
            <h2 class="mb-2 text-md uppercase font-semibold">{{ tableTitle }}</h2>
            <hr class="mb-4">

            <!-- Check of userLotusRequests leeg is -->
            <div v-if="activeUserLotusRequests.length === 0" class="text-gray-600 py-4">
                Er zijn momenteel geen aanvragen beschikbaar.
            </div>

            <div v-else class="relative overflow-x-auto">
                <!-- Desktop/tablet-weergave -->
                <table class="hidden lg:table w-full text-sm text-left rtl:text-right text-gray-500">
                    <thead class="text-gray-700 uppercase text-sm bg-sky-200">
                    <tr>
                        <th class="px-4 py-2">Aanvraag</th>
                        <th class="px-4 py-2">Datum</th>
                        <th class="px-4 py-2">Plaatsnaam</th>
                        <th class="px-4 py-2">Tijd</th>
                        <th class="px-4 py-2">Aanmeldingen</th>
                        <th class="px-4 py-2">Bekijken</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="request in activeUserLotusRequests" :key="request.id" class="odd:bg-sky-50 even:bg-sky-100 border-b hover:bg-gray-200">
                        <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap">{{ request.name }}</td>
                        <td class="px-4 py-2">{{ new Date(request.date).toLocaleDateString('nl-NL') }}</td>
                        <td class="px-4 py-2">{{ request.city }}</td>
                        <td class="px-4 py-2">{{ formatTime(request.arrival_time) }} - {{ formatTime(request.end_time) }}</td>
                        <td class="px-4 py-2">{{ request.filled_spots }} / <strong>{{ request.amount_lotus }}</strong></td>
                        <td class="px-4 py-2">
                            <a :href="route('lotus-requests.viewlotusrequest', { id: request.id })" class="inline-flex items-center justify-center w-6 h-6 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-200 ease-in-out">
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <!-- Mobiele versie -->
                <div class="lg:hidden space-y-4">
                    <div v-for="request in activeUserLotusRequests" :key="request.id" class="p-4 border rounded-lg bg-white shadow-sm">
                        <div><span class="font-semibold">Aanvraag:</span> {{ request.name }}</div>
                        <hr class="my-1">
                        <div><span class="font-semibold">Datum:</span> {{ new Date(request.date).toLocaleDateString('nl-NL') }}</div>
                        <div><span class="font-semibold">Plaatsnaam:</span> {{ request.city }}</div>
                        <div><span class="font-semibold">Tijd:</span> {{ formatTime(request.arrival_time) }} - {{ formatTime(request.end_time) }}</div>
                        <div><span class="font-semibold">Aanmeldingen:</span> {{ request.filled_spots }} / <strong>{{ request.amount_lotus }}</strong></div>
                        <div class="mt-2">
                            <a :href="route('lotus-requests.viewlotusrequest', { id: request.id })" class="inline-flex items-center justify-center w-6 h-6 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-200 ease-in-out">
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>


