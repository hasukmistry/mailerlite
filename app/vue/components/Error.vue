<template>
	<div v-if="message" class="flex items-center p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
		<InfoSvg />
		<span class="sr-only">Info</span>
		<div><span class="font-medium">Sorry!</span> {{ message }}</div>
	</div>
</template>

<script>
import InfoSvg from '@/components/svg/Info.vue';

export default {
	name: 'Error',
	components: {
		InfoSvg,
	},
	props: {
		message: {
			type: String,
			required: true,
		},
	},
};
</script>
