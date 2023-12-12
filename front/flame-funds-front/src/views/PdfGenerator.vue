<template>
  <div class="mx-auto justify-content-center">
    <icon-header title="Podsumowanie roczne" icon="bi bi-calendar2-heart" font-size="44px"></icon-header>
    <br>
    <Card class="h-25 w-75 mx-auto mb-4">
      <template #title> Instrukcja</template>
      <template #content>
        <p>
          <h4> Po naciśnięciu przycisku zostanie wygenerowany i pobrany dokument pdf zawierający podsumowanie Twojego budżetu w bieżącym roku.</h4>
        </p>
        <br/>
      </template>
    </Card>
    <Button label="Wygeneruj dokument" icon="bi bi-calendar2-heart" @click="generatePdf" />
  </div>
  <p></p><return-button link="/home"></return-button>
</template>

<script setup>
import IconHeader from "@/components/IconHeader.vue";
import axios from "axios";
import ReturnButton from "@/components/ReturnButton.vue";



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
