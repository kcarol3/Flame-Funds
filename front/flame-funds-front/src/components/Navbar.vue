<template>
  <div>
  <div class="container-fluid d-flex align-items-center w-100 "
       style="background: #d9c5ed; height: 70px;position: fixed; top: 0; box-shadow: 0px 5px 25px 16px rgba(217, 197, 237, 1);">
    <SidebarMenu/>
    <div style="width: 70%;">
      <router-link to="/home">
        <img src="../assets/logo.png" class="mx-auto mt-2 button-primary"
             style="padding: 0;width: 64px;height: 64px; border-radius: 200px">
      </router-link>
    </div>
    <i class="bi bi-person-fill-gear mx-auto mt-2 click-animation" style="font-size: 60px;padding: 0;" @click="toggle"
       aria-haspopup="true" aria-controls="overlay_menu"/>
    <Menu ref="menu" id="overlay_menu" :model="items" :popup="true"/>
  </div>
    <div style="height: 30px"></div>
  </div>
</template>
<script setup>
import {ref} from "vue";
import SidebarMenu from "@/components/SidebarMenu.vue";
import { useRouter } from 'vue-router';
const router = useRouter()

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
    label: 'Arkusze',
    icon: 'bi bi-file-earmark-spreadsheet-fill',
    to: '/sheets'
  },
  {
    label: 'Roczne posumowanie',
    icon: 'bi bi-calendar2-heart',
    to: '/generatePdf'
  },
  {
    label: 'Wyloguj',
    icon: 'bi bi-box-arrow-right',
    command: () => {
      sessionStorage.removeItem("token");
      sessionStorage.removeItem("refresh_token");
      router.push('/login')
    },
  }
]);

const toggle = (event) => {
  menu.value.toggle(event);
};

</script>

<script>

export default {

  name: "NavbarComponent",

  data() {
    return {}
  },

}
</script>

<style scoped>

</style>