<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Student Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        /* Inline critical Tailwind styles */
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
        }
        .bg-\[\#925fe2\] {
            background-color: #925fe2;
        }
        .flex {
            display: flex;
        }
        .flex-row {
            flex-direction: row;
        }
        .justify-center {
            justify-content: center;
        }
        .w-full {
            width: 100%;
        }
        .w-\[1440px\] {
            width: 1440px;
        }
        .h-\[1024px\] {
            height: 1024px;
        }
        .relative {
            position: relative;
        }
        .absolute {
            position: absolute;
        }
        .text-white {
            color: white;
        }
        .bg-\[\#1c1d21\] {
            background-color: #1c1d21;
        }
        .rounded-\[24px_0px_0px_24px\] {
            border-radius: 24px 0px 0px 24px;
        }
        .bg-\[\#9c6fe4\] {
            background-color: #9c6fe4;
        }
        .rounded-xl {
            border-radius: 0.75rem;
        }
        .text-\[\#ffffff80\] {
            color: rgba(255, 255, 255, 0.5);
        }
        .text-\[\#ffffffb2\] {
            color: rgba(255, 255, 255, 0.7);
        }
        .bg-transparent {
            background-color: transparent;
        }
        .border-b {
            border-bottom-width: 1px;
        }
        .border-white\/50 {
            border-color: rgba(255, 255, 255, 0.5);
        }
        .focus\:outline-none:focus {
            outline: none;
        }
        .hover\:bg-\[\#8055d0\]:hover {
            background-color: #8055d0;
        }
        .transition {
            transition-property: all;
        }
        .duration-300 {
            transition-duration: 300ms;
        }
        .inline-flex {
            display: inline-flex;
        }
        .flex-col {
            flex-direction: column;
        }
        .items-start {
            align-items: flex-start;
        }
        .items-center {
            align-items: center;
        }
        .gap-12 {
            gap: 3rem;
        }
        .gap-6 {
            gap: 1.5rem;
        }
        .gap-3 {
            gap: 0.75rem;
        }
        .gap-\[67px\] {
            gap: 67px;
        }
        .gap-2\.5 {
            gap: 0.625rem;
        }
        .font-bold {
            font-weight: 700;
        }
        .font-medium {
            font-weight: 500;
        }
        .font-normal {
            font-weight: 400;
        }
        .text-5xl {
            font-size: 3rem;
        }
        .text-base {
            font-size: 1rem;
        }
        .text-\[80px\] {
            font-size: 80px;
        }
        .leading-\[70px\] {
            line-height: 70px;
        }
        .w-\[381px\] {
            width: 381px;
        }
        .w-\[393px\] {
            width: 393px;
        }
        .w-\[100px\] {
            width: 100px;
        }
        .py-3 {
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
        }
        .px-6 {
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }
        .bg-\[\#333437\] {
            background-color: #333437;
        }
        .rounded-lg {
            border-radius: 0.5rem;
        }
        .hover\:bg-\[\#444548\]:hover {
            background-color: #444548;
        }
        .object-cover {
            object-fit: cover;
        }
        .text-red-500 {
            color: #ef4444;
        }
        .text-sm {
            font-size: 0.875rem;
        }
        .mt-1 {
            margin-top: 0.25rem;
        }
        .mt-\[-1\.00px\] {
            margin-top: -1px;
        }
        .ml-\[-4\.00px\] {
            margin-left: -4px;
        }
        .mr-\[-4\.00px\] {
            margin-right: -4px;
        }
        .pb-2 {
            padding-bottom: 0.5rem;
        }
        .bg-green-100 {
            background-color: #d1fae5;
        }
        .border-green-400 {
            border-color: #34d399;
        }
        .text-green-700 {
            color: #047857;
        }
        .px-4 {
            padding-left: 1rem;
            padding-right: 1rem;
        }
        .py-3 {
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
        }
        .rounded {
            border-radius: 0.25rem;
        }
        .mb-5 {
            margin-bottom: 1.25rem;
        }
        .border {
            border-width: 1px;
        }
    </style>
