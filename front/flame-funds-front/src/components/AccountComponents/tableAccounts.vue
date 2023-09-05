<template>
<div class="mt-4 p-2 rounded b pt-4 pb-4">
  <header-component title="Twoje konta" style="font-size: 28px"></header-component>
  <div class="jump">
    <i class="bi bi-arrow-up-short icon " style="font-size:34px; color: rebeccapurple"></i>
  </div>
  <ScrollPanel  style="width: 100%; height: 250px">
  <div v-for="(item, index) in accounts" :key="index">
    <one-account class="container element align-content-center justify-content-center mt-3" :account=item></one-account>
  </div>
  </ScrollPanel>
  <div class="jump">
    <i class="bi bi-arrow-down-short icon " style="font-size:34px; color: rebeccapurple"></i>
  </div>
</div>
</template>

<script>
import OneAccount from "@/components/AccountComponents/oneAccount.vue";
import HeaderComponent from "@/components/Header.vue";

export default {
  name: "tableAccounts",
  components: {HeaderComponent, OneAccount},

  props: {
    accounts: Array,
  }
}
</script>

<style scoped>
.b{
  box-shadow: 0px 0px 25px 19px rgba(255, 255, 255, 1);
  background: linear-gradient(to bottom, rgba(255, 255, 255, 0%), rgba(255, 255, 255, 100%));
}
.element{
background: linear-gradient(to top, rgba(255, 255, 255, 30%), rgba(255, 255, 255, 80%),rgba(255, 255, 255, 30%));
}

</style>