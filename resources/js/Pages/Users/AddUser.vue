<script setup>
import {ref, onMounted, defineProps} from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: '',
});

const submit = () => {
    form.post(route('users.store'), {
        onFinish: () =>
        {
            form.reset('name', 'email', 'password', 'password_confirmation');
        }
    });
};
</script>
<template>
    <Head title="Gebruiker toevoegen" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gebruiker toevoegen</h2>
        </template>

        <!-- Succesmelding -->
        <div v-if="$page.props.flash.success" class="pt-8">
            <div class="mx-auto px-2 sm:px-6 lg:px-8">
                <div class="bg-green-50 overflow-scroll shadow-sm rounded-md sm:rounded-lg border border-green-500">
                    <div class="p-4 rounded">
                        {{ $page.props.flash.success }}
                    </div>
                </div>
            </div>
        </div>

        <form @submit.prevent="submit">
<!--        <div :class="flashMessage ? 'pb-8' : 'py-8'">-->
        <div class="py-8">
            <div class="mx-auto px-2 sm:px-6 lg:px-8">

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h2 class="mb-2 text-md font-semibold uppercase">Gebruiker toevoegen</h2>
                    <hr class="mb-4">

                    <div class="space-y-6">

                        <div>
                            <InputLabel for="name" value="Naam" />

                            <TextInput
                                id="name"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.name"
                                required
                                autofocus
                                autocomplete="name"
                            />

                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="email" value="E-mailadres" />

                            <TextInput
                                id="email"
                                type="email"
                                class="mt-1 block w-full"
                                v-model="form.email"
                                required
                                autocomplete="username"
                            />

                            <InputError class="mt-2" :message="form.errors.email" />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="password" value="Wachtwoord" />

                            <TextInput
                                id="password"
                                type="password"
                                class="mt-1 block w-full"
                                v-model="form.password"
                                required
                                autocomplete="new-password"
                            />

                            <InputError class="mt-2" :message="form.errors.password" />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="password_confirmation" value="Bevestig wachtwoord" />

                            <TextInput
                                id="password_confirmation"
                                type="password"
                                class="mt-1 block w-full"
                                v-model="form.password_confirmation"
                                required
                                autocomplete="new-password"
                            />

                            <InputError class="mt-2" :message="form.errors.password_confirmation" />
                        </div>

                        <!-- Role selection -->
                        <div>
                            <InputLabel for="role" value="Rol" />
                            <select v-model="form.role" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="" disabled selected>Selecteer een rol</option>
                                <option v-for="role in $page.props.roles" :key="role.id" :value="role.id">
                                    {{ role.name }}
                                </option>
                            </select>
                            <InputError :message="form.errors.role" />
                        </div>

                        <div>
                            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Gebruiker toevoegen</PrimaryButton>
                        </div>

                    </div>

                </div>
            </div>
        </div>

        </form>

    </AuthenticatedLayout>
</template>

