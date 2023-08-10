<template>
  <div>
    <navbar-component></navbar-component>
    <header-component title="Witaj!" class="mt-3"></header-component>
    <div class="mx-auto mt-4 py-3 lilita-one rounded border border-1 border-black shadow" style="width: 70%; background: #d9c5ed;color: gray">
      <h3>Saldo</h3>
      <h4>{{balance}}zł</h4>
    </div>
  </div>
</template>

<script>
import NavbarComponent from "@/components/Navbar.vue";
import HeaderComponent from "@/components/Header.vue";

export default {
  name: "HomeView",
  components: {HeaderComponent, NavbarComponent},

  data(){
    return {
      balance: 0.0
    }
  },
}
</script>

<style scoped>

</style>