<script setup>
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';
import { ref, onMounted } from 'vue';

const props = defineProps({
    billingInfo: Object
});

const form = useForm(props.billingInfo);

onMounted(() => {
    form.reset({ ...props.billingInfo });
});

const submit = () => {
    form.post(route('profile.billing-info.save'), {
        onFinish: () => form.reset(),
        onError: (errors) => console.error(errors)
    });
};

</script>

<template>
    <Head title="Factuurgegevens" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Factuurgegevens</h2>
        </template>

        <div class="py-8">
            <div class="mx-auto px-2 sm:px-6 lg:px-8">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h2 class="mb-2 text-md uppercase font-semibold">Factuurgegevens</h2>
                    <hr class="mb-4">

                    <form @submit.prevent="submit" class="space-y-6">

                        <div>
                            <InputLabel for="billing_name" value="(Bedrijfs)naam" />

                            <TextInput
                                id="billing_name"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.billing_name"
                                placeholder="Vul naam in..  "
                                />
                            <InputError :message="form.errors.billing_name" />
                        </div>

                        <div>
                            <InputLabel for="billing_address" value="Factuur straatnaam en huisnummer" />

                            <TextInput
                                id="billing_address"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.billing_address"
                                placeholder="Vul straatnaam en huisnummer in.."
                            />
                            <InputError :message="form.errors.billing_address" />

                        </div>

                        <div>
                            <InputLabel for="billing_zipcode" value="Factuur postcode" />

                            <TextInput
                                id="billing_zipcode"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.billing_zipcode"
                                placeholder="Vul postcode in.."
                            />
                            <InputError :message="form.errors.billing_zipcode" />

                        </div>

                        <div>
                            <InputLabel for="billing_city" value="Factuur plaats" />

                            <TextInput
                                id="billing_city"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.billing_city"
                                placeholder="Vul plaatsnaam in.."
                            />
                            <InputError :message="form.errors.billing_city" />

                        </div>

                        <div>
                            <InputLabel for="billing_contactperson" value="Factuur contactpersoon" />

                            <TextInput
                                id="billing_contactperson"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.billing_contactperson"
                                placeholder="Vul naam in.."
                            />
                            <InputError :message="form.errors.billing_contactperson" />

                        </div>

                        <div>
                            <InputLabel for="billing_phone" value="Factuur telefoonnummer" />

                            <TextInput
                                id="billing_phone"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.billing_phone"
                                placeholder="Vul telefoonnummer in.."
                            />
                            <InputError :message="form.errors.billing_phone" />

                        </div>


                        <div>
                            <InputLabel for="billing_email" value="Factuur emailadres" />

                            <TextInput
                                id="billing_email"
                                type="email"
                                class="mt-1 block w-full"
                                v-model="form.billing_email"
                                placeholder="Vul emailadres in.."
                            />
                            <InputError :message="form.errors.billing_email" />

                        </div>


                        <div class="flex items-center gap-4">
                            <PrimaryButton :disabled="form.processing">Factuurgegevens Opslaan</PrimaryButton>
                        </div>

                        <div v-if="form.recentlySuccessful" class="alert alert-success">
                            Factuurgegevens succesvol opgeslagen!
                        </div>
                        <div v-if="Object.keys(form.errors).length" class="alert alert-danger">
                            <ul>
                                <li v-for="(error, key) in form.errors" :key="key">{{ error }}</li>
                            </ul>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