</head>
<body>
    <div class="bg-[#925fe2] flex flex-row justify-center w-full">
        <div class="bg-[#925fe2] w-[1440px] h-[1024px]">
            <div class="relative h-[1024px]">
                <img class="absolute w-[402px] h-[342px] top-0 left-[1038px]" alt="Vector" src="{{ asset('images/vector-2.svg') }}">
                <img class="absolute w-[763px] h-[616px] top-[408px] left-[677px]" alt="Vector" src="{{ asset('images/vector-3.svg') }}">
                <img class="absolute w-[421px] h-[389px] top-[245px] left-[459px]" alt="Vector" src="{{ asset('images/vector-4.svg') }}">
                <img class="absolute w-[177px] h-[200px] top-[175px] left-[1263px]" alt="Vector" src="{{ asset('images/vector-5.svg') }}">
                <img class="absolute w-[297px] h-[221px] top-0 left-[868px]" alt="Vector" src="{{ asset('images/vector.svg') }}">
                <img class="absolute w-[297px] h-[273px] top-[671px] left-[664px]" alt="Vector" src="{{ asset('images/image.svg') }}">
                
                <div class="absolute w-[630px] h-[1024px] top-0 left-0 bg-[#1c1d21] rounded-[24px_0px_0px_24px]"></div>
                
                <div class="inline-flex flex-col items-start gap-12 absolute top-[314px] left-32">
                    @if (session('status'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-5 w-[381px]">
                            {{ session('status') }}
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('password.email') }}" class="inline-flex flex-col items-start gap-6 relative flex-[0_0_auto]">
                        @csrf
                        <div class="inline-flex flex-col items-start gap-12 relative flex-[0_0_auto]">
                            <div class="inline-flex flex-col items-start gap-3 relative flex-[0_0_auto]">
                                <div class="[font-family:'Poppins-Bold',Helvetica] font-bold text-5xl relative w-fit mt-[-1.00px] text-white tracking-[0] leading-[normal]">
                                    Reset Password
                                </div>
                                <div class="relative w-fit [font-family:'Poppins-Medium',Helvetica] font-medium text-[#ffffffb2] text-base tracking-[0] leading-[normal]">
                                    Enter your email to reset password
                                </div>
                            </div>
                            
                            <div class="inline-flex flex-col items-start gap-6 relative flex-[0_0_auto]">
                                <div class="flex flex-col w-[381px] items-start gap-3 relative flex-[0_0_auto]">
                                    <div class="relative w-fit mt-[-1.00px] [font-family:'Poppins-Regular',Helvetica] font-normal text-[#ffffff80] text-base tracking-[0] leading-[normal]">
                                        Email Address
                                    </div>
                                    <input type="email" name="email" value="{{ old('email') }}" required 
                                           class="bg-transparent border-b border-white/50 w-[381px] focus:outline-none text-white pb-2">
                                    @error('email')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="flex w-[393px] items-center justify-center gap-2.5 px-0 py-3 relative flex-[0_0_auto] bg-[#9c6fe4] rounded-xl hover:bg-[#8055d0] transition duration-300">
                            <div class="[font-family:'Poppins-Regular',Helvetica] font-normal text-base relative w-fit mt-[-1.00px] text-white tracking-[0] leading-[normal]">
                                Send Password Reset Link
                            </div>
                        </button>
                    </form>
                </div>
                
                <div class="inline-flex flex-col items-start gap-3 absolute top-[155px] left-[730px]">
                    <p class="relative w-fit mt-[-1.00px] [font-family:'Poppins-Bold',Helvetica] font-normal text-[#eeeeee] text-[80px] tracking-[0] leading-[70px]">
                        <span class="font-bold">
                            Reset Your<br />
                        </span>
                        <span class="[font-family:'Poppins-Regular',Helvetica]">
                            password
                        </span>
                    </p>
                    <p class="relative w-fit [font-family:'Poppins-Medium',Helvetica] font-medium text-[#eeeeee] text-base tracking-[0] leading-[normal]">
                        We'll send you a link to reset your password
                    </p>
                </div>
                
                <div class="inline-flex items-center gap-[67px] absolute top-[912px] left-32">
                    <div class="relative w-fit [font-family:'Poppins-Regular',Helvetica] font-normal text-[#ffffff80] text-base tracking-[0] leading-[normal]">
                        Remember your password?
                    </div>
                    <a href="{{ route('login') }}" class="flex w-[100px] items-center justify-center gap-2.5 px-6 py-3 relative bg-[#333437] rounded-lg hover:bg-[#444548] transition duration-300">
                        <div class="relative w-fit mt-[-1.00px] ml-[-4.00px] mr-[-4.00px] [font-family:'Poppins-Regular',Helvetica] font-normal text-white text-base tracking-[0] leading-[normal]">
                            Login
                        </div>
                    </a>
                </div>
                
                <img class="absolute w-[767px] h-[628px] top-[368px] left-[673px] object-cover" 
                     alt="Element copy" src="{{ asset('images/1100924-01-copy-1.png') }}">
            </div>
        </div>
    </div>
</body>
</html> 