<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Aanvragen goedkeuren</h2>
        </template>

        <div class="py-8">
            <div class="mx-auto px-2 sm:px-6 lg:px-8">
                <div class="bg-white overflow-scroll shadow-sm rounded-md sm:rounded-lg border border-gray-200">
                    <div class="p-6">
                        <h2 class="mb-2 text-md uppercase font-semibold">Aanvragen goedkeuren</h2>
                        <hr class="mb-4">
                        <div class="relative overflow-x-auto">
                            <table class="w-full text-sm text-left rtl:text-right  text-gray-500 ">
                                <thead class=" text-gray-700 uppercase text-sm bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Aanvraag
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Datum
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Plaats
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Tijd
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Plekken
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Accepteren/Bekijken
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr class="odd:bg-white even:bg-gray-50  border-b hover:bg-gray-200">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                        Aanvraag titel één
                                    </th>
                                    <td class="px-6 py-4">
                                        02-03-2024
                                    </td>
                                    <td class="px-6 py-4">
                                        Zwolle
                                    </td>
                                    <td class="px-6 py-4">
                                        14:00 - 18:00
                                    </td>
                                    <td class="px-6 py-4">
                                        4 / <strong>6</strong>
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="#" class="inline-flex items-center justify-center w-6 h-6 bg-red-600 text-white rounded-full hover:bg-red-700 transition duration-200 ease-in-out mr-2"><i class="fa-solid fa-x"></i></a>
                                        <a href="#" class="inline-flex items-center justify-center w-6 h-6 bg-green-600 text-white rounded-full hover:bg-green-700 transition duration-200 ease-in-out mr-2"><i class="fa-solid fa-check"></i></a>
                                        <a :href="route('lotus-requests.viewlotusrequest')" class="inline-flex items-center justify-center w-6 h-6 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition duration-200 ease-in-out"><i class="fa-solid fa-info"></i></a>
                                    </td>
                                </tr>
                                <tr class="odd:bg-white even:bg-gray-50  border-b hover:bg-gray-200">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                        Aanvraag titel twee
                                    </th>
                                    <td class="px-6 py-4">
                                        12-05-2024
                                    </td>
                                    <td class="px-6 py-4">
                                        Staphorst
                                    </td>
                                    <td class="px-6 py-4">
                                        18:00 - 22:00
                                    </td>
                                    <td class="px-6 py-4">
                                        1 / <strong>6</strong>
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="#" class="inline-flex items-center justify-center w-6 h-6 bg-red-600 text-white rounded-full hover:bg-red-700 transition duration-200 ease-in-out mr-2"><i class="fa-solid fa-x"></i></a>
                                        <a href="#" class="inline-flex items-center justify-center w-6 h-6 bg-green-600 text-white rounded-full hover:bg-green-700 transition duration-200 ease-in-out mr-2"><i class="fa-solid fa-check"></i></a>
                                        <a :href="route('lotus-requests.viewlotusrequest')" class="inline-flex items-center justify-center w-6 h-6 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition duration-200 ease-in-out"><i class="fa-solid fa-info"></i></a>
                                    </td>
                                </tr>
                                <tr class="odd:bg-white even:bg-gray-50  border-b hover:bg-gray-200">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                        Aanvraag titel drie
                                    </th>
                                    <td class="px-6 py-4">
                                        23-05-2024
                                    </td>
                                    <td class="px-6 py-4">
                                        Zwolle
                                    </td>
                                    <td class="px-6 py-4">
                                        14:00 - 20:00
                                    </td>
                                    <td class="px-6 py-4">
                                        0 / <strong>2</strong>
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="#" class="inline-flex items-center justify-center w-6 h-6 bg-red-600 text-white rounded-full hover:bg-red-700 transition duration-200 ease-in-out mr-2"><i class="fa-solid fa-x"></i></a>
                                        <a href="#" class="inline-flex items-center justify-center w-6 h-6 bg-green-600 text-white rounded-full hover:bg-green-700 transition duration-200 ease-in-out mr-2"><i class="fa-solid fa-check"></i></a>
                                        <a :href="route('lotus-requests.viewlotusrequest')" class="inline-flex items-center justify-center w-6 h-6 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition duration-200 ease-in-out"><i class="fa-solid fa-info"></i></a>
                                    </td>
                                </tr>
                                <tr class="odd:bg-white even:bg-gray-50  border-b hover:bg-gray-200">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                        Aanvraag titel vier
                                    </th>
                                    <td class="px-6 py-4">
                                        07-06-2024
                                    </td>
                                    <td class="px-6 py-4">
                                        Zwolle
                                    </td>
                                    <td class="px-6 py-4">
                                        14:00 - 18:00
                                    </td>
                                    <td class="px-6 py-4">
                                        0 / <strong>6</strong>
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="#" class="inline-flex items-center justify-center w-6 h-6 bg-red-600 text-white rounded-full hover:bg-red-700 transition duration-200 ease-in-out mr-2"><i class="fa-solid fa-x"></i></a>
                                        <a href="#" class="inline-flex items-center justify-center w-6 h-6 bg-green-600 text-white rounded-full hover:bg-green-700 transition duration-200 ease-in-out mr-2"><i class="fa-solid fa-check"></i></a>
                                        <a :href="route('lotus-requests.viewlotusrequest')" class="inline-flex items-center justify-center w-6 h-6 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition duration-200 ease-in-out"><i class="fa-solid fa-info"></i></a>
                                    </td>
                                </tr>

                                <tr class="odd:bg-white even:bg-gray-50  border-b hover:bg-gray-200">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                        Aanvraag titel vijf
                                    </th>
                                    <td class="px-6 py-4">
                                        07-06-2024
                                    </td>
                                    <td class="px-6 py-4">
                                        Zwolle
                                    </td>
                                    <td class="px-6 py-4">
                                        14:00 - 18:00
                                    </td>
                                    <td class="px-6 py-4">
                                        0 / <strong>6</strong>
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="#" class="inline-flex items-center justify-center w-6 h-6 bg-red-600 text-white rounded-full hover:bg-red-700 transition duration-200 ease-in-out mr-2"><i class="fa-solid fa-x"></i></a>
                                        <a href="#" class="inline-flex items-center justify-center w-6 h-6 bg-green-600 text-white rounded-full hover:bg-green-700 transition duration-200 ease-in-out mr-2"><i class="fa-solid fa-check"></i></a>
                                        <a :href="route('lotus-requests.viewlotusrequest')" class="inline-flex items-center justify-center w-6 h-6 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition duration-200 ease-in-out"><i class="fa-solid fa-info"></i></a>
                                    </td>
                                </tr>

                                <tr class="odd:bg-white even:bg-gray-50  border-b hover:bg-gray-200">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                        Aanvraag titel zes
                                    </th>
                                    <td class="px-6 py-4">
                                        07-06-2024
                                    </td>
                                    <td class="px-6 py-4">
                                        Zwolle
                                    </td>
                                    <td class="px-6 py-4">
                                        14:00 - 18:00
                                    </td>
                                    <td class="px-6 py-4">
                                        0 / <strong>6</strong>
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="#" class="inline-flex items-center justify-center w-6 h-6 bg-red-600 text-white rounded-full hover:bg-red-700 transition duration-200 ease-in-out mr-2"><i class="fa-solid fa-x"></i></a>
                                        <a href="#" class="inline-flex items-center justify-center w-6 h-6 bg-green-600 text-white rounded-full hover:bg-green-700 transition duration-200 ease-in-out mr-2"><i class="fa-solid fa-check"></i></a>
                                        <a :href="route('lotus-requests.viewlotusrequest')" class="inline-flex items-center justify-center w-6 h-6 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition duration-200 ease-in-out"><i class="fa-solid fa-info"></i></a>
                                    </td>
                                </tr>


                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="pb-8">
            <div class="mx-auto px-2 sm:px-6 lg:px-8">
                <div class="bg-white overflow-scroll shadow-sm rounded-md sm:rounded-lg border border-gray-200">
                    <div class="p-6">
                        <h2 class="mb-2 text-md uppercase font-semibold">Goedgekeurde aanvragen</h2>
                        <hr class="mb-4">
                        <div class="relative overflow-x-auto">
                            <table class="w-full text-sm text-left rtl:text-right  text-gray-500 ">
                                <thead class=" text-gray-700 uppercase text-sm bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Aanvraag
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Datum
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Plaats
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Tijd
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Plekken
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Bekijken
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr class="odd:bg-white even:bg-gray-50  border-b hover:bg-gray-200">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                        Aanvraag titel één
                                    </th>
                                    <td class="px-6 py-4">
                                        02-03-2024
                                    </td>
                                    <td class="px-6 py-4">
                                        Zwolle
                                    </td>
                                    <td class="px-6 py-4">
                                        14:00 - 18:00
                                    </td>
                                    <td class="px-6 py-4">
                                        4 / <strong>6</strong>
                                    </td>
                                    <td class="px-6 py-4">
                                        <a :href="route('lotus-requests.viewlotusrequest')" class="inline-flex items-center justify-center w-6 h-6 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-200 ease-in-out"><i class="fa-solid fa-arrow-right"></i></a>
                                    </td>
                                </tr>
                                <tr class="odd:bg-white even:bg-gray-50  border-b hover:bg-gray-200">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                        Aanvraag titel twee
                                    </th>
                                    <td class="px-6 py-4">
                                        12-05-2024
                                    </td>
                                    <td class="px-6 py-4">
                                        Staphorst
                                    </td>
                                    <td class="px-6 py-4">
                                        18:00 - 22:00
                                    </td>
                                    <td class="px-6 py-4">
                                        1 / <strong>6</strong>
                                    </td>
                                    <td class="px-6 py-4">
                                        <a :href="route('lotus-requests.viewlotusrequest')" class="inline-flex items-center justify-center w-6 h-6 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-200 ease-in-out"><i class="fa-solid fa-arrow-right"></i></a>
                                    </td>
                                </tr>
                                <tr class="odd:bg-white even:bg-gray-50  border-b hover:bg-gray-200">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                        Aanvraag titel drie
                                    </th>
                                    <td class="px-6 py-4">
                                        23-05-2024
                                    </td>
                                    <td class="px-6 py-4">
                                        Zwolle
                                    </td>
                                    <td class="px-6 py-4">
                                        14:00 - 20:00
                                    </td>
                                    <td class="px-6 py-4">
                                        1 / <strong>2</strong>
                                    </td>
                                    <td class="px-6 py-4">
                                        <a :href="route('lotus-requests.viewlotusrequest')" class="inline-flex items-center justify-center w-6 h-6 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-200 ease-in-out"><i class="fa-solid fa-arrow-right"></i></a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="pb-8">
            <div class="mx-auto px-2 sm:px-6 lg:px-8">
                <div class="bg-white overflow-scroll shadow-sm rounded-md sm:rounded-lg border border-gray-200">
                    <div class="p-6">
                        <h2 class="mb-2 text-md uppercase font-semibold">Afgewezen aanvragen</h2>
                        <hr class="mb-4">
                        <div class="relative overflow-x-auto">
                            <table class="w-full text-sm text-left rtl:text-right  text-gray-500 ">
                                <thead class=" text-gray-700 uppercase text-sm bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Aanvraag
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Datum
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Plaats
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Tijd
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Plekken
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Bekijken
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr class="odd:bg-white even:bg-gray-50  border-b hover:bg-gray-200">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                        Aanvraag titel één
                                    </th>
                                    <td class="px-6 py-4">
                                        02-03-2024
                                    </td>
                                    <td class="px-6 py-4">
                                        Zwolle
                                    </td>
                                    <td class="px-6 py-4">
                                        14:00 - 18:00
                                    </td>
                                    <td class="px-6 py-4">
                                        4 / <strong>6</strong>
                                    </td>
                                    <td class="px-6 py-4">
                                        <a :href="route('lotus-requests.viewlotusrequest')" class="inline-flex items-center justify-center w-6 h-6 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-200 ease-in-out"><i class="fa-solid fa-arrow-right"></i></a>
                                    </td>
                                </tr>
                                <tr class="odd:bg-white even:bg-gray-50  border-b hover:bg-gray-200">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                        Aanvraag titel twee
                                    </th>
                                    <td class="px-6 py-4">
                                        12-05-2024
                                    </td>
                                    <td class="px-6 py-4">
                                        Staphorst
                                    </td>
                                    <td class="px-6 py-4">
                                        18:00 - 22:00
                                    </td>
                                    <td class="px-6 py-4">
                                        1 / <strong>6</strong>
                                    </td>
                                    <td class="px-6 py-4">
                                        <a :href="route('lotus-requests.viewlotusrequest')" class="inline-flex items-center justify-center w-6 h-6 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-200 ease-in-out"><i class="fa-solid fa-arrow-right"></i></a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
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
