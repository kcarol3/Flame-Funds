<template>
  <div class="card flex justify-content-center mt-2 mx-auto">
    <Sidebar v-model:visible="visible">
      <template #header>
        <div class="container align-content-center justify-content-center">
          <h2>Menu</h2>
        </div>
      </template>
      <div v-for="(item, index) in menu" :key="index" class="mt-1">
        <menu-element @click="visible = false" :name=item.name :icon=item.icon :link=item.link></menu-element>
      </div>
      <divider/>
    </Sidebar>
    <i @click="visible = true" class="bi bi-list click-animation" style="font-size: 38px"/>
  </div>
</template>

<script setup>
import { ref } from "vue";
import MenuElement from "@/components/MenuElement.vue";

const visible = ref(false);
</script>
<script>
export default {
  name: "SidebarMenu",

  data(){
    return {
      menu: [
        {
          name: "Strona domowa",
          icon: "bi bi-house",
          link: "/home"
        },
        {
          name: "Historia",
          icon: "bi bi-hourglass-split",
          link: "/history"
        },
        {
          name: "Konta",
          icon: "bi bi-wallet2",
          link: "/accounts"
        },
        {
          name: "Raport roczny",
          icon: "bi bi-pie-chart-fill",
          link: "/yearlyReport"
        },
        {
          name: "Cele finansowe",
          icon: "bi bi-piggy-bank-fill",
          link: "/myfinancialGoals"
        },
        {
          name: "Dodaj cel finansowy",
          icon: "bi bi-piggy-bank",
          link: "/financialGoal"
        },
        {
          name: "Płatności cykliczne",
          icon: "bi bi-repeat",
          link: "/myPeriodics"
        },
        {
          name: "Dodaj płatność cykliczną",
          icon: "bi bi-repeat-1",
          link: "/periodic"
        },
      ],
    }
  }
}
</script>
<style scoped>
.sh:hover {
  background-color: rgba(0, 0, 0, 0.1);
}

</style>