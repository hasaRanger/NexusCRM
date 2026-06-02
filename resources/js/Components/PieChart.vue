<script>
import VueApexCharts from "vue3-apexcharts";

export default {
  components: {
    apexchart: VueApexCharts,
  },
  props: {
    series: {
      type: Array,
      required: true,
    },
    labels: {
      type: Array,
      required: true,
    },
    colors: {
      type: Array,
      default: () => ['#ca8a04', '#3498db', '#2ecc71']
    }
  },
  computed: {
    chartOptions() {
      return {
        chart: {
          width: 380,
          type: 'pie',
        },
        labels: this.labels,
        
        // 1. Link your colors prop to the chart
        colors: this.colors, 

        // 2. Customize text ON the pie slices (Data Labels)
        dataLabels: {
          enabled: true,
          // Format what the text says (e.g., value, percentage, or name)
          formatter: function (val, opts) {
            return opts.w.globals.labels[opts.seriesIndex] + ":  " + val.toFixed(1) + "%";
          },
          style: {
            fontSize: '10px',
            fontWeight: 'bold',
            colors: ['#000000'] // Color of the text itself
          },
          dropShadow: {
            enabled: true,
            top: 1,
            left: 1,
            blur: 1,
            opacity: 0.5
          }
        },

        // 3. Fine-tune data label placement
        plotOptions: {
          pie: {
            dataLabels: {
              // Adjust this to move labels further in or out of the center
              offset: -10, 
              minAngleToShowLabel: 10 
            }
          }
        },

        // 4. Customize text AROUND the pie chart (Legend)
        legend: {
          position: 'right', // Options: 'top', 'bottom', 'left', 'right'
          horizontalAlign: 'center',
          fontWeight: 'bold',
          fontSize: '12px',
          markers: {
            radius: 12, // Makes the color dots round or square
          }
        },

        responsive: [
          {
            breakpoint: 480,
            options: {
              chart: {
                width: 200,
              },
              legend: {
                position: 'bottom',
              },
            },
          },
        ],
      };
    }
  }
}
</script>
<template>
  <apexchart type="pie" :options="chartOptions" :series="series"></apexchart>
</template>