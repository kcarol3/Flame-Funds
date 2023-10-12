<template>
<div>
  <h3 class="lilita-one">
    Twój token wygasł.
  </h3>
  <h6>
    Czy chcesz wydłużyć sesję?
  </h6>
  <div style="display: flex" class="mt-1">
    <Button @click="logout" class="button-secondary mx-auto" style="font-size: 16px; scale: 0.85">Nie</Button>
    <Button @click="refreshToken" class="button-primary mx-auto" style="font-size: 16px; scale: 0.85">Tak</Button>
  </div>
</div>
</template>
<script>
import axios from "axios";
import {clearToasts} from "mosha-vue-toastify";
export default {
  name: "RefreshTokenDialog",
  props: ['next'],
  methods: {
    logout(){
      sessionStorage.removeItem("token");
      sessionStorage.removeItem("refresh_token");

      clearToasts();
      this.next('/login')
    },

    refreshToken(){
      let refToken = sessionStorage.getItem("refresh_token");
      axios.post("http://localhost:8741/api/token/refresh", {
        'refresh_token': refToken
      }).then(response => {
        sessionStorage.removeItem("token");
        sessionStorage.removeItem("refresh_token");

        sessionStorage.setItem("token", response.data.token);
        sessionStorage.setItem("refresh_token", response.data.refresh_token);

        clearToasts();
        this.next();
      }).catch(error => {
        console.log(error)
      })
    },
  },
}
</script>

<style scoped>

</style>