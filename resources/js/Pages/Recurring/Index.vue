<template>
<div class="min-h-screen bg-primaryBackground">
  <header class="sticky h-13 flex items-center justify-between px-4 py-2 bg-white z-50">
        <div class="flex items-center">
            <h1 class="text-[20px] font-bold">Budgets</h1>
        </div>
        <div class="flex items-center space-x-4">
          <button @click="selectWallet">
            <img :src="wallet?.icon_path || '/assets/img/earth.png'" alt="Wallet" class="size-8">
          </button>
          <button class="flex items-center">
            <font-awesome-icon icon="ellipsis-vertical" class="text-[24px]" />
          </button>
        </div>
    </header>
</div>
    <div>
        <h2> Tan Loooooooooooooo</h2>
    </div>
</template>
