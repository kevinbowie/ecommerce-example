<script setup>
import BreezeAuthenticatedLayout from "@admin/Layouts/Authenticated.vue";
import { Head } from "@inertiajs/inertia-vue3";
import Container from "@admin/Components/Container.vue";
import Card from "../Components/Card.vue";
</script>

<template>
    <Head title="Dashboard" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <Container>
            <Card> You're logged in! </Card>
        </Container>
    </BreezeAuthenticatedLayout>
</template>
