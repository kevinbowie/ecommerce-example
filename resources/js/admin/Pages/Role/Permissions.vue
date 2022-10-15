<script setup>
import { computed, ref } from "vue";
import Container from "@/admin/Components/Container.vue";
import Card from "@/admin/Components/Card.vue";
import Button from "@admin/Components/Button.vue";
import Input from "@admin/Components/Input.vue";
import { Inertia } from "@inertiajs/inertia";

const props = defineProps({
    role: {
        type: Object,
        default: () => {
            permissions: [];
        },
    },
    permissions: {
        type: Array,
    },
});

const search = ref("");

const filteredPermissions = computed(() => {
    return props.permissions.filter((p) =>
        p.name.toLowerCase().includes(search.value.toLowerCase())
    );
});

const roleHasPermission = (permission) => {
    return props.role.permissions.some((p) => p.id === permission.id);
};

const attachPermission = (permission) => {
    Inertia.post(
        route("admin.role.attach-permission"),
        {
            role_id: props.role.id,
            permission_id: permission.id,
        },
        {
            preserveScroll: true,
            preserveState: true,
        }
    );
};

const detachPermission = (permission) => {
    Inertia.post(
        route("admin.role.detach-permission"),
        {
            role_id: props.role.id,
            permission_id: permission.id,
        },
        {
            preserveScroll: true,
            preserveState: true,
        }
    );
};
</script>

<template>
    <Container>
        <Card>
            <template #header> Permissions </template>

            <div class="w-1/4">
                <Input
                    type="text"
                    class="mt-1 block w-full"
                    v-model="search"
                    placeholder="Search"
                />
            </div>

            <div class="mt-4">
                <li
                    v-for="(permission, index) in filteredPermissions"
                    :key="permission.id"
                    class="px-2 py-2 flex items-center justify-between hover:bg-gray-100"
                    :class="{
                        'boder-b': index < permissions.length - 1,
                    }"
                >
                    <div
                        :class="{
                            'text-green-700 font-bold':
                                roleHasPermission(permission),
                        }"
                    >
                        {{ permission.name }}
                    </div>

                    <Button
                        v-if="roleHasPermission(permission)"
                        @click="detachPermission(permission)"
                        color="green"
                        >Detach</Button
                    >
                    <Button v-else @click="attachPermission(permission)"
                        >Attach</Button
                    >
                </li>
            </div>
        </Card>
    </Container>
</template>
