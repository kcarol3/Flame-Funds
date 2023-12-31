<template>
  <div class="d-flex border border-2 rounded-4 my-auto body" :style="maxWidthStyles">
    <div class="mx-auto my-auto">
      <h2 class="flex-item lilita-one" :style="{ color: isAchieved ? 'green' : 'rebeccapurple' }">
        {{ financialGoal.name }}{{ isInactive ? ' - niezrealizowany' : '' }}{{ isAchieved ? ' - zrealizowany' : ''}}
      </h2>
      <h5>
        Aktualna kwota: {{ financialGoal.currentAmount }}zł
      </h5>
      <h5>
        Kwota docelowa: {{ financialGoal.goalAmount }}zł
      </h5>
      <h5>
        {{ isAchieved ? '' : (isInactive ? 'Zabrakło: ' + (financialGoal.goalAmount - financialGoal.currentAmount) + ' zł' : 'Zostało dni: ' + financialGoal.daysRemaining + ' dni') }}
      </h5>
    </div>
    <div class="mx-auto my-auto">
      <button class="button-primary" style="scale: 70%; border-radius: 200px" @click="toggle"><i class="bi bi-gear"/>
      </button>
      <Menu ref="menu" id="overlay_menu" :model="items" :popup="true"/>
    </div>
    <Dialog v-model:visible="delModalVisible" modal header="Potwierdzenie" :style="{ width: '350px' }">
      <h2>
        Czy chcesz usunąć ten cel finansowy?
      </h2>
      <template #footer>
        <Button label="anuluj" icon="bi bi-times" @click="delModalVisible = false" text/>
        <button type="button" class="button-primary" @click="deletefinancialGoal"
                style="font-size: 18px; font-family: Lato, Helvetica, sans-serif">Usuń
        </button>
      </template>
    </Dialog>
    <Dialog v-model:visible="changeModalVisible" modal header="Dodaj kwotę" :style="{ width: '350px' }">
      <span class="p-float-label mt-4 mb-4">
              <InputText id="currAmount" class="w-100" v-model="currentAmount"/>
              <label for="currAmount">Podaj kwotę</label>
      </span>
      <template #footer>
        <Button label="anuluj" icon="bi bi-times" @click="delModalVisible = false" text/>
        <button type="button" class="button-primary" @click="addCurrentAmount" style="font-size: 18px; font-family: Lato, Helvetica, sans-serif">Zapisz</button>
      </template>
    </Dialog>

    <Dialog v-model:visible="infoModalVisible" modal header="Informacje" :style="{ width: '400px' }">
      <h2>
        Cel finansowy: {{ financialGoal.name }}
      </h2>
      <h5>Aktualna kwota: {{ financialGoal.currentAmount }}</h5>
      <h5>Docelowa kwota: {{ financialGoal.goalAmount }}</h5>
      <h5>Data rozpoczęcia: {{ financialGoal.dateStart.date }}</h5>
      <h5>Data zakończenia: {{ financialGoal.dateEnd.date }}</h5>
      <h5>Szczegóły: {{ financialGoal.details }}</h5>
      <template #footer>
        <Button label="anuluj" icon="bi bi-times" @click="infoModalVisible = false" text/>
      </template>
    </Dialog>

  </div>
</template>

<script setup>
import axios from "axios";
import {computed, defineProps, ref, toRefs} from "vue";
import {createToast} from "mosha-vue-toastify";

let delModalVisible = ref(false);
let changeModalVisible = ref(false);
let infoModalVisible = ref(false);
let currentAmount = ref("");
const menu = ref();

const addCurrentAmount = () => {
  let token = sessionStorage.getItem("token");
  const config = {
    headers: { Authorization: `Bearer ${token}` },
  };
  axios
      .put(`http://localhost:8741/api/financialGoal/addCurrentAmount/${financialGoal.value.id}`, {
        currentAmount: currentAmount.value,
      }, config)
      .then((response) => {
        console.log(response);
        changeModalVisible.value = false;
        financialGoal.value.currentAmount = parseFloat(financialGoal.value.currentAmount) + parseFloat(currentAmount.value);
        createToast(
            {
              title: `Dodano kwotę do celu "${financialGoal.value.name}".`,
            },
            {
              showIcon: 'true',
              position: 'top-center',
              type: 'success',
              transition: 'zoom',
            }
        );
      })
      .catch((error) => {
        console.log(error.response.data);
      });
};

const deletefinancialGoal = () => {
  let token = sessionStorage.getItem("token");
  const config = {
    headers: {Authorization: `Bearer ${token}`}
  };
  axios.delete(`http://localhost:8741/api/financialGoal/delete-financialGoal/${financialGoal.value.id}`, config)
      .then(response => {
        console.log(response)
        delModalVisible.value = false;
        createToast({
              title: `Cel finansowy ${financialGoal.value.name} został usunięty.`,
              description: 'Nie masz już dostępu do tego celu finansowego.'
            },
            {
              showIcon: 'true',
              position: 'top-center',
              type: 'success',
              transition: 'zoom',
            })
      })
      .catch(error => {
        console.log(error)
      })
}

const props = defineProps({
  financialGoal: Object
})

const { financialGoal } = toRefs(props);
//Wyliczenie ile dni zostało do zakończenia celu finansowego
const endDateString = financialGoal.value.dateEnd.date;
const endDate = new Date(endDateString);
const today = new Date();
const timeDiff = endDate - today;
const daysRemaining = Math.floor(timeDiff / (1000 * 60 * 60 * 24));
financialGoal.value.daysRemaining = daysRemaining;

const maxWidthStyles = {
  maxWidth: '450px',
  boxShadow: '0 0 10px 2px rgba(0, 0, 0, 0.66)',
  backgroundColor: financialGoal.value.currentAmount >= financialGoal.value.goalAmount
      ? 'rgb(152,251,152)'
      : financialGoal.value.daysRemaining < 0
          ? 'rgba(213, 184, 255)'
          : 'white',
};


const isInactive = financialGoal.value.daysRemaining < 0;
const isAchieved = computed(() => parseFloat(financialGoal.value.currentAmount) >= parseFloat(financialGoal.value.goalAmount));


const items = ref([
  {
    label: 'Właściwości',
    items: [
      {
        label: 'Dodaj kwotę',
        icon: 'bi bi-plus-circle',
        command: () => {
          changeModalVisible.value = true;
          currentAmount.value = ""; //financialGoal.value.currentAmount;
        },
        disabled: isAchieved.value || isInactive, // Disable the option when achieved or inactive
      },
      {
        label: 'Usuń',
        icon: 'bi bi-trash',
        command: () => {
          delModalVisible.value = true;
        }
      },
      {
        label: 'Informacje',
        icon: 'bi bi-info-circle',
        command: () => {
          infoModalVisible.value = true;
        }
      },
    ]
  }
]);

const toggle = (event) => {
  menu.value.toggle(event);
};

</script>

<style scoped>

</style>