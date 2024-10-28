<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";

</script>

<template>
    <Head title="Bekijk lid" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gebruiker toevoegen</h2>
        </template>
        <form>
        <div class="py-8">
            <div class="mx-auto sm:px-6 lg:px-8">

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h2 class="mb-2 text-md font-semibold uppercase">Gebruiker toevoegen</h2>
                    <hr class="mb-4">

                    <div class="space-y-6">

                        <div class="flex gap-6 flex-wrap lg:flex-nowrap">
                            <div class="w-full lg:w-1/2 inline-block">
                                <InputLabel for="name" value="Voornaam" />

                                <TextInput
                                    id="name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    required
                                    autofocus
                                    autocomplete="name"
                                    model-value=""/>
                            </div>
                            <div class="w-full lg:w-1/2 inline-block">
                                <InputLabel for="lastname" value="Achternaam" />

                                <TextInput
                                    id="lastname"
                                    type="text"
                                    class="mt-1 block w-full"
                                    required
                                    autofocus
                                    autocomplete="name"
                                    model-value=""/>
                            </div>
                        </div>
                        <div>
                            <InputLabel for="email" value="Email" />

                            <TextInput
                                id="email"
                                type="email"
                                class="mt-1 block w-full"
                                model-value=""
                                required
                                autocomplete="username"
                            />
                        </div>
                    </div>

                </div>
            </div>
        </div>

            <div class="pb-8">
                <div class="mx-auto sm:px-6 lg:px-8">

                    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                        <h2 class="mb-2 text-md font-semibold uppercase">Persoons gegevens</h2>
                        <hr class="mb-4">

                        <div class="space-y-6">

                                <div>
                                    <InputLabel for="adress" value="Adres" />

                                    <TextInput
                                        id="adress"
                                        type="text"
                                        class="mt-1 block w-full"
                                        required
                                        autofocus
                                        model-value=""/>
                                </div>
                                <div>
                                    <InputLabel for="zipcode" value="Postcode" />

                                    <TextInput
                                        id="zipcode"
                                        type="text"
                                        class="mt-1 block w-full"
                                        required
                                        autofocus
                                        model-value=""/>
                                </div>
                            <div>
                                <InputLabel for="city" value="Plaats" />

                                <TextInput
                                    id="city"
                                    type="text"
                                    class="mt-1 block w-full"
                                    model-value=""
                                    required
                                    autocomplete="city"
                                />
                            </div>

                            <div>
                                <InputLabel for="iban" value="IBAN" />

                                <TextInput
                                    id="iban"
                                    type="text"
                                    class="mt-1 block w-full"
                                    model-value=""
                                    required
                                />
                            </div>

                            <div>
                                <PrimaryButton>Gebruiker toevoegen</PrimaryButton>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </form>

    </AuthenticatedLayout>
</template>

