import Vue from 'vue';
import Vuex from 'vuex';
import get from 'lodash/get';

Vue.use(Vuex);

const initialState = {
	loading: false,
	error: null,
};

export default {
	namespaced: true,
	state: initialState,
	actions: {
		async createSubscriber({ commit }, payload) {
			let createStatus = null;

			commit('TOGGLE_LOADING', true);

			try {
				const postApiUrl = this._vm.$urls.api('subscriber');

				const response = await this._vm.$http
					.post(postApiUrl)
					.send(payload)
					.then((response) => response)
					.catch((error) => {
						if (error.response && error.response.body) {
							// legit error from API
							commit('SET_ERROR', error.response.body);
						} else {
							console.error(error);
						}
						return null;
					});

				createStatus = get(response, 'status') || null;
				console.log(createStatus);
			} catch (error) {
				console.error(error);
			} finally {
				commit('TOGGLE_LOADING', false);
			}

			return createStatus ? true : false;
		},
	},
	mutations: {
		TOGGLE_LOADING(state, value) {
			state.loading = value;
		},

		SET_ERROR(state, error) {
			state.error = get(error, 'error') || null;
		},
	},
	getters: {
		isLoading: (state) => state.loading,
		getError: (state) => state.error || null,
	},
};
