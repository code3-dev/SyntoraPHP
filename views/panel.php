<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .animate-spin {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .animate-pulse {
            animation: pulse 1s infinite;
        }

        @keyframes pulse {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }

            100% {
                opacity: 1;
            }
        }
    </style>

</head>

<body class="bg-gray-100 font-sans flex flex-col min-h-screen">
    <header class="bg-white shadow-md py-4">
        <div class="container mx-auto flex justify-between items-center px-4">
            <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
            <button class="bg-blue-500 text-white py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" onclick="openModal('logout')">Logout</button>
        </div>
    </header>

    <main class="flex-grow container mx-auto p-6">
        <div class="bg-white shadow-lg rounded-lg p-8">
            <div class="text-center mb-6">
                <h2 class="text-3xl font-semibold text-gray-900 mb-2">Hello, <?= htmlspecialchars($user["name"]) ?></h2>
                <p class="text-gray-600">Email: <?= htmlspecialchars($user["email"]) ?></p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <button class="w-full bg-indigo-500 text-white py-3 px-6 rounded-lg shadow-md hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-600" onclick="openModal('changeName')">Change Name</button>
                <button class="w-full bg-green-500 text-white py-3 px-6 rounded-lg shadow-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-600" onclick="openModal('changeEmail')">Change Email</button>
                <button class="w-full bg-yellow-500 text-white py-3 px-6 rounded-lg shadow-md hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-600" onclick="openModal('changePassword')">Change Password</button>
                <button class="w-full bg-red-500 text-white py-3 px-6 rounded-lg shadow-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-600" onclick="openModal('deleteAccount')">Delete Account</button>
            </div>
        </div>
    </main>

    <!-- Modal Templates -->
    <div id="modal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden justify-center items-start p-6">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md mx-auto relative">
            <button class="absolute top-4 right-4 text-gray-500 hover:text-gray-700" onclick="closeModal()">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <div id="modalContent">
                <!-- Modal content will be dynamically injected here -->
            </div>
            <div id="loadingSpinner" class="hidden absolute inset-0 flex justify-center items-center bg-gray-900 bg-opacity-50">
                <div class="w-12 h-12 border-4 border-t-4 border-gray-200 border-solid rounded-full animate-spin"></div>
            </div>
        </div>
    </div>


    <script>
        function openModal(modalType) {
            const modalContent = document.getElementById('modalContent');
            let content = '';

            switch (modalType) {
                case 'changeName':
                    content = `
            <h2 class="text-xl font-semibold mb-4">Change Your Name</h2>
            <form id="changeNameForm" data-action="/api/v1/update-name" method="post">
                <div class="mb-4">
                    <label for="newName" class="block text-gray-700 mb-2">New Name</label>
                    <input type="text" id="newName" name="name" class="w-full border-gray-300 rounded-lg p-3" required>
                </div>
                <input type="hidden" id="userId" name="id" value="<?= htmlspecialchars($user["id"]) ?>">
                <input type="hidden" id="userToken" name="token" value="<?= htmlspecialchars($token) ?>">
                <button type="submit" class="w-full bg-indigo-500 text-white py-2 px-4 rounded-lg hover:bg-indigo-600">Save</button>
            </form>
            <div id="changeNameMessage" class="mt-4"></div>
        `;
                    break;
                case 'changeEmail':
                    content = `
            <h2 class="text-xl font-semibold mb-4">Change Your Email</h2>
            <form id="changeEmailForm" data-action="/api/v1/update-email" method="post">
                <div class="mb-4">
                    <label for="newEmail" class="block text-gray-700 mb-2">New Email</label>
                    <input type="email" id="newEmail" name="email" class="w-full border-gray-300 rounded-lg p-3" required>
                </div>
                <input type="hidden" id="userId" name="id" value="<?= htmlspecialchars($user["id"]) ?>">
                <input type="hidden" id="userToken" name="token" value="<?= htmlspecialchars($token) ?>">
                <button type="submit" class="w-full bg-green-500 text-white py-2 px-4 rounded-lg hover:bg-green-600">Save</button>
            </form>
            <div id="changeEmailMessage" class="mt-4"></div>
        `;
                    break;
                case 'changePassword':
                    content = `
            <h2 class="text-xl font-semibold mb-4">Change Your Password</h2>
            <form id="changePasswordForm" data-action="/api/v1/update-password" method="post">
                <div class="mb-4">
                    <label for="newPassword" class="block text-gray-700 mb-2">New Password</label>
                    <input type="password" id="newPassword" name="password" class="w-full border-gray-300 rounded-lg p-3" required>
                </div>
                <input type="hidden" id="userId" name="id" value="<?= htmlspecialchars($user["id"]) ?>">
                <input type="hidden" id="userToken" name="token" value="<?= htmlspecialchars($token) ?>">
                <button type="submit" class="w-full bg-yellow-500 text-white py-2 px-4 rounded-lg hover:bg-yellow-600">Save</button>
            </form>
            <div id="changePasswordMessage" class="mt-4"></div>
        `;
                    break;
                case 'deleteAccount':
                    content = `
            <h2 class="text-xl font-semibold mb-4">Delete Your Account</h2>
            <form id="deleteAccountForm" data-action="/api/v1/delete-user" method="post">
                <p class="mb-4 text-gray-700">Are you sure you want to delete your account? This action cannot be undone.</p>
                <input type="hidden" id="userId" name="id" value="<?= htmlspecialchars($user["id"]) ?>">
                <input type="hidden" id="userToken" name="token" value="<?= htmlspecialchars($token) ?>">
                <button type="submit" class="w-full bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700">Delete Account</button>
            </form>
            <div id="deleteAccountMessage" class="mt-4"></div>
        `;
                    break;
                case 'logout':
                    content = `
            <h2 class="text-xl font-semibold mb-4">Logout</h2>
            <form action="/api/v1/logout" method="get">
                <p class="mb-4 text-gray-700">Are you sure you want to log out?</p>
                <input type="hidden" name="token" value="user-token-here">
                <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600">Logout</button>
            </form>
        `;
                    break;
                default:
                    content = '<p class="text-red-500">Invalid option</p>';
            }
            modalContent.innerHTML = content;
            document.getElementById('modal').classList.remove('hidden');

            if (modalType === 'changeName') {
                const form = document.getElementById('changeNameForm');
                const loadingSpinner = document.getElementById('loadingSpinner');
                const messageDiv = document.getElementById('changeNameMessage');

                form.addEventListener('submit', async function(event) {
                    event.preventDefault();

                    const formData = new FormData(form);
                    const data = {};
                    formData.forEach((value, key) => {
                        data[key] = value;
                    });

                    loadingSpinner.classList.remove('hidden');
                    form.querySelector('button').disabled = true;

                    try {
                        const response = await fetch(form.getAttribute('data-action'), {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify(data),
                        });

                        const result = await response.json();

                        if (response.ok) {
                            messageDiv.innerHTML = `<p class="text-green-500 animate-pulse">${result.message}</p>`;
                            setTimeout(() => window.location.reload(), 2000);
                        } else {
                            messageDiv.innerHTML = `<p class="text-red-500 animate-pulse">${result.message}</p>`;
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        messageDiv.innerHTML = '<p class="text-red-500 animate-pulse">An error occurred.</p>';
                    } finally {
                        loadingSpinner.classList.add('hidden');
                        form.querySelector('button').disabled = false;
                    }
                });
            } else if (modalType === 'changeEmail') {
                const form = document.getElementById('changeEmailForm');
                const loadingSpinner = document.getElementById('loadingSpinner');
                const messageDiv = document.getElementById('changeEmailMessage');

                form.addEventListener('submit', async function(event) {
                    event.preventDefault();

                    const formData = new FormData(form);
                    const data = {};
                    formData.forEach((value, key) => {
                        data[key] = value;
                    });

                    loadingSpinner.classList.remove('hidden');
                    form.querySelector('button').disabled = true;

                    try {
                        const response = await fetch(form.getAttribute('data-action'), {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify(data),
                        });

                        const result = await response.json();

                        if (response.ok) {
                            messageDiv.innerHTML = `<p class="text-green-500 animate-pulse">${result.message}</p>`;
                            setTimeout(() => window.location.reload(), 2000);
                        } else {
                            messageDiv.innerHTML = `<p class="text-red-500 animate-pulse">${result.message}</p>`;
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        messageDiv.innerHTML = '<p class="text-red-500 animate-pulse">An error occurred.</p>';
                    } finally {
                        loadingSpinner.classList.add('hidden');
                        form.querySelector('button').disabled = false;
                    }
                });
            } else if (modalType === 'deleteAccount') {
                const form = document.getElementById('deleteAccountForm');
                const loadingSpinner = document.getElementById('loadingSpinner');
                const messageDiv = document.getElementById('deleteAccountMessage');

                form.addEventListener('submit', async function(event) {
                    event.preventDefault();

                    const formData = new FormData(form);
                    const data = {};
                    formData.forEach((value, key) => {
                        data[key] = value;
                    });

                    loadingSpinner.classList.remove('hidden');
                    form.querySelector('button').disabled = true;

                    try {
                        const response = await fetch(form.getAttribute('data-action'), {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify(data),
                        });

                        const result = await response.json();

                        if (response.ok) {
                            messageDiv.innerHTML = `<p class="text-green-500 animate-pulse">${result.message}</p>`;
                            setTimeout(() => window.location.href = '/auth/login', 2000); // Redirect to login page after deletion
                        } else {
                            messageDiv.innerHTML = `<p class="text-red-500 animate-pulse">${result.message}</p>`;
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        messageDiv.innerHTML = '<p class="text-red-500 animate-pulse">An error occurred.</p>';
                    } finally {
                        loadingSpinner.classList.add('hidden');
                        form.querySelector('button').disabled = false;
                    }
                });
            } else if (modalType === 'changePassword') {
                const form = document.getElementById('changePasswordForm');
                const loadingSpinner = document.getElementById('loadingSpinner');
                const messageDiv = document.getElementById('changePasswordMessage');

                form.addEventListener('submit', async function(event) {
                    event.preventDefault();

                    const formData = new FormData(form);
                    const data = {};
                    formData.forEach((value, key) => {
                        data[key] = value;
                    });

                    loadingSpinner.classList.remove('hidden');
                    form.querySelector('button').disabled = true;

                    try {
                        const response = await fetch(form.getAttribute('data-action'), {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify(data),
                        });

                        const result = await response.json();

                        if (response.ok) {
                            messageDiv.innerHTML = `<p class="text-green-500 animate-pulse">${result.message}</p>`;
                            setTimeout(() => modal.classList.add('hidden'), 2000);
                        } else {
                            messageDiv.innerHTML = `<p class="text-red-500 animate-pulse">${result.message}</p>`;
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        messageDiv.innerHTML = '<p class="text-red-500 animate-pulse">An error occurred.</p>';
                    } finally {
                        loadingSpinner.classList.add('hidden');
                        form.querySelector('button').disabled = false;
                    }
                });
            }
        }

        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
        }
    </script>
</body>

</html>