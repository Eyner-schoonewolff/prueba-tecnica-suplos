<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Usuarios</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js" defer></script>
</head>

<body class="bg-gray-100">

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

    <main class="py-6">

        <!-- Mensajes de error -->
        <div class="relative sm:w-96 w-11/12 mx-auto mb-4">
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
        <div class="container mx-auto px-4">
            <h1 class="text-2xl font-bold mb-4">Registrar Usuario</h1>

            @if (session('success'))
                <div class="bg-green-200 text-green-800 p-4 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('users.store') }}" method="POST" class="bg-white p-6 rounded shadow-md">
                @csrf
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
                    <input type="text" id="name" name="name" required
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                    <input type="email" id="email" name="email" required
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                </div>

                <button type="submit"
                    class="bg-blue-500 text-white py-2 px-4 rounded-lg text-sm hover:bg-blue-600 transition duration-300 ease-in-out">Registrar</button>
            </form>
        </div>
    </main>

</body>

</html>
