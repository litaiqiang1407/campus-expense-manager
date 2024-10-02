<template>
  <div class="flex flex-col items-center justify-start pt-5">
    <div class="flex flex-col items-center p-4">
      <img src="/public/assets/img/logo.png" alt="Logo" class="h-24 w-24 rounded-xl" />
      <h3 class="text-[#000000] font-semibold text-2xl mt-2">Money Lover</h3>
      <h1 class="text-[#A7A7A7] font-semibold text-sm">Version 8.23.0.3</h1>
      <h1 class="text-[#A7A7A7] font-semibold text-sm mb-2">by Finsify</h1>
    </div>

  </div>

  <div class="full-screen bg-white">
  <div class="container flex flex-col items-center">
    <div class="flex items-center justify-center w-full px-6">
      <div class="icon flex flex-col items-center flex-1">
        <img src="/public/assets/img/logobook.png" alt="Walkthrough Icon" class="h-12 w-12 " />
        <div>Walkthrough</div>
      </div>
      <div class="divider"></div>
      <div class="icon flex flex-col items-center flex-1">
        <img src="/public/assets/img/logoquestion.png" alt="Support Icon" class="h-12 w-12" />
        <div>Support</div>
      </div>
    </div>

    <div class="additional-options mt-6">
        <div class="option">
          <img src="/public/assets/img/logofacebook.png" alt="Support Icon" class="option-icon"/> 
          <span>Like our Facebook page</span>
        </div>
        <div class="option">
          <img src="/public/assets/img/logogroup.png" alt="Support Icon" class="option-icon"/> 
          <span>Join our beta testing group</span>
        </div>
        <div class="option">
          <img src="/public/assets/img/logotranslate.png" alt="Support Icon" class="option-icon"/> 
          <span>Help Us Translate</span>
        </div>
        <div class="option">
          <img src="/public/assets/img/logobusinesspartnership.png" alt="Support Icon" class="option-icon"/> 
          <span>For business partnership</span>
          </div></div></div>
</div>

  <div class="footer">
    <p >© 2024 Finsify. All rights reserved.</p>
  </div>

  
    <button onclick="goBack()" class="back-button">
      <i class="fa-solid fa-arrow-left" style="color: #000000;"></i>
    </button>


  
</template>

<script>
  function goBack() {
    window.history.back();
  }
</script>

<style>

  body {
    background-color: #effbff;
    margin: 0;
    height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: space-between;
  }

  .back-button {
    position: fixed;
    top: 10px;
    left: 10px;
    cursor: pointer;
    background-color: transparent;
    border: none;
    font-size: 24px;
  }

.full-screen {
  width: 100vw;
  display: flex;
  justify-content: center;
  background-color: #ffffff;
}

.container {
  text-align: center;
  width: 100%;
}

.flex {
  display: flex;
}

.items-center {
  align-items: center;
}

.justify-center {
  justify-content: center;
}

.w-full {
  width: 100%;
}

.icon {
  text-align: center;
  margin: 10px 0;
}

.icon img {
  width: 50px;
  height: 50px;
}

.divider {
  height: 60px;
  width: 3px;
  background-color: lightgray;
  margin: 0px 10px;
}

.additional-options {
  text-align: left;
  margin-top: 20px;
}

.option {
  display: flex;
  align-items: center;
  margin-bottom: 15px;
  font-size: 16px;
}

.option i {
  margin-right: 10px;
  font-size: 18px;
}

.mt-6 {
  margin-top: 10px;
}
.footer {
    width: 100%;
    text-align: left;
    padding: 1rem 0;
    background-color: #ffffff;
    position: absolute;
    margin-top: 5px;
  }

  .footer p {
    margin: 0;
    color: #000000;
    padding-left: 10px;
    font-size: 70%;
  }
  .option img.option-icon {
    width: 30px; 
    height: 30px; 
    margin-right: 10px;
  }

  .option span {
    margin-left: 10px; 
  }
</style>