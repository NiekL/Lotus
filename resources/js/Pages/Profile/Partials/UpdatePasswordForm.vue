<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const showPassword = ref({});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Wachtwoord aanpassen</h2>

            <p class="mt-1 text-sm text-gray-600">
                Zorg voor een lang en veilig wachtwoord.
            </p>
        </header>

        <form @submit.prevent="updatePassword" class="mt-6 space-y-6">
            <div class="relative">
                <InputLabel for="current_password" value="Huidig wachtwoord" />

                <TextInput
                    id="current_password"
                    ref="currentPasswordInput"
                    v-model="form.current_password"
                    :type="showPassword['current_password'] ? 'text' : 'password'"
                    class="mt-1 block w-full"
                    autocomplete="current-password"
                />
                <button
                    type="button"
                    @click="showPassword['current_password'] = !showPassword['current_password']"
                    class="absolute bottom-1 h-fit right-0 flex items-end pr-3 pb-2"
                >
                    <i :class="showPassword['current_password'] ? 'fa-eye-slash' : 'fa-eye'" class="text-gray-500 hover:text-gray-700 fa-regular" />
                </button>
                <InputError :message="form.errors.current_password" class="mt-2" />
            </div>

            <div class="relative">
                <InputLabel for="password" value="Nieuw wachtwoord" />

                <TextInput
                    id="password"
                    ref="passwordInput"
                    v-model="form.password"
                    :type="showPassword['password'] ? 'text' : 'password'"
                    class="mt-1 block w-full"
                    autocomplete="new-password"
                />
                <button
                    type="button"
                    @click="showPassword['password'] = !showPassword['password']"
                    class="absolute bottom-1 h-fit right-0 flex items-end pr-3 pb-2"
                >
                    <i :class="showPassword['password'] ? 'fa-eye-slash' : 'fa-eye'" class="text-gray-500 hover:text-gray-700 fa-regular" />
                </button>
                <InputError :message="form.errors.password" class="mt-2" />
            </div>

            <div class="relative">
                <InputLabel for="password_confirmation" value="Bevestig wachtwoord" />

                <TextInput
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    :type="showPassword['password_confirmation'] ? 'text' : 'password'"
                    class="mt-1 block w-full"
                    autocomplete="new-password"
                />
                <button
                    type="button"
                    @click="showPassword['password_confirmation'] = !showPassword['password_confirmation']"
                    class="absolute bottom-1 h-fit right-0 flex items-end pr-3 pb-2"
                >
                    <i :class="showPassword['password_confirmation'] ? 'fa-eye-slash' : 'fa-eye'" class="text-gray-500 hover:text-gray-700 fa-regular" />
                </button>

                <InputError :message="form.errors.password_confirmation" class="mt-2" />
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Opslaan</PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Opgeslagen.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
