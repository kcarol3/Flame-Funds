<template>
  <div class="mx-auto justify-content-center">
    <icon-header :title="yearlyReportTitle" icon="bi bi-pie-chart-fill" font-size="44px"></icon-header>
  </div>
  <div class="row">
    <div class="col-md-6" v-for="(quarterData, index) in quarterlyData" :key="index">
      <div>
        <small-icon-header :title="`Kwartał ${index + 1}`"></small-icon-header>
      </div>
      <div :id="`chart${index + 1}`" className="text-center">
        <apexchart :options="quarterData.options" :series="quarterData.series" type="pie" width="80%" />
      </div>
    </div>
  </div>
</template>

<script>
import VueApexCharts from "vue3-apexcharts";
import IconHeader from "@/components/IconHeader";
import SmallIconHeader from "@/components/SmallIconHeader";
import axios from "axios";

export default {
  components: {
    apexchart: VueApexCharts,
    IconHeader,
    SmallIconHeader
  },
  data() {
    return {
      quarterlyData: [],
      yearlyReportTitle: "",
    };
  },
  mounted() {
    this.fetchQuarterlyData();
  },
  methods: {
    async fetchQuarterlyData() {
      let token = sessionStorage.getItem("token");
      const config = {
        headers: { Authorization: `Bearer ${token}` },
      };
      try {
        const response = await axios.get("http://localhost:8741/api/quarterReport", config);

        const currentYear = new Date().getFullYear();
        this.yearlyReportTitle = `Roczny raport finansowy ${currentYear}`;

        // Przekształć dane z odpowiedzi na oczekiwany format
        this.quarterlyData = Object.keys(response.data).map((quarterKey) => ({
          series: Object.values(response.data[quarterKey]),
          options: {
            labels: Object.keys(response.data[quarterKey]),
            // Możesz dodać inne opcje konfiguracji wykresu tutaj
          },
        }));
      } catch (error) {
        console.error("Błąd podczas pobierania danych:", error);
      }
    },
  },
};
</script>
