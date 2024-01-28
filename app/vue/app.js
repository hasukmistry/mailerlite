import '@sass/app.scss';
import Vue from 'vue';
import App from '@/App.vue';
import store from '@/store/index';
import router from '@/router';
import superagent from 'superagent';
import VeeValidate from 'vee-validate';

Vue.use(VeeValidate);

const request = superagent.agent();

// request interceptor
request.use((req) => {
	req.url = `${process.env.VUE_APP_API_HOST || 'http://localhost:8000'}/${req.url}`;
	return req;
});

// api url generator
Vue.prototype.$urls = {};
Vue.prototype.$urls.api = function (url) {
	return 'mailerlite/v1/' + url;
};

// http request
Vue.prototype.$http = request;

new Vue({
	el: '#app',
	router,
	store,
	render: (h) => h(App),
});
