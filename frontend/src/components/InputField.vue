<template>
  <div class="form-control w-full">
    <label class="label">{{ label }}</label>
    <input
      :type="type"
      :placeholder="placeholder"
      class="input input-bordered w-full"
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
    />
  </div>
</template>

<script>
export default {
  props: {
    label: String,
    type: { type: String, default: 'text' },
    placeholder: String,
    modelValue: [String, Number],
  },
}
</script>
