<template>
  <div className="mx-auto justify-content-center">
    <icon-header :title="yearlyReportTitle" icon="bi bi-pie-chart-fill" font-size="44px"></icon-header>
  </div>
  <div class="row">
    <div class="col-md-6">
      <div>
        <icon-header title="Wydatki"></icon-header>
      </div>
      <div id="chart" className="text-center">
        <apexchart :options="chartOptions1" :series="series1" type="pie" width="80%"/>
      </div>
    </div>
    <div class="col-md-6">
      <div>
        <icon-header title="Przychody"></icon-header>
      </div>
      <div id="chart2" className="text-center">
        <apexchart :options="chartOptions2" :series="series2" type="pie" width="80%"/>
      </div>
    </div>
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
      series1: [],
      chartOptions1: {
        width: 380,
        type: 'pie',
        colors: [
          "#00b3e1", // Styczeń
          "#5da0d7", // Luty
          "#87bf54", // Marzec
          "#f1969b", // Kwiecień
          "#f08ab1", // Maj
          "#c78dbd", // Czerwiec
          "#fee327", // Lipiec
          "#fdca54", // Sierpień
          "#c0a0a0", // Wrzesień
          "#f6a570", // Październik
          "#F8CEAB", // Listopad
          "#B5D8D6", // Grudzień
        ],
        labels: ['Styczeń', 'Luty', 'Marzec', 'Kwiecień', 'Maj', 'Czerwiec', 'Lipiec', 'Sierpień', 'Wrzesień', 'Październik', 'Listopad', 'Grudzień'],
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
      series2: [],
      chartOptions2: {
        width: 380,
        type: 'pie',
        colors: [
          "#00b3e1", // Styczeń
          "#5da0d7", // Luty
          "#87bf54", // Marzec
          "#f1969b", // Kwiecień
          "#f08ab1", // Maj
          "#c78dbd", // Czerwiec
          "#fee327", // Lipiec
          "#fdca54", // Sierpień
          "#c0a0a0", // Wrzesień
          "#f6a570", // Październik
          "#F8CEAB", // Listopad
          "#B5D8D6", // Grudzień
        ],
        labels: ['Styczeń', 'Luty', 'Marzec', 'Kwiecień', 'Maj', 'Czerwiec', 'Lipiec', 'Sierpień', 'Wrzesień', 'Październik', 'Listopad', 'Grudzień'],
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
      yearlyReportTitle: "",
    };
  },
  mounted() {
    this.fetchYearlyReport();
    this.fetchYearlyReport2();
  },
  methods: {
    async fetchYearlyReport() {
      let token = sessionStorage.getItem("token");
      const config = {
        headers: {Authorization: `Bearer ${token}`}
      };
      try {
        const response = await axios.get("http://localhost:8741/api/yearlyReport", config);

        this.chartOptions1.labels = response.data.map(item => item.name);
        this.series1 = response.data;

        const currentYear = new Date().getFullYear();
        this.yearlyReportTitle = `Roczny raport finansowy ${currentYear}`;
      } catch (error) {
        console.error("Błąd podczas pobierania danych:", error);
      }
    },
    async fetchYearlyReport2() {
      let token = sessionStorage.getItem("token");
      const config = {
        headers: { Authorization: `Bearer ${token}` }
      };
      try {
        const response = await axios.get("http://localhost:8741/api/yearlyIncomeReport", config);

        this.chartOptions2.labels = response.data.map(item => item.name);
        this.series2 = response.data;

        // Możesz również zaktualizować tytuł dla drugiego wykresu, jeśli jest inny.
      } catch (error) {
        console.error("Błąd podczas pobierania danych dla drugiego wykresu:", error);
      }
    },
  },
};
</script>
