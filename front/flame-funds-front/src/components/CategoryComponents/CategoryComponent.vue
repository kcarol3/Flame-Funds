<template>
<div>
  <header-component :title="this.category.name" class=" mt-3" style="font-size: 24px; max-width: 500px"/>
  <div class="jump">
    <i class="bi bi-arrow-up-short icon " style="font-size:34px; color: rebeccapurple"></i>
  </div>
  <ScrollPanel class="mx-auto" :class="{ 'scrollPanel': this.categories.length > 3}">
    <div v-for="(item, index) in categories" :key="index">
      <one-category :category="item" :categoryType = "this.category.type"></one-category>
    </div>
  </ScrollPanel>
  <div class="jump">
    <i class="bi bi-arrow-down-short icon " style="font-size:34px; color: rebeccapurple"></i>
  </div>
</div>
</template>
<script>
import HeaderComponent from "@/components/Header.vue";
import axios from "axios";
import OneCategory from "@/components/CategoryComponents/oneCategory.vue";

export default {
  name: "CategoryComponent",
  components: {OneCategory, HeaderComponent},
  props:{
    category: Object,
  },
  created() {
    this.getCategories()
  },
  data(){
    return{
      categories: [],
    }
  },

  methods:{
    getCategories() {
      let token = sessionStorage.getItem("token");
      const config = {
        headers: {Authorization: `Bearer ${token}`}
      };
      axios.get(`http://localhost:8741/api/category/${this.category.type}`,config)
          .then(response => {
            console.log(response)
            this.categories = response.data
          })
          .catch(error => {
            console.log(error)
          })
    },
  },

}
</script>
<style scoped>
.scrollPanel{
  height: 30vh;
}
</style>