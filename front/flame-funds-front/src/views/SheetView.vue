<template>
  <div>
      <icon-header title="Arkusze" icon="bi bi-file-earmark-spreadsheet-fill" class="mt-3"></icon-header>
    <div v-if="sheetsId===null" class="mt-4 sh" style="color: #606060;">
      <header-component title="Stwórz arkusze" style="font-size: 24px"/>
      <div class="form mx-auto">
        <span class="p-float-label mt-4 mb-4">
          <InputText id="title" class="w-100" v-model="title"/>
          <label for="title">Nazwa arkusza</label>
        </span>
        <div style="display:flex">
          <label for="role" class="w-75 mx-auto"> Czy chcesz mieć możliwość edycji arkusza? </label>
          <Checkbox id="role" class="my-auto" style="scale: 150%" v-model="isWriter" :binary="true" value="writer"/>
        </div>
        <div style="display:flex" class="mt-4 mb-4">
          <label for="ingredient1" class="w-75 mx-auto"> Czy chcesz użyć emailu przypisanego do konta? </label>
          <Checkbox class="my-auto" style="scale: 150%" v-model="defaultEmail" :binary="true"/>
        </div>
        <div v-if="!defaultEmail">
          <span class="p-float-label mt-4 mb-4">
            <InputText id="email" class="w-100" v-model="email"/>
            <label for="email">Podaj email do konta google</label>
        </span>
        </div>
      </div>
      <button  class="button-primary mt-5 mb-4"><i class="bi bi-file-earmark-spreadsheet-fill  me-3"/>Stwórz</button>
    </div>
    <div v-else-if="sheetsId" class="mt-4 sh">
      <div class="lilita-one container ">
        <div style="font-size: 32px" class="my-auto">
          Twój arkusz
        </div>
        <i class="bi bi-arrow-right-short"></i>
        <a class="jump" :href="'https://docs.google.com/spreadsheets/d/'+this.sheetsId+'/edit#gid=0'" target="_blank">
          <i class="bi bi-file-earmark-spreadsheet" style="color:green"></i>
        </a>
      </div>
      <Card class="h-25 w-75 mx-auto">
        <template #title> Instrukcja</template>
        <template #content>
          <p>
            Pamiętaj aby nie naruszać poprawności danych w arkuszu!
            Możesz tworzyć dodatkowe zestawienia, jednak pierwotne dane nie powinny być zmieniane.
          </p>
        </template>
      </Card>
    </div>

  </div>
</template>
<script>
import IconHeader from "@/components/IconHeader.vue";
import axios from "axios";
import HeaderComponent from "@/components/Header.vue";

export default {
  name: "SheetView",
  components: {HeaderComponent, IconHeader},
  data() {
    return {
      sheetsId: null,
      title: '',
      isWriter: false,
      email:'',
      defaultEmail: true
    }
  },

  beforeMount() {
    this.getSheetId()
  },

  methods: {
    async getSheetId() {
      let token = sessionStorage.getItem("token");
      const config = {
        headers: {Authorization: `Bearer ${token}`}
      };
      await axios.get("http://localhost:8741/api/google/id", config)
          .then(response => {
            console.log(response)
            this.sheetsId = response.data
          })
          .catch(error => {
            console.log(error)
          })

    },
  },
}
</script>
<style scoped>
.form{
  max-width: 300px;
}
.sh {
  box-shadow: 0 0 40px 40px rgba(255, 255, 255, 0.9);
  background-color: rgba(255, 255, 255, 0.9);
}

.container {
  color: #9646e3;
  font-size: 48px;
  display: flex;
  justify-content: center;
}
</style>