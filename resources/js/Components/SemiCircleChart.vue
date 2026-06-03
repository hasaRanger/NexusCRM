<script>
import VueApexCharts from "vue3-apexcharts";
export default {
  components: {
    apexchart: VueApexCharts,
  },
  props: {
    amount: {
      type: [Number, String],
      default: 0,
    },
    target: {
      type: Number,
      default: 1000
    },
    fontSize: {
      type: String,
      default: '24px'
    },
    fontWeight: {
      type: [String, Number],
      default: 'bold'
    },
    color: {
      type: String,
      default: '#111827'
    },
    barColor: {
      type: String,
      default: '#4f46e5' // Tailwind indigo-600
    }
  },
  computed: {
    series() {
      const val = parseFloat(this.amount) || 0;
      const percentage = (val / this.target) * 100;
      return [parseFloat(percentage.toFixed(1))];
    },
    chartOptions() {
      return {
        chart: {
          height: 200,
          type: 'gauge',
        },
        colors: [this.barColor],
        plotOptions: {
          radialBar: {
            startAngle: -90,
            endAngle: 90,
            track: {
              background: '#d1d5db',
              strokeWidth: '100%',
              margin: 0,
            },
            dataLabels: {
              name: { show: false },
              value: {
                offsetY: -2,
                fontSize: this.fontSize,
                fontWeight: this.fontWeight,
                color: this.color,
                formatter: function (val) {
                  return val + '%'
                },
              },
            },
          },
        },
        fill: {
          type: 'linear',
          gradient: {
            shade: 'light',
            shadeIntensity: 0.4,
            inverseColors: false,
            opacityFrom: 1,
            opacityTo: 1,
            stops: [0, 50, 53, 91],
          },
        },
        labels: ['Score'],
      };
    }
  },
}
</script>

<template>
  <apexchart type="radialBar" :options="chartOptions" :series="series"></apexchart>
</template>
