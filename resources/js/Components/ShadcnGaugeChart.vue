<script setup>
import { computed } from 'vue'
import { VisSingleContainer, VisDonut } from '@unovis/vue'
import { DONUT_HALF_ANGLE_RANGE_TOP } from '@unovis/ts'

const props = defineProps({
  amount: {
    type: [Number, String],
    default: 0,
  },
  target: {
    type: [Number, String],
    default: 1000
  },
  height: {
    type: [Number, String],
    default: 300
  }
})

const value = computed(() => parseFloat(props.amount) || 0)
const parsedTarget = computed(() => parseFloat(props.target) || 1000)
const remainder = computed(() => Math.max(0, parsedTarget.value - value.value))

const data = computed(() => [
  { name: 'Revenue', value: value.value },
  { name: 'Remaining', value: remainder.value }
])

const valueAccessor = (d) => d.value
const colorAccessor = (d, i) => i === 0 ? '#4f46e5' : '#c7d2fe' // Indigo-600 vs Gray-200
</script>

<template>
  <div class="relative flex items-center justify-center w-full" :style="{ height: `${height}px` }">
    <VisSingleContainer :data="data" :height="height" class="absolute inset-0 w-full h-full">
      <VisDonut
        :value="valueAccessor"
        :color="colorAccessor"
        :arcWidth="30"
        :angleRange="DONUT_HALF_ANGLE_RANGE_TOP"
      />
    </VisSingleContainer>
    <div class="absolute bottom-[25%] flex flex-col items-center">
      <span class="text-2xl font-extrabold text-gray-900">{{ ((value / parsedTarget) * 100).toFixed(1) }}%</span>
      <span class="text-sm font-semibold text-gray-500">of {{ parsedTarget }} Target</span>
    </div>
  </div>
</template>
