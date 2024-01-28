<template>
	<Paginate
		v-model="page"
		:page-count="pagination.total"
		:click-handler="pageChanged"
		:prev-text="'Prev'"
		:next-text="'Next'"
		:container-class="'app-pagination'"
	/>
</template>

<script>
import Paginate from 'vuejs-paginate';

export default {
	name: 'Pagination',
	components: {
		Paginate,
	},
	props: {
		pagination: {
			type: Object,
			required: true,
		},
	},
	data() {
		return {
			page: 1,
		};
	},
	created() {
		this.page = this.pagination.current;
	},
	methods: {
		pageChanged(pageNumber) {
			this.$emit('page-changed', pageNumber);
		},
	},
};
</script>

<style lang="scss">
.app-pagination {
	@apply inline-flex -space-x-px text-sm;
	li {
		a {
			@apply flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-100 hover:text-gray-200 bg-sky-500 border border-e-0 border-sky-200 hover:bg-sky-800;
		}
		&:first-child a {
			@apply rounded-s-lg;
		}
		&.active {
			a {
				@apply text-gray-200 hover:text-gray-200 border border-sky-200 bg-sky-700 hover:bg-sky-800;
			}
		}
		&.disabled {
			a {
				@apply hover:cursor-not-allowed bg-sky-100 text-gray-400;
			}
		}
		&:last-child a {
			@apply leading-tight rounded-e-lg;
		}
	}
}
</style>
