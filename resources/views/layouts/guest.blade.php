<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email | FinanceFlow</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-4">

    <div class="w-full max-w-md mx-auto">
        <!-- Main Card -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <!-- Top bar -->
            <div class="h-1.5 w-full" style="background-color: #111827;"></div>
            
            <div class="p-8">
                <!-- Icon -->
                <div class="flex justify-center mb-6">
                    <div class="bg-gray-100 rounded-full p-3">
                        <svg class="w-12 h-12" style="color: #111827;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 13l3 3m0 0l3-3m-3 3v-6"/>
                        </svg>
                    </div>
                </div>

                <!-- Title -->
                <h1 class="text-2xl font-bold text-center mb-2" style="color: #111827;">
                    Check your email
                </h1>
                
                <!-- Message -->
                <p class="text-center text-gray-600 text-sm mb-6">
                    We've sent a verification link to your email address.
                </p>

                <p class="text-center text-gray-500 text-sm mb-8">
                    Click the link in the email to verify your account and start managing your finances.
                </p>

                <!-- Buttons -->
                <div class="space-y-3">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="w-full py-2.5 rounded-lg font-medium text-white transition-colors" style="background-color: #111827;">
                            Resend verification email
                        </button>
                    </form>
                    
                    <a href="{{ route('login') }}" class="block text-center py-2.5 rounded-lg font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 transition-colors">
                        Back to login
                    </a>
                </div>

                <!-- Help text -->
                <p class="text-center text-gray-400 text-xs mt-6">
                    Didn't receive the email? Check your spam folder.
                </p> 
                <p class="text-center text-gray-400 text-xs mt-6">
                    Support: workwithsarnep@gmail.com
                </p>
            </div>
        </div>
        
        <!-- Footer -->
        <p class="text-center text-gray-400 text-xs mt-6">
            Sarthak's Finance Management System
        </p>
    </div>

</body>
</html>