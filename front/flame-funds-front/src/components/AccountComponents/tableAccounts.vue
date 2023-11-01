<template>
<div class="p-2 rounded sh mt-3">
  <div class="jump">
    <i class="bi bi-arrow-up-short icon " style="font-size:34px; color: rebeccapurple"></i>
  </div>
  <ScrollPanel class="mx-auto" :class="{ 'scrollPanel': this.accounts.length >= 5}">
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

export default {
  name: "tableAccounts",
   components: {OneAccount},

  props: {
    accounts: Array,
  }
}
</script>

<style scoped>
.sh{
  box-shadow: 0 0 20px 20px rgba(255, 255, 255, 0.95);
  background-color: rgba(255, 255, 255, 0.95);
  max-width: 450px;
  height: 60vh
}
@media screen and (max-width: 424px) {
  .sh {
    height: 60vh;
    box-shadow: 0 0 40px 40px rgba(255, 255, 255, 0.95);
    background-color: rgba(255, 255, 255, 0.95);
  }
}

.scrollPanel{
  height: 50vh;
}
</style>