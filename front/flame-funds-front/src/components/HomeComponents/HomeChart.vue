<template>
  <div class="container align-content-center">
    <div id="chart">
      <apexchart type="area" height="250" :options="chartOptions" :series="series"></apexchart>
    </div>
  </div>
</template>
<script>
import axios from "axios";

export default {
  name: "HomeChart",

  methods:{
    getData(){
      let token = sessionStorage.getItem("token");
      const config = {
        headers: { Authorization: `Bearer ${token}` }
      };
      axios.get("http://localhost:8741/api/dashboard",config)
          .then(response=>{
            console.log(response)
            this.series[0].data = response.data
            console.log(this.series[0].data)
          })
          .catch(error =>{
            console.log(error)
          })
    },
    preparePeriodics(){
      let token = sessionStorage.getItem("token");
      const config = {
        headers: { Authorization: `Bearer ${token}` }
      };
      axios.post("http://localhost:8741/api/endfinancialgoalcheck", {
        "date": this.date,
        "amount": this.amount,
      }, config)
          .then(response=>{
            console.log(response)
          })
          .catch(error =>{
            console.log(error)
          })
    }
  },

  created() {
    this.preparePeriodics(),
    this.getData()

  },

  data(){
    return {
      series: [{
        name: 'Saldo',
        data: [
        ]
      }],
      chartOptions: {
        chart: {
          type: 'area',
          stacked: false,
          height: 350,
          zoom: {
            type: 'x',
            enabled: true,
            autoScaleYaxis: true
          },
          toolbar: {
            autoSelected: 'zoom'
          }
        },
        dataLabels: {
          enabled: false
        },
        markers: {
          size: 0,
        },
        title: {
          text: 'Ostatnie zmiany salda',
          align: 'left'
        },
        fill: {
          type: 'gradient',
          gradient: {
            shadeIntensity: 1,
            inverseColors: false,
            opacityFrom: 0.5,
            opacityTo: 0.2,
            stops: [0, 90, 100]
          },
        },
        yaxis: {
          // labels: {
          //   formatter: function (val) {
          //     return (val / 1000000).toFixed(0);
          //   },
          // },
          title: {
            text: 'Saldo'
          },
        },
        xaxis: {
          type: 'datetime',
        },
        tooltip: {
          shared: false,

        }
      },
    }
  }
}
</script>
<style scoped>

</style>