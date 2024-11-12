<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import LotusRequestsAcceptDeclineTable from "@/Components/LotusRequestsAcceptDeclineTable.vue";
import LotusRequestsTable from "@/Components/LotusRequestsTable.vue";
import {defineProps} from "vue";


const props = defineProps({
    newLotusRequests: Array,
    acceptedLotusRequests: Array,
    declinedLotusRequests: Array,
    expiredLotusRequests: Array,
    success: String,
});

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Aanvragen goedkeuren</h2>
        </template>

        <!-- Succesmelding -->
        <div v-if="props.success" class="py-8">
            <div class="mx-auto px-2 sm:px-6 lg:px-8">
                <div class="bg-green-50 overflow-scroll shadow-sm rounded-md sm:rounded-lg border border-green-500">
                    <div class="p-4 rounded">
                        {{ props.success }}
                    </div>
                </div>
            </div>
        </div>

        <div :class="props.success ? 'pb-8' : 'py-8'">
            <div class="mx-auto px-2 sm:px-6 lg:px-8">
                <LotusRequestsAcceptDeclineTable :newLotusRequests="props.newLotusRequests" />
            </div>
        </div>

        <div class="pb-8">
            <div class="mx-auto px-2 sm:px-6 lg:px-8">
                <LotusRequestsTable :lotusRequests="props.acceptedLotusRequests" tableTitle="Goedgekeurde aanvragen" />
            </div>
        </div>

        <div class="pb-8">
            <div class="mx-auto px-2 sm:px-6 lg:px-8">
                <LotusRequestsTable :lotusRequests="props.declinedLotusRequests" tableTitle="Afgewezen aanvragen" />
            </div>
        </div>

        <div class="pb-8">
            <div class="mx-auto px-2 sm:px-6 lg:px-8">
                <LotusRequestsTable :lotusRequests="props.expiredLotusRequests" tableTitle="Verlopen aanvragen" />
            </div>
        </div>

    </AuthenticatedLayout>
</template>


<script>
export default {
    props: {
        lotusRequests: Array,
    },
};
</script>
