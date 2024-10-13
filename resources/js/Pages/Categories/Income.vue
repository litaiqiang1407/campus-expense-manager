<template>
    <div class=" absolute w-screen h-screen bg-[#effbff]">
        <!-- New Category Button -->
        <div class="flex justify-left items-center py-2 bg-white rounded-lg shadow">
            <button class="rounded-full px-4">
                <font-awesome-icon icon="circle-plus" class="text-primary size-8" />
            </button>
            <span class="text-primary font-semibold p-3">NEW CATEGORY</span>
        </div>

        <div class=" bg-white shadow my-2">
            <div class="flex justify-between items-center pt-4 px-4">
                <div class="flex items-center space-x-3">
                <img src="/assets/icon/expense/education.png" alt="Badminton" class="w-10 h-10 rounded-full">
                    <div>
                        <p class="font-medium">Badminton</p>
                        <p class="text-sm text-secondaryText">Cash</p>
                    </div>
                </div>
                <button class="absolute right-0 pr-3 z-50">
                    <font-awesome-icon icon="angle-right" class="text-secondaryText size-5" />
                </button> 
            </div>
            <ul class="pl-8 ">
                    <li class="flex items-center space-x-2 border-l-[2px] py-2">
                        <div class="h-[2px] bg-gray-200 w-2 absolute"></div>
                        <img src="/assets/icon/expense/education.png" alt="Badminton" class="w-8 h-8 rounded-full">
                        <div>
                            <p class="font-medium">Badminton</p>
                            <p class="text-xs text-secondaryText">Cash</p>
                        </div>
                        <button class="absolute right-0 pr-3 z-50">
                            <font-awesome-icon icon="angle-right" class="text-secondaryText size-4" />
                        </button>
                    </li>
                    <li class="flex items-center space-x-2 border-left-half py-2">
                        <div class="h-[2px] bg-gray-200 w-2 absolute"></div>
                        <img src="/assets/icon/expense/education.png" alt="Badminton" class="w-8 h-8 rounded-full">
                        <div>
                            <p class="font-medium">Badminton</p>
                            <p class="text-xs text-secondaryText">Cash</p>
                        </div>
                        <button class="absolute right-0 pr-3 z-50">
                            <font-awesome-icon icon="angle-right" class="text-secondaryText size-4" />
                        </button>
                    </li>
                </ul>
            </div>
        <div class="bg-white shadow my-2">
                <div class="flex justify-between items-center px-4 pt-4 ">
                    <div class="flex items-center space-x-3">
                    <img src="/assets/icon/expense/education.png" alt="Badminton" class="w-10 h-10 rounded-full">
                        <div>
                            <p class="font-medium">Badminton</p>
                            <p class="text-sm text-secondaryText">Cash</p>
                        </div>
                    </div>
                    <button class="absolute right-0 pr-3 z-50">
                        <font-awesome-icon icon="angle-right" class="text-secondaryText size-5" />
                    </button> 
                </div>
                <ul class="pl-8 ">
                    <li class="flex items-center space-x-2 border-l-[2px] py-2">
                        <div class="h-[2px] bg-gray-200 w-2 absolute"></div>
                        <img src="/assets/icon/expense/education.png" alt="Badminton" class="w-8 h-8 rounded-full">
                        <div>
                            <p class="font-medium">Badminton</p>
                            <p class="text-xs text-secondaryText">Cash</p>
                        </div>
                        <button class="absolute right-0 pr-3 z-50">
                            <font-awesome-icon icon="angle-right" class="text-secondaryText size-4" />
                        </button>
                    </li>
                    <li class="flex items-center space-x-2 border-left-half py-2">
                        <div class="h-[2px] bg-gray-200 w-2 absolute"></div>
                        <img src="/assets/icon/expense/education.png" alt="Badminton" class="w-8 h-8 rounded-full">
                        <div>
                            <p class="font-medium">Badminton</p>
                            <p class="text-xs text-secondaryText">Cash</p>
                        </div>
                        <button class="absolute right-0 pr-3 z-50">
                            <font-awesome-icon icon="angle-right" class="text-secondaryText size-4" />
                        </button>
                    </li>
                    
                </ul>
            </div>
            
        <div class="flex justify-center items-center bg-white shadow my-2">
            <span class="text-primary font-semibold p-3">Need help? Send us a message?</span>
        </div>

        <div class="flex justify-left items-center bg-white shadow my-2">
            <button class="rounded-full px-4">
                <font-awesome-icon icon="plus" class="text-primary size-6" />
            </button>
            <span class="text-primary font-semibold p-3">Show inactive categories</span>
        </div>
    </div>
</template>