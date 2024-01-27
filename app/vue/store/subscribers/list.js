import Vue from 'vue';
import Vuex from 'vuex';
import get from 'lodash/get';

Vue.use(Vuex);

const perPage = process.env.VUE_APP_SUBSCRIBERS_PER_PAGE || 10;

const initialState = {
	loading: false,
	list: null,
	listLength: 0,
	pagination: {
		show: false,
		current: 1,
		total: 0,
		records: 0,
	},
	alert: null,
};

export default {
	namespaced: true,
	state: initialState,
	actions: {
		async fetchSubscribers({ commit }, payload) {
			commit('TOGGLE_LOADING', true);

			try {
				const listApiUrl = this._vm.$urls.api('subscribers');

				const response = await this._vm.$http
					.get(listApiUrl)
					.query({ page: payload.page })
					.then((response) => response)
					.catch((error) => {
						console.error(error);
						return null;
					});

				const list = get(response, 'body.data') || initialState.list;

				commit('SET_LIST_LENGTH', list ? list.length : 0);
				commit('SET_LIST', list);
				commit('SET_PAGINATION', get(response, 'body.paginate') || initialState.pagination);
			} catch (error) {
				console.error(error);
			} finally {
				commit('TOGGLE_LOADING', false);
			}
		},
	},
	mutations: {
		TOGGLE_LOADING(state, value) {
			state.loading = value;
		},

		SET_LIST(state, list) {
			state.list = list;
		},

		SET_LIST_LENGTH(state, length) {
			state.listLength = length;
		},

		SET_PAGINATION(state, pagination) {
			state.pagination = {
				...pagination,
				show: (get(pagination, 'records') || 0) > perPage,
			};
		},

		SET_ALERT(state, alert) {
			state.alert = alert;
		},
	},
	getters: {
		isLoading: (state) => state.loading,
		getList: (state) => state.list,
		getPagination: (state) => state.pagination,
		getListLength: (state) => state.listLength,
		getAlert: (state) => state.alert,
	},
};
