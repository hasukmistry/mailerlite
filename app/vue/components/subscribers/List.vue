<template>
	<div>
		<Navigation />
		<div class="flex flex-col w-full justify-center items-center">
			<Loading v-if="isLoading" message="Please wait, fetching subscribers..." />
			<div v-else class="flex flex-col px-2 md:px-24 w-[100%] md:size-full">
				<h1 class="font-bold text-amber-600 text-lg py-5 text-center">Subscribers</h1>

				<Alert v-if="alertMessage" :message="alertMessage" />

				<Error v-if="getListLength === 0" message="There are no subscribers at the moment." />

				<div v-else class="relative overflow-x-auto">
					<table class="w-full text-sm border text-left text-gray-70">
						<thead class="text-xs border-b uppercase bg-gray-5">
							<tr>
								<th scope="col" class="px-6 py-3">Full Name</th>
								<th scope="col" class="px-6 py-3">Email</th>
								<th scope="col" class="px-6 py-3">Status</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(item, index) in getList" :key="index" class="bg-white border-b">
								<th scope="row" class="px-6 py-4 font-medium whitespace-nowrap">
									{{ get(item, 'name') }} {{ get(item, 'last_name') || '' }}
								</th>
								<td class="px-6 py-4">{{ get(item, 'email') }}</td>
								<td class="px-6 py-4">{{ get(item, 'status') }}</td>
							</tr>
						</tbody>
					</table>
				</div>

				<Pagination
					v-if="getPagination.show"
					:pagination="getPagination"
					class="mt-10 justify-center md:justify-end"
					@page-changed="navigateToPage"
				/>
			</div>
		</div>
	</div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import get from 'lodash/get';
import Loading from '@/components/Loading.vue';
import Navigation from '@/components/Navigation.vue';
import Alert from '@/components/Alert.vue';
import Error from '@/components/Error.vue';
import Pagination from '@/components/subscribers/Pagination.vue';

export default {
	name: 'ListSubscribers',
	components: {
		Loading,
		Navigation,
		Alert,
		Error,
		Pagination,
	},
	computed: {
		...mapGetters('subscribers/list', ['isLoading', 'getList', 'getListLength', 'getPagination', 'getAlert']),
		alertMessage() {
			return this.getAlert;
		},
	},
	watch: {
		$route: function () {
			this.paginate();
		},
	},
	created() {
		this.paginate();
	},
	mounted() {
		// unset all the alrets
		setTimeout(() => {
			this.$store.commit('subscribers/list/SET_ALERT', null);
		}, 5000);
	},
	methods: {
		...mapActions('subscribers/list', ['fetchSubscribers']),
		get,
		paginate() {
			let payload = {};
			const { params } = this.$route;
			if ('undefined' === typeof params.page) {
				payload = { page: 1 };
			} else {
				payload = { page: params.page };
			}

			this.fetchSubscribers(payload);
		},
		navigateToPage(pageNumber) {
			if ('paginate-subscribers' === this.$route.name && this.$route.params.page === pageNumber) {
				// Already on this page, no need to navigate
				return;
			}

			this.$router.push({ name: 'paginate-subscribers', params: { page: pageNumber } }).catch((err) => {
				if ('NavigationDuplicated' !== err.name) {
					console.warn(err);
					throw err;
				}
			});
		},
	},
};
</script>

<style lang="scss" scoped></style>
