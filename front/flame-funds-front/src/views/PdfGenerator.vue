<template>
  <div class="mx-auto justify-content-center">
    <icon-header title="Podsumowanie roczne" icon="bi bi-calendar2-heart" font-size="44px"></icon-header>
    <br>
    <Button label="Wygeneruj dokument" icon="bi bi-calendar2-heart" @click="generatePdf" />
  </div>
</template>

<script setup>
import IconHeader from "@/components/IconHeader.vue";
import axios from "axios";


const generatePdf = async () => {
  try {
    let token = sessionStorage.getItem("token");
    const config = {
      headers: { Authorization: `Bearer ${token}` },
      responseType: 'blob',
    };

    const response = await axios.get('http://localhost:8741/api/generatePdf', config);

    const url = window.URL.createObjectURL(new Blob([response.data]));

    const a = document.createElement('a');
    a.href = url;
    a.download = 'podsumowanie_roczne.pdf';
    document.body.appendChild(a);
    a.click();

    window.URL.revokeObjectURL(url);
    document.body.removeChild(a);
  } catch (error) {
    console.error('Błąd podczas pobierania pliku PDF:', error);
  }
};
</script>
