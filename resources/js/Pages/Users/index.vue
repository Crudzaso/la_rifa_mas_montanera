<template>
    <div class="container mx-auto p-6">
        <h1 class="text-4xl font-semibold mb-8 text-gray-900">Lista de Usuarios</h1>

        <!-- Table -->
        <div class="overflow-x-auto bg-white shadow-lg rounded-lg">
            <table class="min-w-full text-sm text-gray-800">
                <thead class="bg-gradient-to-r from-indigo-500 to-blue-500 text-white">
                    <tr>
                        <th class="py-3 px-4 border-b text-center font-semibold">ID</th>
                        <th class="py-3 px-4 border-b text-center font-semibold">Nombre</th>
                        <th class="py-3 px-4 border-b text-center font-semibold">Email</th>
                        <th class="py-3 px-4 border-b text-center font-semibold">Fecha de Creación</th>
                        <th class="py-3 px-4 border-b text-center font-semibold">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="user in users"
                        :key="user.id"
                        class="hover:bg-gray-50 transition duration-200"
                    >
                        <td class="py-2 px-4 border-b text-center">{{ user.id }}</td>
                        <td class="py-2 px-4 border-b font-medium text-gray-900">{{ user.name }}</td>
                        <td class="py-2 px-4 border-b">{{ user.email }}</td>
                        <td class="py-2 px-4 border-b">
                            {{ new Date(user.created_at).toLocaleDateString() }}
                        </td>
                        <td class="py-2 px-4 border-b text-center">
                            <!-- Action buttons with modern icons -->
                            <div class="flex justify-center space-x-4">
                                <!-- View button -->
                                <button
                                    @click="viewUser(user.id)"
                                    class="text-blue-500 hover:text-blue-700 focus:outline-none"
                                >
                                    <i class="fas fa-eye"></i>
                                </button>
                                <!-- Edit button -->
                                <button
                                    @click="editUser(user.id)"
                                    class="text-yellow-500 hover:text-yellow-600 focus:outline-none"
                                >
                                    <i class="fas fa-pencil-alt"></i>
                                </button>
                                <!-- Delete button -->
                                <button
                                    @click="deleteUser(user.id)"
                                    class="text-red-500 hover:text-red-700 focus:outline-none"
                                >
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        users: {
            type: Array,
            required: true,
        },
    },
    methods: {
        // Ver detalles del usuario
        viewUser(id) {
            console.log("Ver usuario con ID:", id);
            // Aquí puedes redirigir o abrir un modal con los detalles del usuario
        },
        // Editar un usuario
        editUser(id) {
            console.log("Editar usuario con ID:", id);
            // Aquí puedes redirigir a una página de edición o abrir un formulario
        },
        // Eliminar un usuario
        deleteUser(id) {
            if (confirm("¿Estás seguro de que deseas eliminar este usuario?")) {
                console.log("Eliminar usuario con ID:", id);
                // Aquí debes implementar la lógica de eliminación
            }
        },
    },
};
</script>

<style scoped>
/* Estilos adicionales */
table {
    width: 100%;
    border-collapse: collapse;
}
th,
td {
    text-align: center;
    padding: 12px;
}
th {
    background-color: #4c51bf;
    font-weight: 600;
    color: #ffffff;
    text-transform: uppercase;
    border : solid 1px #25577e;
}
td {
    background-color: #fff;
    border: solid 1px #25577e;
}
tr:hover td {
    background-color: #f9fafb;
}
button {
    padding: 8px 8px;
    border-radius: 4px;
    border: none;
    cursor: pointer;
    transition: all 0.3s;
}
button:focus {
    outline: none;
    box-shadow: 0 0 0 2px rgba(66, 153, 225, 0.5);
}
i {
    font-size: 1.2rem;
}
</style>
