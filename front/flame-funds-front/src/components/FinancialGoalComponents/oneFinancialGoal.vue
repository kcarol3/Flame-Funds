<template>
  <div class="d-flex border border-2 rounded-4 my-auto body" style="max-width: 480px; box-shadow: 0 0 10px 2px rgba(0, 0, 0, 0.66);">
    <div class="mx-auto my-auto">
      <h2 class="flex-item lilita-one" style="color: rebeccapurple">
        {{ financialGoal.name }}
      </h2>
      <h5>
        Uzbierana kwota: {{ financialGoal.currentAmount }}zł
      </h5>
      <h5>
        Kwota docelowa: {{ financialGoal.goalAmount }}zł
      </h5>
    </div>

    <div class="mx-auto my-auto">
      <button class="button-primary" style="scale: 70%; border-radius: 200px" @click="toggle"><i class="bi bi-gear" /></button>
      <button class="button-primary" style="scale: 70%; border-radius: 200px" @click="deleteFinancialGoal"><i class="bi bi-trash" /></button>
      <Menu ref="menu" id="overlay_menu" :model="items" :popup="true" />
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
const menu = ref();
const items = ref([
  {
    label: 'Właściwości',
    items: [
      {
        label: 'Edytuj cel finansowy',
        icon: 'bi bi-pencil-square',
        to: "/home"
      },
      {
        label: 'Dodaj kwotę',
        icon: 'bi bi-star',
        to: "/editFinancialGoal"
      },
      {
        label: 'Usuń',
        icon: 'bi bi-trash',
        to: "/home"
      },
    ]
  }
]);

const toggle = (event) => {
  menu.value.toggle(event);
};

</script>
<script>
export default {
  name: "oneFinancialGoal",
  props:{
    financialGoal:Object,
  },

  data(){
    return{

    }
  }
}
</script>

<style scoped>

</style>