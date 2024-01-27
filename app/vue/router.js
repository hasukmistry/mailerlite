import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter);

const routes = [
	{
		path: '/',
		name: 'list-subscribers',
		component: () => import(`@/components/subscribers/List.vue`),
	},
	{
		path: '/page/:page',
		name: 'paginate-subscribers',
		component: () => import(`@/components/subscribers/List.vue`),
	},
	{
		path: '/add',
		name: 'add-subscriber',
		component: () => import(`@/components/subscribers/Add.vue`),
	},
];

export default new VueRouter({
	mode: 'history',
	routes,
});
