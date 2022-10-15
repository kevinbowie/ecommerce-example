<script setup>
import Container from "@admin/Components/Container.vue";
import BreezeAuthenticatedLayout from "@admin/Layouts/Authenticated.vue";
import { Head } from "@inertiajs/inertia-vue3";
import Card from "@/admin/Components/Card.vue";
import Table from "@/admin/Components/Table/Table.vue";
import Td from "@/admin/Components/Table/Td.vue";
import Actions from "@/admin/Components/Table/Actions.vue";
import Button from "@admin/Components/Button.vue";
import Modal from "@admin/Components/Modal.vue";

import useDeleteItem from "@admin/Composables/useDeleteItem.js";
import useFilter from "@admin/Composables/useFilter";
import Filter from "./Filter.vue";

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    items: {
        type: Object,
        default: () => ({}),
    },
    headers: {
        type: Array,
        default: () => [],
    },
    filters: {
        type: Object,
        default: {},
    },
    routeResourceName: {
        type: String,
        required: true,
    },
    rootCategories: Array,
    can: Object,
});

const {
    deleteModal,
    itemToDelete,
    isDeleting,
    showDeleteModal,
    handleDeleteItem,
} = useDeleteItem({
    routeResourceName: props.routeResourceName,
});

const { filters, isLoading } = useFilter({
    filters: props.filters,
    routeResourceName: props.routeResourceName,
});
</script>

<template>
    <Head :title="title" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ title }}
            </h2>
        </template>

        <Container>
            <Filter v-model="filters" :categories="rootCategories" />

            <Button
                v-if="can.create"
                :href="route(`admin.${routeResourceName}.create`)"
                class="mt-3"
                >Add New</Button
            >

            <Card class="mt-4" :is-loading="isLoading">
                <Table :headers="headers" :items="items">
                    <template v-slot="{ item }">
                        <Td>{{ item.name }}</Td>
                        <Td>
                            <Button
                                v-if="item.children_count > 0"
                                :href="
                                    route(`admin.${routeResourceName}.index`, {
                                        parentId: item.id,
                                    })
                                "
                                small
                                >{{ item.children_count }}</Button
                            >
                            <span v-else>{{ item.children_count }}</span>
                        </Td>
                        <Td>
                            <Button
                                small
                                :color="item.active ? 'green' : 'red'"
                                >{{
                                    item.active ? "active" : "inactive"
                                }}</Button
                            >
                        </Td>
                        <Td>{{ item.created_at_formatted }}</Td>
                        <Td>
                            <Actions
                                :edit-link="
                                    route(`admin.${routeResourceName}.edit`, {
                                        id: item.id,
                                    })
                                "
                                :show-edit="item.can.edit"
                                :show-delete="item.can.delete"
                                @deleteClicked="showDeleteModal(item)"
                            />
                        </Td>
                    </template>
                </Table>
            </Card>
        </Container>
    </BreezeAuthenticatedLayout>

    <Modal v-if="deleteModal" size="sm" :title="`Delete ${itemToDelete.name}`">
        Are you sure want to delete this item?
        <template #footer>
            <Button @click="handleDeleteItem" :disabled="isDeleting">
                <span>{{ isDeleting ? "Deleting" : "Delete" }}</span>
            </Button>
        </template>
    </Modal>
</template>
