<template>
  <div class="d-flex border border-2 rounded-4 my-auto body"
       style="max-width: 400px;box-shadow: 0 0 10px 2px rgba(0,0,0,0.66);">
    <div class="mx-auto my-auto">
      <h2 class="flex-item lilita-one" style="color: rebeccapurple">
        {{ periodic.name }}
      </h2>
      <h5>
        Kwota: {{ periodic.amount }} zł
      </h5>
      <h5>
        Powtarzalność: co {{ periodic.days }} dni
      </h5>
      <h5>
        Pozostało płatności: {{ periodic.paymentsRemaining }}
      </h5>
    </div>
    <div class="mx-auto my-auto">
      <button class="button-primary" style="scale: 70%; border-radius: 200px" @click="toggle"><i class="bi bi-gear"/>
      </button>
      <Menu ref="menu" id="overlay_menu" :model="items" :popup="true"/>
    </div>
    <Dialog v-model:visible="delModalVisible" modal header="Potwierdzenie" :style="{ width: '350px' }">
      <h2>
        Czy chcesz usunąć tą płatność cykliczną?
      </h2>
      <template #footer>
        <Button label="anuluj" icon="bi bi-times" @click="delModalVisible = false" text/>
        <button type="button" class="button-primary" @click="deletePeriodic"
                style="font-size: 18px; font-family: Lato, Helvetica, sans-serif">Usuń
        </button>
      </template>
    </Dialog>
    <Dialog v-model:visible="changeModalVisible" modal header="Zmiana nazwy" :style="{ width: '350px' }">
      <span class="p-float-label mt-4 mb-4">
              <InputText id="periodicName" class="w-100" v-model="name"/>
              <label for="periodicName">Nazwa płatności</label>
      </span>
      <template #footer>
        <Button label="anuluj" icon="bi bi-times" @click="delModalVisible = false" text/>
        <button type="button" class="button-primary" @click="changePeriodicName" style="font-size: 18px; font-family: Lato, Helvetica, sans-serif">Zapisz</button>
      </template>
    </Dialog>

    <Dialog v-model:visible="infoModalVisible" modal header="Informacje" :style="{ width: '400px' }">
      <h2>
        Płatność cykliczna: {{ periodic.name }}
      </h2>
      <h5>Kwota: {{ periodic.amount }}</h5>
      <h5>Data rozpoczęcia: {{ periodic.dateStart.date }}</h5>
      <h5>Data zakończenia: {{ periodic.dateEnd.date }}</h5>
      <h5>Ile płatności pozostało: {{ periodic.paymentsRemaining }}</h5>
      <h5>Co ile dni: {{ periodic.days }}</h5>
      <h5>Szczegóły: {{ periodic.details }}</h5>
      <template #footer>
        <Button label="anuluj" icon="bi bi-times" @click="infoModalVisible = false" text/>
      </template>
    </Dialog>

  </div>
</template>

<script setup>
import axios from "axios";
import {defineProps, ref, toRefs} from "vue";
import {createToast} from "mosha-vue-toastify";

let delModalVisible = ref(false);
let changeModalVisible = ref(false);
let infoModalVisible = ref(false);

let name = ref("");
const menu = ref();

const items = ref([
  {
    label: 'Właściwości',
    items: [
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

const deletePeriodic = () => {
  let token = sessionStorage.getItem("token");
  const config = {
    headers: {Authorization: `Bearer ${token}`}
  };
  axios.delete(`http://localhost:8741/api/periodic/delete-periodic/${periodic.value.id}`, config)
      .then(response => {
        console.log(response)
        delModalVisible.value = false;
        createToast({
              title: `Płatność cykliczna ${periodic.value.name} została usunięta.`,
              description: 'Nie masz już dostępu do tej płatności cyklicznej.'
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
  periodic: Object
})

const {periodic} = toRefs(props);

const toggle = (event) => {
  menu.value.toggle(event);
};


const today = new Date();
const endDateString = periodic.value.dateEnd.date;
const endDate = new Date(endDateString);
const daysRemaining = Math.floor((endDate - today) / (1000 * 60 * 60 * 24));
periodic.value.daysRemaining = daysRemaining;

const daysBetweenPayments = periodic.value.days;
const paymentsRemaining = Math.floor(daysRemaining / daysBetweenPayments);
periodic.value.paymentsRemaining = paymentsRemaining;

</script>

<style scoped>

</style>