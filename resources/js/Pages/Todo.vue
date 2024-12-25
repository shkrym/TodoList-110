<template>
    <Head title="Dashboard" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Todo
            </h2>
        </template>

        <!-- component -->
        <div class="h-100 w-full flex items-center justify-center bg-teal-lightest font-sans">
            <div class="bg-white rounded shadow p-6 mt-12 m-4 w-full lg:w-3/4 lg:max-w-lg">
                <div class="mb-4">
                    <h1 class="text-grey-darkest">Todo List</h1>
                    <div class="flex mt-4 mb-6">
                        <input
                            class="shadow appearance-none border rounded w-full py-2 px-3 mr-4 text-grey-darker"
                            placeholder="Add Todo"
                            v-model="form.content"
                        />
                        <button
                            class="flex-no-shrink p-2 border-2 rounded-lg text-teal border-teal text-white bg-blue-500 hover:bg-blue-700"
                            @click="submit"
                        >
                            Add
                        </button>
                    </div>
                </div>

                <div
                    class="flex mb-4 items-center justify-between"
                    v-for="todo in todos"
                    :key="todo.id"
                >
                    <div class="flex items-center w-full">
                        <!-- Edit Mode -->
                        <div v-if="todo.isEditing" class="flex w-full ">
                            <input
                                class="shadow appearance-none border rounded w-full py-2 px-3 mr-4 text-grey-darker  bg-gray-100"
                                v-model="todo.content"
                                placeholder="Edit Todo"
                            />
                            <button
                                class="flex-no-shrink p-2 ml-2 border-2 rounded-lg text-green border-green text-white bg-green-500 hover:bg-green-700"
                                @click="saveEdit(todo)"
                            >
                                Save
                            </button>
                            <button
                                class="flex-no-shrink p-2 ml-2 border-2 rounded-lg text-gray border-gray text-white bg-gray-500 hover:bg-gray-700"
                                @click="cancelEdit(todo)"
                            >
                                Cancel
                            </button>
                        </div>

                        <!-- View Mode -->
                        <div v-else class="flex items-center justify-between w-full">
                            <div class="flex items-center">
                                <button
                                    v-if="todo.is_done"
                                    class="flex-no-shrink p-2 ml-4 border-2 rounded-lg border-grey"
                                    @click="updateStatus(todo)"
                                >
                                    Not Done
                                </button>
                                <button
                                    v-else
                                    class="flex-no-shrink p-2 ml-4 border-2 rounded-lg border-green text-white bg-green-500 hover:bg-green-700"
                                    @click="updateStatus(todo)"
                                >
                                    Done
                                </button>
                                <p class="ml-4 text-grey-darkest">{{ todo.content }}</p>
                            </div>
                            <div class="flex items-center">
                                <button
                                    class="edit-button flex items-center justify-center w-10 h-10 bg-gray-200 text-white rounded-full transition-all duration-300 overflow-hidden hover:bg-gray-400 "
                                    @click="editTodo(todo)"
                                >
                                    <svg
                                        class="edit-svgIcon w-4 h-4 transition-all duration-300"
                                        viewBox="0 0 512 512"
                                    >
                                        <path
                                            d="M410.3 231l11.3-11.3-33.9-33.9-62.1-62.1L291.7 89.8l-11.3 11.3-22.6 22.6L58.6 
                                            322.9c-10.4 10.4-18 23.3-22.2 37.4L1 480.7c-2.5 8.4-.2 17.5 6.1 23.7s15.3 8.5 23.7 
                                            6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L387.7 253.7 410.3 231zM160 399.4l-9.1 
                                            22.7c-4 3.1-8.5 5.4-13.3 6.9L59.4 452l23-78.1c1.4-4.9 3.8-9.4 6.9-13.3l22.7-9.1v32c0 
                                            8.8 7.2 16 16 16h32zM362.7 18.7L348.3 33.2 325.7 55.8 314.3 67.1l33.9 33.9 62.1 62.1 
                                            33.9 33.9 11.3-11.3 22.6-22.6 14.5-14.5c25-25 25-65.5 0-90.5L453.3 18.7c-25-25-65.5-25-90.5 
                                            0zm-47.4 168l-144 144c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6l144-144c6.2-6.2 16.4-6.2 22.6 
                                            0s6.2 16.4 0 22.6z"
                                        />
                                    </svg>
                                </button>
                                <button
                                    class="flex-no-shrink p-2 ml-2 border-2 rounded-lg text-red border-red text-white bg-red-500 hover:bg-red-700"
                                    @click="deleteTodo(todo)"
                                >
                                    Remove
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>

<script>
import BreezeAuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";
import { reactive } from "vue";

export default {
    components: {
        BreezeAuthenticatedLayout,
        Head,
    },
    props: {
        todos: Array,
    },
    setup() {
        const form = reactive({
            content: null,
        });

        function submit() {
            if (form.content != null) {
                Inertia.post("/todos", form);
                form.content = null;
            }
        }

        function editTodo(todo) {
            todo.isEditing = true;
            todo.originalContent = todo.content;
        }

        function saveEdit(todo) {
            todo.isEditing = false;
            Inertia.put("/todos/" + todo.id + "/update", {
                content: todo.content,
            });
        }

        function cancelEdit(todo) {
            todo.isEditing = false;
            todo.content = todo.originalContent;
        }

        function updateStatus(todo) {
            Inertia.put("/todos/" + todo.id + "/update", {
                is_done: !todo.is_done,
            });
        }

        function deleteTodo(todo) {
            Inertia.delete("/todos/" + todo.id);
        }

        return {
            form,
            submit,
            updateStatus,
            deleteTodo,
            editTodo,
            saveEdit,
            cancelEdit,
        };
    },
};
</script>
