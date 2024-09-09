<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tareas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>
    <!-- Navbar -->
    <nav class="bg-blue-600 p-4 text-white">
        <div class="container mx-auto flex justify-between items-center">
            <div class="text-lg font-bold flex gap-4">
                <a href="{{ route('tasks.index') }}"
                    class="hover:underline hover:text-blue-300 transition duration-300 ease-in-out">Tareas</a>
                <a href="{{ route('users.index') }}"
                    class="hover:underline hover:text-blue-300 transition duration-300 ease-in-out">Usuarios</a>
            </div>
        </div>
    </nav>

    <!-- Mensajes de éxito y error -->
    <div class="relative sm:w-96 w-11/12 mx-auto mb-4">
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative shadow-lg"
                role="alert" style="top:1rem">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative shadow-lg"
                role="alert" style="top:1rem">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 mb-3 border border-red-400 text-red-700 px-4 py-3 rounded relative shadow-lg"
                role="alert" style="top:1rem">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Contenedor principal para formulario y tareas -->
    <div class="flex flex-col md:flex-row gap-6 mx-auto px-4 py-6">
        <!-- Formulario para crear una nueva tarea -->
        <div class="flex-1 bg-white shadow-xl sm:rounded-3xl p-8 text-left sticky top-0">
            <h1 class="text-3xl font-bold mb-6 text-gray-700">Crear Nueva Tarea</h1>
            <form action="{{ route('tasks.store') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Título</label>
                    <input type="text" name="title" id="title" required
                        class="w-full px-4 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
                    <textarea name="description" id="description" required
                        class="w-full px-4 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>
                </div>
                <div>
                    <label for="user" class="block text-sm font-medium text-gray-700">Correo del Usuario</label>
                    <input type="email" name="user" id="user" required
                        class="w-full px-4 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
                <button type="submit"
                    class="w-full bg-blue-500 text-white py-2 rounded-lg text-sm hover:bg-blue-600 transition duration-300 ease-in-out transform hover:scale-105">Crear
                    Tarea</button>
            </form>
        </div>

        <div class="flex-1 bg-white shadow-xl sm:rounded-3xl p-8 text-left max-h-screen overflow-y-auto sticky top-0">
            <form method="GET" action="{{ route('tasks.index') }}" class="mb-4">
                <label for="status" class="mr-2 text-gray-700">Filtrar por estado:</label>
                <select id="status" name="status" class="form-select h-10 w-40 border-gray-300 rounded-lg">
                    <option value="">Todas</option>
                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Completadas</option>
                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>No Completadas</option>
                </select>
                <button type="submit"
                    class="ml-2 bg-blue-500 text-white py-2 px-4 rounded-lg text-sm hover:bg-blue-600 transition duration-300 ease-in-out">Filtrar</button>
            </form>
            @if (!empty($message))
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-lg shadow-lg"
                    role="alert">
                    <p>{{ $message }}</p>
                </div>
            @else
                <h1 class="text-2xl font-bold mb-4 text-gray-700">Lista de Tareas</h1>
                @foreach ($tasks as $task)
                    <div
                        class="p-6 bg-gray-50 rounded-lg shadow-md hover:bg-gray-100 transition duration-300 ease-in-out transform hover:scale-105 mb-4">
                        <h2 class="text-xl font-semibold text-gray-800">{{ $task->title }}</h2>
                        <p class="text-sm text-gray-600 mt-2">{{ $task->description }}</p>
                        <p class="text-sm text-gray-500 mt-1">Asignada a: {{ $task->user->email }}</p>
                        <div class="mt-4 flex items-center">
                            <span class="text-sm text-gray-800 mr-2">Estado tarea:</span>
                            @if ($task->completed === 1)
                                <i class="fas fa-check text-green-500"></i>
                            @else
                                <i class="fas fa-times text-red-500"></i>
                            @endif
                        </div>
                        <div class="mt-4 flex gap-2">
                            <label class="flex items-center gap-2">
                                <input type="checkbox" id="task-status-checkbox-{{ $task->id }}"
                                    onchange="updateTaskStatus({{ $task->id }})"
                                    {{ $task->completed === 1 ? 'checked' : 'unchecked' }}
                                    class="form-checkbox h-5 w-5 text-green-600 transition duration-150 ease-in-out">
                                <span class="text-sm text-gray-700">Tarea Completa</span>
                            </label>
                            <!-- Botón para abrir el modal -->
                            <button
                                onclick="openEditModal({{ $task->id }}, '{{ $task->title }}', '{{ $task->description }}')"
                                class="bg-green-500 text-white py-2 px-4 rounded-lg text-sm hover:bg-green-600 transition duration-300 ease-in-out transform hover:scale-105">Editar
                                Tarea</button>

                            <!-- Botón para eliminar tarea -->
                            <button onclick="deleteTask({{ $task->id }})"
                                class="bg-red-500 text-white py-2 px-4 rounded-lg text-sm hover:bg-red-600 transition duration-300 ease-in-out transform hover:scale-105">Eliminar
                                Tarea</button>

                        </div>
                    </div>
                @endforeach

            @endif
        </div>
    </div>

    <!-- Modal para editar tarea -->
    <div id="edit-modal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 hidden">
        <div class="bg-white rounded-lg shadow-xl max-w-sm mx-auto p-6 space-y-4">
            <h2 class="text-xl font-bold text-gray-800">Actualizar Tarea</h2>
            <form id="edit-task-form" class="max-w-lg p-8">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="edit-title" class="block text-lg font-medium text-gray-700">Título</label>
                    <input type="text" id="edit-title"
                        class="w-full px-4 py-3 border rounded-lg text-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
                <div class="mb-4">
                    <label for="edit-description" class="block text-lg font-medium text-gray-700">Descripción</label>
                    <textarea id="edit-description"
                        class="w-full px-4 py-3 border rounded-lg text-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                        rows="4"></textarea>
                </div>
                <button type="button" onclick="updateTask()"
                    class="w-full bg-blue-500 text-white py-3 rounded-lg text-lg hover:bg-blue-600 transition duration-300 ease-in-out transform hover:scale-105">Actualizar
                    Tarea</button>
                <button type="button" onclick="closeEditModal()"
                    class="w-full bg-gray-500 text-white py-3 rounded-lg text-lg hover:bg-gray-600 transition duration-300 ease-in-out transform hover:scale-105 mt-4">Cerrar</button>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(id, title, description) {
            document.getElementById('edit-task-form').action = `/tasks/${id}`;
            document.getElementById('edit-title').value = title;
            document.getElementById('edit-description').value = description;

            currentTaskId = id;
            document.getElementById('edit-modal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('edit-modal').classList.add('hidden');
        }

        function deleteTask(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'No podrás revertir esto!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete(`/tasks/${id}`)
                        .then(response => {
                            Swal.fire(
                                'Eliminado!',
                                'La tarea ha sido eliminada.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        })
                        .catch(error => {
                            console.error('Hubo un error al eliminar la tarea:', error);
                            Swal.fire(
                                'Error!',
                                'Hubo un problema al eliminar la tarea.',
                                'error'
                            );
                        });
                }
            });
        }


        function updateTask() {

            const title = document.getElementById('edit-title').value;
            const description = document.getElementById('edit-description').value;

            axios.put(`/tasks/${currentTaskId}`, {
                    title,
                    description,
                    _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                })
                .then(response => {
                    if (response.data.success) {
                        Swal.fire(
                            'Actualizao!',
                            'La tarea ha sido actualizada.',
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    } else {
                        console.error('Error:', response.data.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error.response.data.error || error.message);
                    alert('Error al actualizar la tarea.');
                });
        }


        function updateTaskStatus(taskId) {
            const checkbox = document.getElementById(`task-status-checkbox-${taskId}`);
            const isComplete = checkbox.checked;

            axios.put(`/tasksStatus/${taskId}`, {
                    status: isComplete ? 1 : 0
                })
                .then(response => {
                    Swal.fire(
                        'Estado Actualizado',
                        `La tarea ha sido marcada como ${isComplete ? 'completa' : 'incompleta'}.`,
                        'success'
                    ).then(() => {
                        location.reload();
                    });
                })
                .catch(error => {
                    console.error('Hubo un error al actualizar el estado de la tarea:', error);
                    Swal.fire(
                        'Error!',
                        'Hubo un problema al actualizar el estado de la tarea.',
                        'error'
                    );
                });
        }
    </script>
</body>

</html>
