<template>
        <Header />
        <main class="p-4 flex flex-col space-y-4">
            <div class="bg-white rounded-lg py-2 pl-2 shadow">
                <div class="flex items-center justify-between border-b-[1px] border-secondaryText pb-2 pr-2">
                    <span class="font-semibold text-[14px]">My Wallets</span>
                    <button class="text-primary font-semibold text-[10px]">See all</button>
                </div>
                <div class="flex items-center justify-between py-2 pr-2">
                    <div class="flex items-center">
                        <img src="/public/assets/icon/wallet.png" alt="Wallet" class="w-8 h-8" />
                        <span class="ml-2 font-semibold text-[14px]">Cash</span>
                    </div>
                    <span class="font-semibold text-[14px]">$ 0.00</span>
                </div>
            </div>
            <Report />
            <Spending />
            <Transaction />
        </main>
</template>

<script setup>
import { Header, Report, Spending, Transaction } from '@/Pages/Home/Components/Index.js';
</script>