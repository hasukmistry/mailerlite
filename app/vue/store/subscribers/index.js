import Vue from 'vue';
import Vuex from 'vuex';
import list from '@/store/subscribers/list';
import add from '@/store/subscribers/add';

Vue.use(Vuex);

export default {
	namespaced: true,
	modules: {
		list,
		add,
	},
};
