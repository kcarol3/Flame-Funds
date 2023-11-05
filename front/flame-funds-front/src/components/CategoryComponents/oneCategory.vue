<template>
<div>
  <div class="border border-2 rounded-4 my-auto body mx-auto mt-3 bg-white d-flex" style="max-width: 320px;box-shadow: 0 0 10px 2px rgba(0,0,0,0.66)">
    <div class="mx-auto">
      <h3 style="color: rebeccapurple">
        {{ this.name }}
      </h3>
      <h5>
        {{ this.details }}
      </h5>
    </div>
    <button label="Toggle" @click="toggle" aria-haspopup="true" aria-controls="overlay_menu" class="button-primary but my-auto">
      <i class="bi bi-gear" style="font-size: 32px"/>
    </button>
    <Menu ref="menu" id="overlay_menu" :model="items" :popup="true" />
    <Dialog v-model:visible="delModalVisible" modal header="Potwierdzenie" :style="{ width: '350px' }">
      <h2>
        Czy chcesz usunąć tą kategorię?
      </h2>
      <template #footer>
        <Button label="anuluj" icon="bi bi-times" @click="delModalVisible = false" text/>
        <button type="button" class="button-primary" @click="deleteCategory" style="font-size: 18px; font-family: Lato, Helvetica, sans-serif">
          Usuń
        </button>
      </template>
    </Dialog>
    <Dialog v-model:visible="changeModalVisible" modal header="Edytuj kategorie" :style="{ width: '350px' }">
      <span class="p-float-label mt-4 mb-4">
              <InputText id="catName" class="w-100" v-model="name"/>
              <label for="catName">Nazwa kategorii</label>
      </span>
      <span class="p-float-label mt-4 mb-4">
              <InputText id="catName" class="w-100" v-model="details"/>
              <label for="catName">Szczegóły</label>
      </span>
      <template #footer>
        <Button label="anuluj" icon="bi bi-times" @click="this.changeModalVisible = false" text/>
        <button type="button" class="button-primary" @click="this.editCategory" style="font-size: 18px; font-family: Lato, Helvetica, sans-serif">Zapisz</button>
      </template>
    </Dialog>
  </div>
</div>
</template>
<script>
import axios from "axios";
import {createToast} from "mosha-vue-toastify";

export default {
  name: "oneCategory",

  props:{
    category: Object,
    categoryType: String
  },

  data(){
    return {
      name: this.category.name,
      details: this.category.details,
      delModalVisible: false,
      changeModalVisible: false,
      items:
          [{
            label: 'Właściwości',
            items: [
              {
                label: 'Edytuj',
                icon: 'bi bi-pencil-square',
                command: () => {
                  this.changeModalVisible = true;
                },
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
    }
  },

  methods:{
    editCategory() {
      let token = sessionStorage.getItem("token");
      const config = {
        headers: {Authorization: `Bearer ${token}`}
      };
      axios.put(`http://localhost:8741/api/category/${this.categoryType}/${this.category.id}`,{
        "name": this.name,
        "details": this.details,
      }, config)
          .then(response => {
            console.log(response)
            this.changeModalVisible = false;
            createToast({
                  title: `Dane kategorii zostały zmienione`,
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

    deleteCategory(){
      let token = sessionStorage.getItem("token");
      const config = {
        headers: {Authorization: `Bearer ${token}`}
      };
      axios.delete(`http://localhost:8741/api/category/${this.categoryType}/${this.category.id}`, config)
          .then(response => {
            console.log(response)
            this.delModalVisible = false;
            createToast({
                  title: `Kategoria ${this.name} została usuniętą.`,
                  description: 'Nie masz już dostępu do tej kategorii.'
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
  },
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