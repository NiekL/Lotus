<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, usePage} from '@inertiajs/vue3';
import LotusRequestTable from '@/Components/LotusRequestsTable.vue';

import PrimaryButton from '@/Components/PrimaryButton.vue';
import {computed, defineProps} from 'vue';

const props = defineProps({
    lotusRequests: Array, // Definieer de lotusRequests prop
    penningmeesterAllLotusRequests: Array,
    success: String,
});



const formatTime = (timeString) => {
    // Create a Date object using a base date (e.g., today's date) and the time string
    const date = new Date(`1970-01-01T${timeString}Z`); // Use UTC for correct parsing

    return date.toLocaleTimeString('nl-NL', { hour: '2-digit', minute: '2-digit' });
};

const page = usePage();
const userRoles = computed(() => page.props.auth.user?.roles || []);
const isCoordinator = computed(() => userRoles.value.includes("coordinator"));


</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mijn aanvragen</h2>
        </template>

        <div class="py-8">
            <div class="mx-auto px-2 sm:px-6 lg:px-8">
                <!-- Succesmelding -->
                <div v-if="props.success" class="bg-green-50 overflow-scroll shadow-sm rounded-md sm:rounded-lg border border-green-500 mb-6">
                    <div class="p-4 rounded">
                        {{ props.success }}
                    </div>
                </div>
                <!-- Gebruik het LotusRequestTable component -->
                <LotusRequestTable :lotusRequests="props.lotusRequests" :tableTitle="isCoordinator ? 'Beschikbare aanvragen voor leden' : 'Beschikbare aanvragen'" />
            </div>
        </div>

        <div v-if="props.penningmeesterAllLotusRequests.length > 0" class="pb-8">
            <div class="mx-auto px-2 sm:px-6 lg:px-8">
                <!-- Gebruik het LotusRequestTable component -->
                <LotusRequestTable :lotusRequests="props.penningmeesterAllLotusRequests" tableTitle="Alle Lotus Aanvragen" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
