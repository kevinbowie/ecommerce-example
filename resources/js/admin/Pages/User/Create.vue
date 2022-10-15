<script setup>
import Container from "@admin/Components/Container.vue";
import BreezeAuthenticatedLayout from "@admin/Layouts/Authenticated.vue";
import { Head, useForm } from "@inertiajs/inertia-vue3";
import { onMounted } from "vue";
import Card from "@/admin/Components/Card.vue";
import Button from "@admin/Components/Button.vue";
import InputGroup from "@/admin/Components/InputGroup.vue";
import SelectGroup from "@/admin/Components/SelectGroup.vue";

const props = defineProps({
    edit: {
        type: Boolean,
        default: false,
    },
    item: {
        type: Object,
        default: {},
    },
    title: {
        type: String,
    },
    routeResourceName: {
        type: String,
        required: true,
    },
    roles: {
        type: Array,
        required: true,
    },
});

const form = useForm({
    name: "",
    email: "",
    password: "",
    passwordConfirmation: "",
    roleId: "",
});

const submit = () => {
    props.edit
        ? form.put(
              route(`admin.${props.routeResourceName}.update`, {
                  id: props.item.id,
              })
          )
        : form.post(route(`admin.${props.routeResourceName}.store`));
};

onMounted(() => {
    if (props.edit) {
        form.name = props.item.name;
        form.email = props.item.email;
        form.roleId = props.item.roles[0]?.id;
    }
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
            <Card>
                <form @submit.prevent="submit">
                    <div class="grid grid-cols-2 gap-6">
                        <InputGroup
                            label="Name"
                            v-model="form.name"
                            required
                            :error-message="form.errors.name"
                        />

                        <InputGroup
                            label="Email"
                            v-model="form.email"
                            required
                            :error-message="form.errors.email"
                            type="email"
                        />

                        <InputGroup
                            label="Password"
                            v-model="form.password"
                            :required="!edit"
                            :error-message="form.errors.password"
                            type="password"
                        />

                        <InputGroup
                            label="Password Confirmation"
                            v-model="form.passwordConfirmation"
                            :required="!edit"
                            :error-message="form.errors.passwordConfirmation"
                            type="password"
                        />

                        <SelectGroup
                            label="Role"
                            v-model="form.roleId"
                            :items="roles"
                            :error-message="form.errors.roleId"
                            required
                        />
                    </div>

                    <div class="mt-4">
                        <Button :disabled="form.processing">
                            {{ form.processing ? "Saving..." : "Save" }}</Button
                        >
                    </div>
                </form>
            </Card>
        </Container>
    </BreezeAuthenticatedLayout>
</template>
