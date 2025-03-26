<script setup>
import {defineProps, ref, computed} from "vue";
import "https://kit.fontawesome.com/bf86a3d082.js";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import NavLink from "@/Components/NavLink.vue";
import VersionComponent from "@/Components/VersionComponent.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import { usePage, Link } from "@inertiajs/vue3";

const showingNavigationDropdown = ref(false);

//User Roles
const page = usePage();
const userRoles = computed(() => page.props.auth.user?.roles || []);

const isAdmin = computed(() => userRoles.value.includes("admin"));
const isCoordinator = computed(() => userRoles.value.includes("coordinator"));
const isKlant = computed(() => userRoles.value.includes("klant"));
const isLid = computed(() => userRoles.value.includes("lid"));
const isPenningmeester = computed(() => userRoles.value.includes("penningmeester"));
const isSecretaris = computed(() => userRoles.value.includes("secretaris"));


</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100" id="top-of-app">
            <nav
                class="bg-white border-b border-gray-200 fixed z-30 w-full border-b border-gray-200"
            >
                <div class="mx-auto px-4 lg:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('dashboard')">
                                    <ApplicationLogo
                                        class="block h-9 w-auto fill-current text-gray-800"
                                    />
                                </Link>
                            </div>
                        </div>

                        <div class="hidden lg:flex lg:items-center lg:ms-6">
                            <!-- Settings Dropdown -->
                            <div class="ms-3 relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="profile-button inline-flex items-center justify-center px-3 py-2 border border-transparent text-xl leading-4 text-center  center font-bold uppercase rounded-md text-white bg-red-600 hover:text-grey-50 focus:outline-none transition ease-in-out duration-150"
                                            >
                                                {{ $page.props.auth.user.name.charAt(0) }}
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink
                                            :href="route('logout')"
                                            method="post"
                                            as="button"
                                        >
                                            Uitloggen
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center lg:hidden">
                            <button
                                @click="
                                    showingNavigationDropdown =
                                        !showingNavigationDropdown
                                "
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                            >
                                <svg
                                    class="h-6 w-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex':
                                                !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex':
                                                showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </nav>
            <div class="flex pt-16 overflow-hidden">
                <aside
                    :class="{
                        block: showingNavigationDropdown,
                        hidden: !showingNavigationDropdown,
                    }"
                    class="fixed top-0 left-0 z-20 mx-auto px-4 lg:px-8 flex flex-col flex-shrink-0 w-64 border-r border-gray-200 bg-white h-full pt-24 font-normal duration-75 lg:flex transition-width"
                >
                    <!-- Navigation Links -->
                    <div class="space-y-4 lg:-my-px flex flex-col">
                        <NavLink
                            :href="route('dashboard')"
                            :active="route().current('dashboard')"
                        >
                        <i class="fa-solid fa-table"></i>Overzicht
                        </NavLink>
                        <hr>

                        <!-- MAKE THIS ACCESSIBLE FOR 'admin', 'coordinator', 'klant' -->
                        <NavLink
                            v-if="isAdmin || isCoordinator || isKlant"
                            :href="route('lotus-requests.create')"
                            :active="route().current('lotus-requests.create')"
                        >
                            <i class="fa-solid fa-plus"></i>Aanvraag aanmaken
                        </NavLink>

                        <NavLink
                            v-if="isAdmin || isLid"
                            :href="route('lotus-requests.mylotusrequests')"
                            :active="route().current('lotus-requests.mylotusrequests')"
                        >
                            <i class="fa-regular fa-calendar-check"></i>Mijn aanvragen
                        </NavLink>

                        <NavLink
                            v-if="isAdmin || isKlant"
                            :href="route('lotus-requests.mycustomerlotusrequests')"
                            :active="route().current('lotus-requests.mycustomerlotusrequests')"
                        >
                            <i class="fa-regular fa-calendar-check"></i>Aanvragen inzien<span v-if="isAdmin">(Alleen klanten)</span>
                        </NavLink>

                        <NavLink
                            v-if="isAdmin || isCoordinator || isLid || isPenningmeester || isSecretaris"
                            :href="route('lotus-requests.openlotusrequests')"
                            :active="route().current('lotus-requests.openlotusrequests')"
                        >
                        <i class="fa-regular fa-calendar-days"></i>Beschikbare aanvragen
                        </NavLink>

                        <NavLink
                            v-if="isAdmin || isCoordinator"
                            :href="route('lotus-requests.acceptlotusrequests')"
                            :active="route().current('lotus-requests.acceptlotusrequests')"
                        >
                            <i class="fa-solid fa-square-check"></i>Aanvragen goedkeuren
                        </NavLink>

                        <hr v-if="isAdmin || isCoordinator || isLid || isPenningmeester || isSecretaris">

                        <NavLink
                            v-if="isAdmin || isCoordinator || isLid || isPenningmeester || isSecretaris"
                            :href="route('users.memberadministration')"
                            :active="route().current('users.memberadministration')"
                        >
                            <i class="fa-solid fa-user"></i>Leden administratie
                        </NavLink>

                        <NavLink
                            v-if="isAdmin || isCoordinator || isPenningmeester || isSecretaris"
                            :href="route('users.customeradministration')"
                            :active="route().current('users.customeradministration')"
                        >
                            <i class="fa-solid fa-user-tie"></i>Klanten administratie
                        </NavLink>

                        <hr v-if="isAdmin || isCoordinator || isLid || isPenningmeester || isSecretaris">

                        <NavLink
                            v-if="isAdmin || isCoordinator || isLid || isPenningmeester || isSecretaris"
                            :href="route('declarationinfo')"
                            :active="route().current('declarationinfo')"
                        >
                            <i class="fa-solid fa-credit-card"></i>Declaratie info
                        </NavLink>

                        <hr>

                        <NavLink
                            v-if="isAdmin || isKlant"
                            :href="route('profile.invoiceinfo')"
                            :active="route().current('profile.invoiceinfo')"
                        >
                            <i class="fa-solid fa-file-invoice-dollar"></i>Factuurgegevens
                        </NavLink>

                        <NavLink
                            :href="route('profile.edit')"
                            :active="route().current('profile.edit')"
                        >
                            <i class="fa-solid fa-user-pen"></i>Profiel
                        </NavLink>





                    </div>
                </aside>

                <div
                    class="fixed inset-0 z-10 hidden"
                    id="sidebarBackdrop"
                ></div>

                <!-- Page Content -->
                <div
                    id="main-content"
                    class="relative w-full h-full overflow-y-auto lg:ml-64"
                >
                    <main>
                        <slot />
                        <VersionComponent />
                    </main>
                </div>
            </div>
        </div>
    </div>
</template>
