<script setup>
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import UserLotusRequestTable from "@/Components/UserLotusRequestsTable.vue";
import LotusRequestTable from "@/Components/LotusRequestsTable.vue";

// Ontvang de gegevens van de gebruiker en zijn/haar aanvragen
const { member } = usePage().props;

// Maak reactieve referenties voor de naam en email
const name = ref(member.name);
const email = ref(member.email);

const lotusRequests = ref(member.lotus_requests || []);



</script>

<template>
    <Head title="Bekijk lid" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Bekijk gebruiker</h2>
        </template>
        <div class="py-8">
            <div class="mx-auto px-2 sm:px-6 lg:px-8">

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h2 class="mb-2 text-md font-semibold uppercase">Account gegevens</h2>
                    <hr class="mb-4">

                    <div class="space-y-6">

                        <div>
                            <InputLabel for="name" value="Naam" />
                            <TextInput
                                id="name"
                                type="text"
                                class="mt-1 block w-full"
                                autocomplete="name"
                                disabled
                                :model-value="name"/>
                        </div>
                        <div>
                            <InputLabel for="email" value="Email" />
                            <TextInput
                                id="email"
                                type="email"
                                class="mt-1 block w-full"
                                disabled
                                autocomplete="username"
                                :model-value="email"
                            />
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="pb-8">
            <div class="mx-auto px-2 sm:px-6 lg:px-8">
                <LotusRequestTable :lotusRequests="lotusRequests" :tableTitle="'Gekoppelde aanvragen van ' + name " />
            </div>
        </div>

    </AuthenticatedLayout>
</template>

