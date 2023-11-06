<template>
<div>
  <icon-header title="Kategorie" icon="bi bi-tags-fill" class="mt-3"></icon-header>
  <div v-if="!showCategory">
    <header-component title="Rodzaje kategorii" class=" mt-3" style="font-size: 24px; max-width: 500px"/>
    <div v-for="(category, index) in categories" :key=index>
      <button class="button-primary m-3" style="font-size: 24px; width: 300px;" @click="this.chooseCategory(category)">
        <i :class="category.icon" class="me-2" />{{category.name}}
      </button>
    </div>
    <return-button link="/home" class="m-auto mb-3 mt-5"></return-button>
  </div>
  <div v-else>
    <category-component :category="this.category" class="sh mx-auto"/>
    <button class="button-primary mt-5" @click="showCategory=false"><i class="bi bi-arrow-90deg-up"/></button>
  </div>
</div>
</template>
<script>
import IconHeader from "@/components/IconHeader.vue";
import HeaderComponent from "@/components/Header.vue";
import ReturnButton from "@/components/ReturnButton.vue";
import CategoryComponent from "@/components/CategoryComponents/CategoryComponent.vue";

export default {
  name: "CategoriesView",
  components: {CategoryComponent, ReturnButton, HeaderComponent, IconHeader},

  data(){
    return{
      showCategory: false,
      category:Object,
      categories:[
        {
          name:"Kategorie wydatków",
          icon:"bi bi-cash-stack",
          type: "expense",
        },
        {
          name:"Kategorie przychodów",
          icon:"bi bi-cash-coin",
          type: "income",
        }
      ],
    }
  },

  methods:{
    chooseCategory(categoryType){
      this.showCategory = true;
      this.category = categoryType;
    },
  },

}
</script>

<style scoped>
.sh{
  box-shadow: 0 0 20px 20px rgba(255, 255, 255, 0.95);
  background-color: rgba(255, 255, 255, 0.95);
  max-width: 450px;
}
@media screen and (max-width: 424px) {
  .sh {
    box-shadow: 0 0 40px 40px rgba(255, 255, 255, 0.95);
    background-color: rgba(255, 255, 255, 0.95);
  }
}
</style>