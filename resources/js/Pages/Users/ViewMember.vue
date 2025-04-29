<script setup>
import { router } from '@inertiajs/vue3'
import {reactive, ref, defineProps, computed} from 'vue';
import { usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import UserLotusRequestTable from "@/Components/UserLotusRequestsTable.vue";
import LotusRequestTable from "@/Components/LotusRequestsTable.vue";

// Ontvang de gegevens van de gebruiker en zijn/haar aanvragen
const props = defineProps({
    member: Object,
    lotusRequests: Array,
});

const flashMessage = computed(() => usePage().props.flash?.success);

const editMode = ref(false);
const form = reactive({
    name: props.member.name,
    email: props.member.email,
});

const toggleEdit = () => {
    if (editMode.value) {
        updateUser();
    } else {
        editMode.value = true;
    }
};

const updateUser = () => {
    router.put(`/users/${props.member.id}`, form, {
        onSuccess: () => {
            editMode.value = false;
        },
        onError: (errors) => {
            console.error(errors);
        }
    });
};

const deleteUser = () => {
    if (confirm('Weet je zeker dat je deze gebruiker wilt verwijderen?')) {
        router.delete(`/users/${props.member.id}`);
    }
};


//User Roles
const page = usePage();
const userRoles = computed(() => page.props.auth.user?.roles || []);

const isAdmin = computed(() => userRoles.value.includes("admin"));
const isCoordinator = computed(() => userRoles.value.includes("coordinator"));
const isSecretaris = computed(() => userRoles.value.includes("secretaris"));

</script>

<template>
    <Head title="Bekijk lid" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Bekijk gebruiker</h2>
        </template>
        <div class="py-8">
            <div class="mx-auto px-2 sm:px-6 lg:px-8">

                <div :class="['p-4 sm:p-8 shadow sm:rounded-lg transition-all duration-300', editMode ? 'bg-yellow-50' : 'bg-white']">
                    <h2 class="mb-2 text-md font-semibold uppercase">Account gegevens</h2>
                    <hr class="mb-4">

                    <div class="space-y-6">

                        <div v-if="flashMessage" class="mb-4 px-4 py-3 rounded bg-green-100 text-green-800 border border-green-300">
                            {{ flashMessage }}
                        </div>

                        <div>
                            <InputLabel for="name" value="Naam" />
                            <TextInput
                                id="name"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.name"
                                :disabled="!editMode"
                            />
                        </div>
                        <div>
                            <InputLabel for="email" value="Email" />
                            <TextInput
                                id="email"
                                type="email"
                                class="mt-1 block w-full"
                                v-model="form.email"
                                :disabled="!editMode"
                            />
                        </div>

                        <div class="flex gap-4 pt-4" v-if="isAdmin || isCoordinator || isSecretaris">
                            <PrimaryButton @click="toggleEdit">
                                {{ editMode ? 'Opslaan' : 'Bewerk gebruiker' }}
                            </PrimaryButton>
                            <PrimaryButton
                                v-if="editMode"
                                class="bg-gray-500 hover:bg-gray-600"
                                @click="editMode = false"
                            >
                                Annuleer bewerken
                            </PrimaryButton>
                            <PrimaryButton class="bg-red-600 hover:bg-red-700" @click="deleteUser">
                                Verwijder gebruiker
                            </PrimaryButton>

                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="pb-8">
            <div class="mx-auto px-2 sm:px-6 lg:px-8">
                <LotusRequestTable :lotusRequests="lotusRequests" :tableTitle="'Gekoppelde aanvragen van ' + props.member.name " />
            </div>
        </div>

    </AuthenticatedLayout>
</template>

