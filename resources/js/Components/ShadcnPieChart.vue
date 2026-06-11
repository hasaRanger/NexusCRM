<script setup>
import { computed } from 'vue'
import { VisSingleContainer, VisDonut, VisTooltip } from '@unovis/vue'
import { Donut } from '@unovis/ts'

const props = defineProps({
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
    default: () => ['#818cf8', '#4f46e5', '#3730a3']
  },
  height: {
    type: [Number, String],
    default: 300
  }
})

const data = computed(() => {
  return props.labels.map((label, index) => ({
    name: label,
    value: props.series[index] || 0,
  }))
})

const total = computed(() => data.value.reduce((sum, item) => sum + item.value, 0))

const valueAccessor = (d) => d.value
const colorAccessor = (d, i) => props.colors[i % props.colors.length]

const tooltipTriggers = {
  [Donut.selectors.segment]: (d) => {
    const item = d.data;
    const index = data.value.indexOf(item);
    const color = props.colors[index % props.colors.length];
    const percentage = total.value > 0 ? ((item.value / total.value) * 100).toFixed(1) : 0;
    return `
      <div class="flex items-center gap-2 px-1 py-1 rounded-lg z-50 relative">
        <div class="w-3 h-3 rounded-full" style="background-color: ${color}"></div>
        <span class="font-semibold text-sm text-gray-900 dark:text-white">${item.name}</span>
        <span class="font-extrabold text-sm text-gray-700 dark:text-gray-300 ml-2">${percentage}%</span>
      </div>
    `
  }
}
</script>

<template>
  <div class="w-full grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
    <!-- Chart Container -->
    <div class="relative w-full col-span-2 flex justify-center" :style="{ height: `${height}px` }">
      <VisSingleContainer :data="data" :height="height" class="absolute inset-0 w-full h-full flex justify-center">
        <VisDonut
          :value="valueAccessor"
          :color="colorAccessor"
          :arcWidth="0" 
        />
        <VisTooltip :triggers="tooltipTriggers" />
      </VisSingleContainer>
    </div>
    
    <!-- Legend -->
    <div class="flex flex-col justify-center gap-5 col-span-1 ml-10">
      <div v-for="(item, index) in data" :key="item.name" class="flex items-center gap-3">
        <div class="w-4 h-4 rounded-full shadow-sm flex-shrink-0" :style="{ backgroundColor: colors[index % colors.length] }"></div>
        <span class="text-sm font-bold text-gray-700 dark:text-gray-300 whitespace-nowrap">{{ item.name }}</span>
      </div>
    </div>
  </div>
</template>
