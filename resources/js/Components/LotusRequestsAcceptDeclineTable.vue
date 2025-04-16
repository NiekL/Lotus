<script setup>
import axios from 'axios';
import { defineProps, ref } from 'vue';

const props = defineProps({
    newLotusRequests: Array,
});

console.log(props.newLotusRequests)

// Functie om de tijd te formatteren
const formatTime = (timeString) => {
    const date = new Date(`1970-01-01T${timeString}`);
    console.log(date);
    return date.toLocaleTimeString('nl-NL', { hour: '2-digit', minute: '2-digit' });

};


const acceptRequest = async (id) => {
    try {
        const response = await axios.post(`/lotus-requests/accept/${id}`);
        console.log(response.data);
        location.reload(); // Herlaad de pagina om de status te updaten
    } catch (error) {
        console.error('Er is een fout opgetreden:', error);
    }
};

const declineRequest = async (id) => {
    try {
        const response = await axios.post(`/lotus-requests/decline/${id}`);
        console.log(response.data);
        location.reload(); // Herlaad de pagina om de status te updaten
    } catch (error) {
        console.error('Er is een fout opgetreden:', error);
    }
};


</script>

<style scoped>
/* Voeg hier eventuele stijlen voor je tabelcomponent toe */
</style>

<template>
    <div class="bg-white overflow-scroll shadow-sm rounded-md sm:rounded-lg border border-gray-200">
        <div class="p-6">
            <h2 class="mb-2 text-md uppercase font-semibold">Aanvragen goedkeuren</h2>
            <hr class="mb-4">

            <!-- Check of lotusRequests leeg is -->
            <div v-if="newLotusRequests.length === 0" class="text-gray-600 py-4">
                Er zijn momenteel geen nieuwe aanvragen beschikbaar.
            </div>

            <div v-else class="relative overflow-x-auto">
                <!-- Desktop versie -->
                <table class="hidden lg:table w-full text-sm text-left rtl:text-right text-gray-500">
                    <thead class="text-gray-700 uppercase text-sm bg-gray-50">
                    <tr>
                        <th class="px-2 py-1 sm:px-4 sm:py-2 lg:px-6 lg:py-4">Aanvraag</th>
                        <th class="px-2 py-1 sm:px-4 sm:py-2 lg:px-6 lg:py-4">Datum</th>
                        <th class="px-2 py-1 sm:px-4 sm:py-2 lg:px-6 lg:py-4">Plaats</th>
                        <th class="px-2 py-1 sm:px-4 sm:py-2 lg:px-6 lg:py-4">Tijd</th>
                        <th class="px-2 py-1 sm:px-4 sm:py-2 lg:px-6 lg:py-4">Plekken</th>
                        <th class="px-2 py-1 sm:px-4 sm:py-2 lg:px-6 lg:py-4">Accepteren/Afwijzen</th>
                        <th class="px-2 py-1 sm:px-4 sm:py-2 lg:px-6 lg:py-4">Bekijken</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="request in newLotusRequests" :key="request.id" class="odd:bg-white even:bg-gray-50 border-b hover:bg-gray-200">
                        <td class="px-2 py-1 sm:px-4 sm:py-2 lg:px-6 lg:py-4 font-medium text-gray-900 whitespace-nowrap">
                            {{ request.name }}
                        </td>
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
                            <a @click="acceptRequest(request.id)" class="inline-flex items-center justify-center w-6 h-6 bg-green-600 text-white rounded-full hover:bg-green-700 transition duration-200 ease-in-out mr-2">
                                <i class="fa-solid fa-check"></i>
                            </a>
                            <a @click="declineRequest(request.id)" class="inline-flex items-center justify-center w-6 h-6 bg-red-600 text-white rounded-full hover:bg-red-700 transition duration-200 ease-in-out mr-2">
                                <i class="fa-solid fa-x"></i>
                            </a>
                        </td>
                        <td class="px-2 py-1 sm:px-4 sm:py-2 lg:px-6 lg:py-4">
                            <a :href="route('lotus-requests.viewlotusrequest', { id: request.id })" class="inline-flex items-center justify-center w-6 h-6 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200 ease-in-out">
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <!-- Mobiele versie -->
                <div class="lg:hidden space-y-4">
                    <div v-for="request in newLotusRequests" :key="request.id" class="p-4 border rounded-lg bg-gray-50 shadow-sm">
                        <div><span class="font-semibold">Aanvraag:</span> {{ request.name }}</div>
                        <hr class="my-1">
                        <div><span class="font-semibold">Datum:</span> {{ new Date(request.date).toLocaleDateString('nl-NL') }}</div>
                        <div><span class="font-semibold">Plaats:</span> {{ request.city }}</div>
                        <div><span class="font-semibold">Tijd:</span> {{ formatTime(request.arrival_time) }} - {{ formatTime(request.end_time) }}</div>
                        <div><span class="font-semibold">Plekken:</span> {{ request.filled_spots }} / <strong>{{ request.amount_lotus }}</strong></div>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="font-semibold">Accepteren / afwijzen:</span>
                            <button @click="acceptRequest(request.id)" class="inline-flex items-center justify-center w-6 h-6 bg-green-600 text-white rounded-full hover:bg-green-700 transition">
                                <i class="fa-solid fa-check"></i>
                            </button>
                            <button @click="declineRequest(request.id)" class="inline-flex items-center justify-center w-6 h-6 bg-red-600 text-white rounded-full hover:bg-red-700 transition">
                                <i class="fa-solid fa-x"></i>
                            </button>
                        </div>
                        <div class="flex items-center gap-2 mt-2">
                            <a :href="route('lotus-requests.viewlotusrequest', { id: request.id })" class="inline-flex items-center justify-center w-6 h-6 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</template>


