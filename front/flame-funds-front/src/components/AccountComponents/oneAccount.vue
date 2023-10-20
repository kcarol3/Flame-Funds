<template>
  <div class="d-flex border border-2 rounded-4 my-auto body"
       style="max-width: 320px;box-shadow: 0 0 10px 2px rgba(0,0,0,0.66);">
    <div class="mx-auto my-auto">
      <h2 class="flex-item lilita-one" style="color: rebeccapurple">
        {{ account.name }}
      </h2>
      <h5>
        Saldo: {{ account.balance }}zł
      </h5>
    </div>
    <div class="mx-auto my-auto">
      <button class="button-primary" style="scale: 70%; border-radius: 200px" @click="toggle"><i class="bi bi-gear"/>
      </button>
      <Menu ref="menu" id="overlay_menu" :model="items" :popup="true"/>
    </div>
    <Dialog v-model:visible="delModalVisible" modal header="Potwierdzenie" :style="{ width: '350px' }">
      <h2>
        Czy chcesz usunąć to konto?
      </h2>
      <template #footer>
        <Button label="anuluj" icon="bi bi-times" @click="delModalVisible = false" text/>
        <button type="button" class="button-primary" @click="deleteAccount"
                style="font-size: 18px; font-family: Lato, Helvetica, sans-serif">Usuń
        </button>
      </template>
    </Dialog>
    <Dialog v-model:visible="changeModalVisible" modal header="Zmiana nazwy" :style="{ width: '350px' }">
      <span class="p-float-label mt-4 mb-4">
              <InputText id="catName" class="w-100" v-model="name"/>
              <label for="catName">Nazwa konta</label>
      </span>
      <template #footer>
        <Button label="anuluj" icon="bi bi-times" @click="delModalVisible = false" text/>
        <button type="button" class="button-primary" @click="changeAccountName" style="font-size: 18px; font-family: Lato, Helvetica, sans-serif">Zapisz</button>
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
let name = ref("");
const menu = ref();

const items = ref([
  {
    label: 'Właściwości',
    items: [
      {
        label: 'Zmień nazwę',
        icon: 'bi bi-pencil-square',
        command: () => {
          changeModalVisible.value = true;
          name.value = account.value.name;
        },
      },
      {
        label: 'Wybierz konto',
        icon: 'bi bi-star',
        command: () => {
          let token = sessionStorage.getItem("token");
          const config = {
            headers: {Authorization: `Bearer ${token}`}
          };

          axios.put(`http://localhost:8741/api/account-change/${account.value.id}`, {}, config)
              .then(response => {
                console.log(response)
                createToast({
                      title: `Zmieniono konto na ${account.value.name}`,
                      description: 'Teraz to konto jest twoim kontem głównym.'
                    },
                    {
                      showIcon: 'true',
                      position: 'top-center',
                      type: 'success',
                      transition: 'zoom',
                    })
              })
              .catch(error => {
                console.log(error.response.data)
              })
        }
      },
      {
        label: 'Usuń',
        icon: 'bi bi-trash',
        command: () => {
          delModalVisible.value = true;
        }
      },
    ]
  }
]);
const changeAccountName = () => {
  let token = sessionStorage.getItem("token");
  const config = {
    headers: {Authorization: `Bearer ${token}`}
  };
  axios.put(`http://localhost:8741/api/account/${account.value.id}`,{
    "name":name.value,
  }, config)
      .then(response => {
        console.log(response)
        changeModalVisible.value = false;
        account.value.name = name.value;
        createToast({
              title: `Nazwa konta została zmieniona.`,
            },
            {
              showIcon: 'true',
              position: 'top-center',
              type: 'success',
              transition: 'zoom',
            })
      })
      .catch(error => {
        console.log(error.response.data)
      })
}

const deleteAccount = () => {
  let token = sessionStorage.getItem("token");
  const config = {
    headers: {Authorization: `Bearer ${token}`}
  };
  axios.delete(`http://localhost:8741/api/account/${account.value.id}`, config)
      .then(response => {
        console.log(response)
        delModalVisible.value = false;
        createToast({
              title: `Konto ${account.value.name} zostało usunięte.`,
              description: 'Nie masz już dostępu do tego konta.'
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
  account: Object
})

const {account} = toRefs(props);

const toggle = (event) => {
  menu.value.toggle(event);
};

</script>

<style scoped>

</style>