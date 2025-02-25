<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm, Head } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';
import LotusRequestsTable from "@/Components/LotusRequestsTable.vue";
import UserLotusRequestsTable from "@/Components/UserLotusRequestsTable.vue";
import {computed, defineProps} from "vue";
import InputLabel from "@/Components/InputLabel.vue";
const props = defineProps({
    lotusRequests: Array,
    activeUserLotusRequests: Array,
    announcements: Array,
    userRoles: Array,

});

console.log(props);


//Announcement form
const form = useForm({
    message: '',
});

//Delete Announcements
const deleteAnnouncement = (id) => {
    if (confirm('Weet je zeker dat je deze mededeling wilt verwijderen?')) {
        form.delete(route('announcements.destroy', id));
    }
};
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout :userRoles="userRoles">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-8">
            <div class="mx-auto px-2 sm:px-6 lg:px-8">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h2 class="mb-2 text-md font-semibold uppercase">Mededelingen</h2>
                    <hr class="mb-4">
                    <!-- Loop through announcements -->
                    <div v-if="props.announcements.length === 0">
                        <p>Er zijn op dit moment geen mededelingen.</p>
                    </div>

                    <div v-else>
                        <div v-for="announcement in props.announcements" :key="announcement.id">
                            <div class="flex justify-between items-start mb-2">
                                <p class="flex items-start">
                                    <i class="fa-regular fa-bell mt-1"></i> <!-- mt-1 helpt bij het beter uitlijnen van de bel met de bovenste regel van de tekst -->
                                    <span class="mx-3">{{ announcement.announcement }}</span>
                                </p>
                                <i v-if="userRoles.includes('admin') || userRoles.includes('coordinator')" class="fa-regular fa-trash-can cursor-pointer mt-1 text-red-600" @click="deleteAnnouncement(announcement.id)"></i>
                            </div>
                            <hr class="mb-2">
                        </div>

                    </div>

                    <form v-if="userRoles.includes('admin') || userRoles.includes('coordinator')" @submit.prevent="form.post(route('announcements.store'), { onSuccess: () => form.reset() })">
                        <textarea
                            v-model="form.message"
                            placeholder="Voeg een mededeling toe"
                            class="mt-6 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                        ></textarea>
                        <InputError :message="form.errors.message" class="mt-2" />
                        <PrimaryButton class="mt-4">Mededeling toevoegen</PrimaryButton>
                    </form>

                </div>
            </div>
        </div>

        <div v-if="['admin', 'coordinator', 'lid', 'penningmeester', 'secretaris'].some(role => userRoles.includes(role))" class="pb-8">
            <div class="mx-auto px-2 sm:px-6 lg:px-8">
                <UserLotusRequestsTable :activeUserLotusRequests="props.activeUserLotusRequests" tableTitle="Mijn aangemelde aanvragen" />
            </div>
        </div>

        <div v-if="['admin', 'coordinator', 'lid', 'penningmeester', 'secretaris'].some(role => userRoles.includes(role))" class="pb-8">
            <div class="mx-auto px-2 sm:px-6 lg:px-8">
                <LotusRequestsTable :lotusRequests="props.lotusRequests" tableTitle="Beschikbare aanvragen" />
            </div>
        </div>


    </AuthenticatedLayout>
</template>
