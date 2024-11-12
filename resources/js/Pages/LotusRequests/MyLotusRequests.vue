<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import LotusRequestTable from "@/Components/LotusRequestsTable.vue";
import UserLotusRequestTable from "@/Components/UserLotusRequestsTable.vue";

import { defineProps } from 'vue';

const props = defineProps({
    activeUserLotusRequests: Array,
    expiredUserLotusRequests: Array,
});

console.log(props);

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mijn aanvragen</h2>
        </template>

        <div class="py-8">
            <div class="mx-auto px-2 sm:px-6 lg:px-8">
                <UserLotusRequestTable :activeUserLotusRequests="props.activeUserLotusRequests" tableTitle="Mijn aangemelde aanvragen" />
            </div>
        </div>

        <div class="pb-8">
            <div class="mx-auto px-2 sm:px-6 lg:px-8">
                <LotusRequestTable :lotusRequests="props.expiredUserLotusRequests" tableTitle="Mijn afgeronde aanvragen" />
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
