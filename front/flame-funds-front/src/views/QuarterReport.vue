<template>
  <div class="mx-auto justify-content-center">
    <icon-header :title="yearlyReportTitle" icon="bi bi-pie-chart-fill" font-size="44px"></icon-header>
  </div>
  <div class="row">
    <div class="col-md-6" v-for="(quarterData, index) in quarterlyData" :key="index">
      <div>
        <small-icon-header :title="`Kwartał ${index + 1}`"></small-icon-header>
      </div>
      <div :id="`chart${index + 1}`" class="text-center">
        <apexchart :options="quarterData.options" :series="quarterData.series" type="pie"
                   :width="quarterData.options.chart.width"/>
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
        headers: {Authorization: `Bearer ${token}`},
      };
      try {
        const response = await axios.get("http://localhost:8741/api/quarterReport", config);

        const currentYear = new Date().getFullYear();
        this.yearlyReportTitle = `Kwartalny raport finansowy kategorii wydatków ${currentYear}`;

        // Przekształć dane z odpowiedzi na oczekiwany format
        this.quarterlyData = Object.keys(response.data).map((quarterKey) => ({
          series: Object.values(response.data[quarterKey]),
          options: {
            chart: {
              width: 450, // Zmniejsz szerokość wykresu
            },
            labels: Object.keys(response.data[quarterKey]),
            colors: ["#00b3e1", "#5da0d7", "#87bf54", "#f1969b", "#f08ab1", "#c78dbd", "#fee327",
              "#fdca54", "#c0a0a0", "#f6a570", "#F8CEAB", "#B5D8D6",],
          },
        }));
      } catch (error) {
        console.error("Błąd podczas pobierania danych:", error);
      }
    },
  },
};
</script>
