<template>
  <div class="mx-auto justify-content-center">
    <icon-header :title="yearlyReportTitle" icon="bi bi-pie-chart-fill" font-size="44px"></icon-header>
  </div>
  <div id="chart" class="text-center">
    <apexchart :options="chartOptions" :series="series" type="pie" width="600"/>
  </div>
</template>

<script>
import VueApexCharts from "vue3-apexcharts";
import IconHeader from "@/components/IconHeader";
import axios from "axios";

export default {
  components: {
    apexchart: VueApexCharts,
    IconHeader,
  },
  data() {
    return {
      series: [],
      chartOptions: {
          width: 380,
          type: 'pie',
          colors: ["#fee327", "#fdca54", "#f6a570", "#f1969b", "#f08ab1", "#c78dbd", "#927db6", "#5da0d7", "#00b3e1", "#50bcbf", "#65bda5", "#87bf54"],
        labels: ['Styczeń', 'Luty', 'Marzec', 'Kwiecień', 'Maj', 'Czerwiec', 'Lipiec', 'Sierpień', 'Wrzesień',
          'Październik', 'Listopad', 'Grudzień'],
        responsive: [{
          breakpoint: 480,
          options: {
            chart: {
              width: 200,
            },
            legend: {
              position: 'bottom',
            },
          },
        }],
      },
      yearlyReportTitle: "", // Dodaj pole na tytuł raportu rocznego
    };
  },
  mounted() {
    this.fetchYearlyReport();
  },
  methods: {
    async fetchYearlyReport() {
      let token = sessionStorage.getItem("token");
      const config = {
        headers: {Authorization: `Bearer ${token}`}
      };
      try {
        const response = await axios.get("http://localhost:8741/api/yearlyReport", config);

        // Aktualizuj etykiety w opcjach wykresu na podstawie danych z odpowiedzi
        this.chartOptions.labels = response.data.map(item => item.name);
        this.series = response.data;

        // Aktualizuj tytuł raportu rocznego z rokiem
        const currentYear = new Date().getFullYear();
        this.yearlyReportTitle = `Roczny raport wydatków ${currentYear}`;
      } catch (error) {
        console.error("Błąd podczas pobierania danych:", error);
      }
    },
  },
};
</script>
