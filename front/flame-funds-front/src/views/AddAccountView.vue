<template>
  <div>
    <icon-header :two-lines=true title="Dodaj konto" icon="bi bi-bank" font-size="48px"></icon-header>
    <div class="container" style="width: 300px">
    <span class="p-float-label mt-5">
      <InputText id="name" class="w-100" v-model="name"/>
      <label for="name">Nazwa konta</label>
    </span>
    <span class="p-float-label mt-5">
      <InputNumber id="amount" :step="100" v-model="balance" inputId="stacked-buttons" showButtons mode="currency" currency="PLN" :min="0"/>
      <label for="amount">Początkowa kwota</label>
    </span>
      <button @click="addAccount" class="button-primary mt-5 mb-4 "><i class="bi bi-bank me-1"/>Dodaj</button>
    </div>
    <return-button link="/home"></return-button>
  </div>
</template>

<script>
import IconHeader from "@/components/IconHeader.vue";
import ReturnButton from "@/components/ReturnButton.vue";
import axios from "axios";
import {createToast} from "mosha-vue-toastify";

export default {
  name: "AddAccountView",
  components: {ReturnButton, IconHeader},
  data(){
   return{
     balance:0,
     name:""
   }
  },
  methods:{
    addAccount(){
      let token = sessionStorage.getItem("token");
      const config = {
        headers: {Authorization: `Bearer ${token}`}
      };
      axios.post("http://localhost:8741/api/account/add-account", {
        "name":this.name,
        "balance":this.balance
      }, config)
          .then(response=>{
            console.log(response)
            createToast({
                  title: 'Dodano konto.',
                  description: 'Możesz teraz zmienić wybrane konto i dodawać wydatki oraz przychody.'
                },
                {
                  showIcon: 'true',
                  position: 'top-center',
                  type: 'success',
                  transition: 'zoom',
                })
            this.$router.push("/accounts")
          })
          .catch(error=>{
            console.log(error)
            createToast({
                  title: 'Nie udało się dodać wydatku!',
                  description: 'Napotkano jakiś błąd.'
                },
                {
                  position: 'top-center',
                  showIcon: 'true',
                  type: 'danger',
                  transition: 'zoo',
                  showCloseButton: true,
                })
          })
    },
  }
}
</script>

<style scoped>

</style>