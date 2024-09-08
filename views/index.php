<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?> - Fast & Simple Framework</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #0f0f0f;
            color: #0ff0ff;
            font-family: 'Fira Code', monospace;
        }

        a {
            color: #00ff00;
        }

        .bg-custom-dark {
            background-color: #111111;
        }

        .border-custom-green {
            border-color: #00ff00;
        }

        .neon-shadow {
            text-shadow: 0 0 5px #0ff, 0 0 10px #0ff, 0 0 20px #00ff00;
        }

        svg {
            stroke: #00ff00;
        }
    </style>
</head>

<body class="antialiased">
    <div class="relative flex items-top justify-center min-h-screen sm:items-center sm:pt-0">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-center pt-8 sm:justify-start sm:pt-0">
                <h1 class="text-5xl font-bold">SyntoraPHP</h1>
            </div>

            <div class="mt-8 bg-custom-dark overflow-hidden shadow border-custom-green sm:rounded-lg">
                <div class="grid grid-cols-1 md:grid-cols-2">
                    <div class="p-6">
                        <div class="flex items-center">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7l6 6-6 6M21 7l-6 6 6 6"></path>
                            </svg>
                            <div class="ml-4 text-lg leading-7 font-semibold">
                                <h2 class="underline">Fast & Simple</h2>
                            </div>
                        </div>
                        <div class="ml-12">
                            <div class="mt-2 text-sm">
                                SyntoraPHP is a powerful and lightweight PHP framework designed for simplicity and speed. Build dynamic web applications with minimal setup.
                            </div>
                        </div>
                    </div>

                    <div class="p-6 border-t border-custom-green md:border-t-0 md:border-l">
                        <div class="flex items-center">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            <div class="ml-4 text-lg leading-7 font-semibold">
                                <h2 class="underline">Modern Tools</h2>
                            </div>
                        </div>
                        <div class="ml-12">
                            <div class="mt-2 text-sm">
                                SyntoraPHP integrates modern tools and technologies for real-time development, offering a clean API and easy integration with front-end frameworks.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-center mt-8 sm:items-center sm:justify-between">
                <div class="text-center text-sm">
                    <div class="flex items-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h18"></path>
                        </svg>
                        <a href="https://SyntoraPHP.github.io" class="ml-1 underline">Documentation</a>
                    </div>
                </div>

                <div class="ml-4 text-center text-sm">
                    <p>SyntoraPHP © <?php echo date('Y'); ?> | Running PHP version: <strong><?php echo phpversion(); ?></strong></p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>