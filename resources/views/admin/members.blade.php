<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Members Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --purple: #925fe2;
            --darkpurple: #7848c7;
            --black: #000000;
            --descedent: #202020;
            --shape-corner-extra-large: 28px;
            --shape-corner-large: 16px;
        }
        
        * {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        .rounded-extra-large {
            border-radius: var(--shape-corner-extra-large);
        }
        
        .rounded-large {
            border-radius: var(--shape-corner-large);
        }
        
        .bg-purple {
            background-color: var(--purple);
        }
        
        .text-purple {
            color: var(--purple);
        }
        
        .shadow-custom {
            box-shadow: 0px 4px 9.7px rgba(0, 0, 0, 0.25);
        }
        
        .avatar-circle {
            width: 38px;
            height: 39px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }
    </style>
</head>
<body class="bg-white">
    <div class="bg-white flex flex-row justify-center w-full">
        <div class="bg-white w-[1440px] h-[1024px] relative">
            <!-- Sidebar -->
            <div class="flex flex-col w-[265px] h-[958px] items-start gap-9 px-[53px] py-[42px] absolute top-[33px] left-[35px] bg-[#925fe2] rounded-extra-large">
                <img class="relative w-[158px] h-[150px] object-cover" alt="Logo" src="{{ asset('images/image-1.png') }}">

                <div class="inline-flex flex-col items-center gap-[49px] relative flex-[0_0_auto]">
                    <div class="flex flex-col w-[136px] h-[271px] items-start gap-2.5">
                        <p class="relative self-stretch font-normal text-white text-base tracking-[0] leading-4">
                            <span class="leading-[22.4px]">
                                Dashboard
                                <br />
                            </span>
                        </p>

                        <p class="relative self-stretch font-normal text-white text-base tracking-[0] leading-4">
                            <span class="font-light leading-[22.4px]">
                                <br />
                            </span>
                        </p>

                        <p class="relative self-stretch font-normal text-white text-base tracking-[0] leading-4">
                            <span class="font-bold leading-[19.4px]">
                                Members
                                <br />
                            </span>
                        </p>

                        <p class="relative self-stretch font-normal text-white text-base tracking-[0] leading-4">
                            <span class="leading-[19.4px]">
                                <br />
                            </span>
                        </p>

                        <p class="relative self-stretch font-normal text-white text-base tracking-[0] leading-4">
                            <span class="leading-[19.4px]">
                                Announcements
                                <br />
                            </span>
                        </p>

                        <p class="relative self-stretch font-normal text-white text-base tracking-[0] leading-4">
                            <span class="leading-[19.4px]">
                                <br />
                            </span>
                        </p>

                        <p class="relative self-stretch font-normal text-white text-base tracking-[0] leading-4">
                            <span class="leading-[19.4px]">
                                Courses Taught
                                <br />
                            </span>
                        </p>

                        <p class="relative self-stretch font-normal text-white text-base tracking-[0] leading-4">
                            <span class="leading-[19.4px]">
                                <br />
                            </span>
                        </p>

                        <p class="relative self-stretch font-normal text-white text-base tracking-[0] leading-4">
                            <span class="leading-[19.4px]">Messages</span>
                        </p>
                    </div>
                </div>

                <div class="relative w-fit font-light text-white text-base tracking-[0] leading-[19.4px] whitespace-nowrap">
                    Logout
                </div>
            </div>

            <!-- Notification Icon -->
            <div class="absolute w-[41px] h-8 top-[43px] left-[1308px] bg-[url({{ asset('notifications.svg') }})] bg-[100%_100%]">
                <div class="relative w-2 h-2 top-1.5 left-[7px] bg-[#ff0000] rounded"></div>
            </div>

            <!-- Settings Icon -->
            <div class="absolute w-[27px] h-[27px] top-[46px] left-[1363px]">
                <svg xmlns="http://www.w3.org/2000/svg" width="27" height="27" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="3"></circle>
                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                </svg>
            </div>

            <!-- Top Search Bar -->
            <div class="absolute w-[444px] h-[57px] top-[33px] left-[342px] bg-neutral-100 rounded-extra-large shadow-custom">
                <div class="absolute w-[444px] h-[57px] top-0 left-0 bg-neutral-100 rounded-extra-large shadow-custom"></div>
                <div class="absolute h-[19px] top-[18px] left-[362px] font-normal text-[#787878] text-base tracking-[0] leading-[19.4px] whitespace-nowrap">
                    Search
                </div>
            </div>

            <!-- Members Title -->
            <div class="absolute h-[39px] top-[129px] left-[361px] font-semibold text-[#000000] text-[32px] tracking-[0] leading-[38.8px] whitespace-nowrap">
                Members
            </div>

            <!-- Add Member Button -->
            <div class="absolute w-[243px] h-16 top-[130px] left-[1145px] bg-[#925fe2] rounded-large cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="absolute w-6 h-6 top-[21px] left-[17px]" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="16"></line>
                    <line x1="8" y1="12" x2="16" y2="12"></line>
                </svg>
                <div class="absolute h-[29px] top-4 left-[52px] font-semibold text-white text-2xl tracking-[0] leading-[29.1px] whitespace-nowrap">
                    Add a member
                </div>
            </div>

            <!-- Navigation Tabs -->
            <div class="absolute h-6 top-[212px] left-[361px] font-normal text-[#000000] text-xl tracking-[0] leading-[24.3px] whitespace-nowrap">
                People
            </div>

            <div class="absolute h-6 top-[212px] left-[463px] font-normal text-[#000000] text-xl tracking-[0] leading-[24.3px] whitespace-nowrap">
                Attendance
            </div>

            <!-- Active Tab Line -->
            <div class="absolute w-[1048px] h-[21px] top-[248px] left-[340px]">
                <img
                    class="absolute w-[1042px] h-[3px] top-2.5 left-1.5 object-cover"
                    alt="Line"
                    src="{{ asset('images/line-5.svg') }}"
                />
                <img
                    class="absolute w-[95px] h-[21px] top-0 left-0"
                    alt="Line"
                    src="{{ asset('images/line-6.svg') }}"
                />
            </div>

            <!-- Total Members Count -->
            <div class="absolute h-[29px] top-[317px] left-[361px] font-semibold text-[#000000] text-2xl tracking-[0] leading-[29.1px] whitespace-nowrap">
                63 people in total
            </div>

            <!-- Filter -->
            <div class="absolute h-6 top-[322px] left-[971px] font-normal text-[#000000] text-xl tracking-[0] leading-[24.3px] whitespace-nowrap">
                Filter
            </div>

            <!-- Filter Button - All -->
            <div class="absolute w-[97px] h-[57px] top-[304px] left-[1037px] bg-neutral-100 rounded-extra-large shadow-custom">
                <div class="absolute h-6 top-[15px] left-[27px] font-normal text-[#000000] text-xl tracking-[0] leading-[24.3px] whitespace-nowrap">
                    All
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="absolute w-5 h-5 top-[19px] left-16" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 12 15 18 9"></polyline>
                </svg>
            </div>

            <!-- Search Button -->
            <div class="absolute w-56 h-[57px] top-[302px] left-[1164px] bg-neutral-100 rounded-extra-large shadow-custom">
                <div class="absolute h-6 top-4 left-[46px] font-thin text-[#000000] text-xl tracking-[0] leading-[24.3px] whitespace-nowrap">
                    look for someone
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="absolute w-6 h-6 top-4 left-[22px]" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </div>

            <!-- Members Table Header -->
            <div class="absolute top-[390px] left-[381px] font-normal text-[#000000] text-sm tracking-[0] leading-[17.0px] whitespace-nowrap">
                #&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Member&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Position&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Email&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Roll number/Course
            </div>

            <!-- Member Row 1 -->
            <div class="absolute w-[1027px] h-[57px] top-[428px] left-[361px] bg-neutral-100 rounded-extra-large shadow-custom">
                <div class="absolute w-[38px] h-[39px] top-[7px] left-[66px] bg-[#ffa903] rounded-[19px/19.5px]"></div>
                <p class="absolute top-4 left-5 font-medium text-[#000000] text-[15px] tracking-[0] leading-[18.2px] whitespace-nowrap">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; M&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Manu Basavaraju&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Teacher&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;manub@nitk.edu.in&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Theory of Computation
                </p>
            </div>

            <!-- Member Row 2 -->
            <div class="absolute w-[1027px] h-[57px] top-[501px] left-[361px] bg-neutral-100 rounded-extra-large shadow-custom">
                <div class="absolute w-[38px] h-[39px] top-[9px] left-[68px] bg-[#3aa905] rounded-[19px/19.5px]"></div>
                <p class="absolute top-[19px] left-5 font-medium text-[#000000] text-[15px] tracking-[0] leading-[18.2px] whitespace-nowrap">
                    2.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; B&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; B R Chandrashekar&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Teacher&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;brc@nitk.edu.in&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Design of Digital Systems
                </p>
            </div>

            <!-- Member Row 3 -->
            <div class="absolute w-[1027px] h-[57px] top-[577px] left-[361px] bg-neutral-100 rounded-extra-large shadow-custom">
                <div class="absolute w-[38px] h-[39px] top-2.5 left-[68px] bg-[#03efff] rounded-[19px/19.5px]"></div>
                <p class="absolute top-5 left-5 font-medium text-[#000000] text-[15px] tracking-[0] leading-[18.2px] whitespace-nowrap">
                    3.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; N&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Nischal Basavaraju&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Student&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;nischal.231cs139@nitk.edu.in&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;231CS139
                </p>
            </div>

            <!-- Member Row 4 -->
            <div class="absolute w-[1027px] h-[57px] top-[653px] left-[361px] bg-neutral-100 rounded-extra-large shadow-custom">
                <div class="absolute w-[38px] h-[39px] top-2.5 left-[68px] bg-[#cc03ff] rounded-[19px/19.5px]"></div>
                <p class="absolute top-5 left-5 font-medium text-[#000000] text-[15px] tracking-[0] leading-[18.2px] whitespace-nowrap">
                    4.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; P&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Prahas G R&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Student&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;prahasgr.231cs142@nitk.edu.in&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 231CS142
                </p>
            </div>

            <!-- Member Row 5 -->
            <div class="absolute w-[1027px] h-[57px] top-[726px] left-[361px] bg-neutral-100 rounded-extra-large shadow-custom">
                <div class="absolute w-[38px] h-[39px] top-[9px] left-[68px] bg-[#eaff03] rounded-[19px/19.5px]"></div>
                <p class="absolute top-[19px] left-5 font-medium text-[#000000] text-[15px] tracking-[0] leading-[18.2px] whitespace-nowrap">
                    5.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; V&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Vineet Nayak&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Student&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;vineetnayak.231cs132@nitk.edu.in&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;231CS132
                </p>
            </div>

            <!-- Member Row 6 -->
            <div class="absolute w-[1027px] h-[57px] top-[802px] left-[361px] bg-neutral-100 rounded-extra-large shadow-custom">
                <div class="absolute w-[38px] h-[39px] top-[9px] left-[68px] bg-[#5403ff] rounded-[19px/19.5px]"></div>
                <p class="absolute top-[19px] left-5 font-medium text-[#000000] text-[15px] tracking-[0] leading-[18.2px] whitespace-nowrap">
                    6.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; D&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Dharineesh Rajan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Student&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;dharineeshrajan.231ee119@nitk.edu.in&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 231EE119
                </p>
            </div>

            <!-- Member Row 7 -->
            <div class="absolute w-[1027px] h-[57px] top-[881px] left-[361px] bg-neutral-100 rounded-extra-large shadow-custom">
                <div class="absolute w-[38px] h-[39px] top-[9px] left-[68px] bg-[#ff0303] rounded-[19px/19.5px]"></div>
                <div class="absolute top-[19px] left-5 font-medium text-[#000000] text-[15px] tracking-[0] leading-[18.2px] whitespace-nowrap">
                    7.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;A&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Anush&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Student&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;anush.231ch239@nitk.edu.in&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Mtech(Biochemistry)
                </div>
            </div>

            <!-- Pagination -->
            <div class="absolute left-[1132px] top-[953px] flex items-center">
                <ul class="flex space-x-2">
                    <li class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-200">1</li>
                    <li class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-200">2</li>
                    <li class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-200">3</li>
                    <li class="flex items-center justify-center w-8 h-8 rounded-full bg-[#925fe2] text-white">4</li>
                    <li class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-200">5</li>
                    <li class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-200">6</li>
                    <li class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-200">7</li>
                    <li class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-200">8</li>
                    <li class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-200">9</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        // You can add JavaScript for interactive elements here
        document.addEventListener('DOMContentLoaded', function() {
            // Example: Make sidebar items clickable
            const sidebarItems = document.querySelectorAll('.sidebar-menu p');
            sidebarItems.forEach(item => {
                item.style.cursor = 'pointer';
                item.addEventListener('click', function() {
                    // Handle navigation
                });
            });
        });
    </script>
</body>
</html> 