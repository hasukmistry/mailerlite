import Vue from 'vue';
import Vuex from 'vuex';

import subscribers from '@/store/subscribers/index';

Vue.use(Vuex);

export default new Vuex.Store({
	strict: 'production' !== process.env.NODE_ENV,
	modules: {
		subscribers,
	},
});
