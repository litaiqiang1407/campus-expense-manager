<template>
    <div class=" absolute w-screen h-screen bg-[#effbff]">
    <!-- Expense/Income/Debt Navigation -->
        <div class="max-w-lg mx-auto py-1">
            <div class="flex justify-around text-xs font-medium bg-[#f8f9fa] pt-2">
                <button class="py-2 px-4 text-secondaryText border-b-[3px] border-black">EXPENSE</button>
                <button class="py-2 text-secondaryText">INCOME</button>
                <button class="py-2 text-secondaryText">DEBT/LOAN</button>
            </div>
        </div>

    <!-- New Category Button -->
        <div class="flex justify-left items-center py-2 bg-white rounded-lg shadow">
            <button class="rounded-full px-4">
                <font-awesome-icon icon="circle-plus" class="text-primary size-8" />
            </button>
            <span class="text-primary font-semibold p-3">NEW CATEGORY</span>
        </div>

    <!-- Category List -->
        <div class=" shadow overflow-hidden">
        <!-- Badminton Category -->
            <div class="flex justify-between items-center py-4 px-4 border-b bg-white my-2">
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
            
        <!-- Bill & Utilities Section -->
            <div class="bg-white shadow rounded-lg my-2">
                <!-- Main Category Item -->
                <div class="flex justify-between items-center px-4 pt-4">
                    <div class="flex items-center space-x-3">
                        <img src="/assets/icon/expense/education.png" alt="Badminton" class="w-10 h-10 rounded-full">
                        <div>
                            <p class="font-medium ">Badminton</p>
                            <p class="text-sm text-secondaryText">Cash</p>
                        </div>
                       
                    </div>
                    <button class="absolute right-0 pr-3 z-50">
                        <font-awesome-icon icon="angle-right" class="text-secondaryText size-5" />
                    </button>
                </div>
                <!-- Subcategories (Bills) -->
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
                <!--Last Item-->
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


            <div class="bg-white shadow rounded-lg my-2">
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
            

            <div class="flex justify-between items-center py-4 px-4 border-b bg-white my-2">
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


<style>
.border-left-half {
    position: relative; 
}
.border-left-half::before {
    content: ''; 
    position: absolute; 
    top: 0; 
    left: 0; 
    width: 1.9px; 
    height: 50%; 
    background-color: #e5e7eb; 
    z-index: 1; 
}
</style>