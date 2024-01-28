<template>
	<div>
		<Navigation />

		<div class="flex flex-col w-full justify-center items-start">
			<div v-if="isLoading" class="py-10 px-2 md:px-14">
				<Loading message="Please wait, creating subscriber..." />
			</div>

			<div v-else class="flex flex-col pt-4 px-2 md:px-24 w-[100%] md:size-full">
				<div class="max-w-lg p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50" role="alert">
					<span class="font-medium">Info alert!</span>
					You can add your subscriber here. Make sure to enter valid email address.
				</div>

				<div v-if="getError" class="max-w-lg text-sm text-blue-800 rounded-lg">
					<Error :message="getError" />
				</div>

				<form class="flex flex-col max-w-lg" @submit.prevent="submitForm">
					<div class="py-5 mb-5">
						<div class="relative z-0 w-full group">
							<input
								id="email"
								v-model="email"
								v-validate="'required|email'"
								type="email"
								name="email"
								class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
								:class="{ 'border-red-500': errors.has('email') }"
								placeholder=" "
								required
							/>
							<label
								for="email"
								class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
							>
								Email address
							</label>
						</div>
						<span v-show="errors.has('email')" class="text-red-500 text-xs">
							{{ errors.first('email') }}
						</span>
					</div>

					<div class="grid md:grid-cols-2 md:gap-6">
						<div class="py-5">
							<div class="relative z-0 w-full mb-5 group">
								<input
									id="name"
									v-model="name"
									v-validate="'required|alpha_spaces'"
									type="text"
									name="name"
									class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
									placeholder=" "
									required
								/>
								<label
									for="name"
									class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
								>
									First name
								</label>
							</div>
							<span v-show="errors.has('name')" class="text-red-500 text-xs">
								{{ errors.first('name') }}
							</span>
						</div>

						<div class="py-5">
							<div class="relative z-0 w-full mb-5 group">
								<input
									id="lastName"
									v-model="lastName"
									v-validate="'alpha_spaces'"
									type="text"
									name="lastName"
									class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
									placeholder=" "
								/>
								<label
									for="lastName"
									class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
								>
									Last name
								</label>
								<span v-show="errors.has('lastName')" class="text-red-500 text-xs">
									{{ errors.first('lastName') }}
								</span>
							</div>
						</div>
					</div>

					<div class="grid md:grid-cols-2 md:gap-6 py-5">
						<div class="relative z-0 w-full mb-5 group">
							<label class="relative inline-flex items-center mb-5 cursor-pointer">
								<input v-model="status" type="checkbox" class="sr-only peer" />
								<div
									class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:w-5 after:h-5 after:transition-all peer-checked:bg-blue-600"
								></div>
								<span class="ms-3 text-sm font-medium text-gray-900">{{ statusText }}</span>
							</label>
						</div>
					</div>

					<button
						type="submit"
						class="max-w-xs text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center"
					>
						Submit
					</button>
				</form>
			</div>
		</div>
	</div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import Navigation from '@/components/Navigation.vue';
import Loading from '@/components/Loading.vue';
import Error from '@/components/Error.vue';
import { Validator } from 'vee-validate';

// Add a custom rule.
Validator.extend('alpha_spaces', {
	validate: (value) => /^[A-Za-z\s]*$/.test(value),
	getMessage: (field) => field + ' may only contain alphabetic characters and spaces.',
});

export default {
	name: 'AddSubscriber',
	components: {
		Navigation,
		Loading,
		Error,
	},
	data() {
		return {
			email: '',
			name: '',
			lastName: '',
			status: true,
		};
	},
	computed: {
		...mapGetters('subscribers/add', ['isLoading', 'getError']),
		statusText() {
			return this.status ? 'Enabled' : 'Disabled';
		},
	},
	methods: {
		...mapActions('subscribers/add', ['createSubscriber']),
		async submitForm() {
			this.$validator.validateAll().then(async (result) => {
				if (result) {
					// If all fields are valid, do something
					const formData = {
						email: this.email,
						name: this.name,
						last_name: this.lastName,
						status: this.status ? 'active' : 'inactive',
					};

					// If last name is empty, remove it from formData
					if (!this.lastName) {
						delete formData.last_name;
					}

					const response = await this.createSubscriber(formData);

					if (response) {
						this.$store.commit('subscribers/list/SET_ALERT', 'Subscriber added successfully!');
						this.$router.push({ name: 'list-subscribers' });
					}
				}
			});
		},
	},
};
</script>
