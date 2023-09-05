<template>
  <div class="container-fluid d-flex align-items-center w-100" style="background: #d9c5ed; height: 70px;position: fixed; top: 0;">
    <SidebarMenu/>
    <div style = "width: 70%;">
      <router-link to="/home">
        <img  src="../assets/logo.png" class="mx-auto mt-2 button-primary" style="padding: 0;width: 64px;height: 64px; border-radius: 200px">
      </router-link>
    </div>
    <i class="bi bi-person-fill-gear mx-auto mt-2 click-animation" style="font-size: 60px;padding: 0;" @click="toggle" aria-haspopup="true" aria-controls="overlay_menu"/>
    <Menu ref="menu" id="overlay_menu" :model="items" :popup="true" />
  </div>
  <div style="background: linear-gradient(to bottom, #d9c5ed, rgba(255,255,255, 0%)); width: 100%; height: 30px; margin-top: 70px;"></div>
</template>
<script setup>
import { ref } from "vue";
import SidebarMenu from "@/components/SidebarMenu.vue";

const menu = ref();
const items = ref([
  {
    label: 'Konta',
    items: [
      {
        label: 'Dodaj konto',
        icon: 'bi bi-bank',
        to: "/add-account"
      },
      {
        label: 'Zmień konto',
        icon: 'bi bi-arrow-repeat',
        to: "/accounts"
      }
    ]
  },
  {
    label: 'Użytkownik',
    items: [
      {
        label: 'Vue Website',
        icon: 'pi pi-external-link',
        url: 'https://vuejs.org/'
      },
      {
        label: 'Router',
        icon: 'pi pi-upload',
        to: '/fileupload'
      }
    ]
  }
]);

const toggle = (event) => {
  menu.value.toggle(event);
};

</script>

<script>

export default {

  name: "NavbarComponent",

  data(){
    return{

    }
  },

}
</script>

<style scoped>

</style>