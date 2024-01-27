<template>
	<div class="flex flex-col justify-center items-center p-10">
		<LoadingSvg />
		<div v-if="message" class="mt-2">{{ message }}</div>
	</div>
</template>

<script>
import LoadingSvg from '@/components/svg/Loading.vue';

export default {
	name: 'Loading',
	components: {
		LoadingSvg,
	},
	props: {
		message: {
			type: String,
			required: false,
			default: '',
		},
	},
};
</script>
