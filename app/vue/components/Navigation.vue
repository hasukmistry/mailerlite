<template>
	<nav class="border-gray-200 bg-gray-50">
		<div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
			<div id="navbar-solid-bg" class="w-full block w-auto">
				<ul
					class="flex flex-wrap font-medium mt-4 rounded-lg bg-gray-50 space-x-8 rtl:space-x-reverse flex-row mt-0 border-0 bg-transparent"
				>
					<li>
						<router-link class="block py-2 px-3 p-0 rounded bg-transparent text-amber-700" to="/">
							Home
						</router-link>
					</li>
					<li>
						<router-link
							class="block py-2 px-3 p-0 text-gray-900 rounded hover:bg-gray-100 hover:bg-transparent border-0 hover:text-amber-700"
							to="/add"
						>
							Add Subscriber
						</router-link>
					</li>
				</ul>
			</div>
		</div>
	</nav>
</template>

<script>
export default {
	name: 'Navigation',
};
</script>
