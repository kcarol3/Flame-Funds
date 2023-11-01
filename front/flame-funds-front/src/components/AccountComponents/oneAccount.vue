<template>
  <div class="d-flex border border-2 rounded-4 my-auto body"
       style="max-width: 320px;box-shadow: 0 0 10px 2px rgba(0,0,0,0.66);">
    <div class="mx-auto my-auto">
      <h2 class="flex-item lilita-one" style="color: rebeccapurple">
        {{ localAccount.name }}
      </h2>
      <h5>
        Saldo: {{ localAccount.balance }}zł
      </h5>
    </div>
    <div class="my-auto">
      <button label="Toggle" @click="toggle" aria-haspopup="true" aria-controls="overlay_menu" class="button-primary but my-auto">
        <i class="bi bi-gear" style="font-size: 32px"/>
      </button>
      <Menu ref="menu" id="overlay_menu" :model="items" :popup="true" />
    </div>
    <Dialog v-model:visible="delModalVisible" modal header="Potwierdzenie" :style="{ width: '350px' }">
      <h2>
        Czy chcesz usunąć to konto?
      </h2>
      <template #footer>
        <Button label="anuluj" icon="bi bi-times" @click="delModalVisible = false" text/>
        <button type="button" class="button-primary" @click="deleteAccount" style="font-size: 18px; font-family: Lato, Helvetica, sans-serif">
          Usuń
        </button>
      </template>
    </Dialog>
    <Dialog v-model:visible="changeModalVisible" modal header="Zmiana nazwy" :style="{ width: '350px' }">
      <span class="p-float-label mt-4 mb-4">
              <InputText id="catName" class="w-100" v-model="name"/>
              <label for="catName">Nazwa konta</label>
      </span>
      <template #footer>
        <Button label="anuluj" icon="bi bi-times" @click="this.delModalVisible = false" text/>
        <button type="button" class="button-primary" @click="changeAccountName" style="font-size: 18px; font-family: Lato, Helvetica, sans-serif">Zapisz</button>
      </template>
    </Dialog>
  </div>
</template>

<script>
import axios from "axios";
import {createToast} from "mosha-vue-toastify";
export default {
  props:{
   account: Object,
  },

  created() {
    this.localAccount = { ...this.account };
  },

  data() {
    return {
      items:
        [{
          label: 'Właściwości',
          items: [
            {
              label: 'Zmień nazwę',
              icon: 'bi bi-pencil-square',
              command: () => {
                this.changeModalVisible = true;
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

                axios.put(`http://localhost:8741/api/account-change/${this.account.id}`, {}, config)
                    .then(response => {
                      console.log(response)
                      createToast({
                            title: `Zmieniono konto na ${this.account.name}`,
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
                this.delModalVisible = true;
              }
            },
          ]
        }
      ],
      localAccount: {},
      delModalVisible: false,
      changeModalVisible: false,
      show: false,
      name:this.account.name,
    }
  },
  methods: {
    changeAccountName() {
      let token = sessionStorage.getItem("token");
      const config = {
        headers: {Authorization: `Bearer ${token}`}
      };
      axios.put(`http://localhost:8741/api/account/${this.account.id}`,{
        "name":this.name,
      }, config)
          .then(response => {
            console.log(response)
            this.changeModalVisible = false;
            this.localAccount.name = this.name;
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
    },

    deleteAccount(){
      let token = sessionStorage.getItem("token");
      const config = {
        headers: {Authorization: `Bearer ${token}`}
      };
      axios.delete(`http://localhost:8741/api/account/${this.account.id}`, config)
          .then(response => {
            console.log(response)
            this.delModalVisible = false;
            createToast({
                  title: `Konto ${this.name} zostało usunięte.`,
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
    },

    toggle(event) {
      this.$refs.menu.toggle(event);
    },
  }

}
</script>

<style scoped>
.but{
  width:67px;
  height:67px;
  border-radius: 40px;
  scale: 70%;
}
</style>