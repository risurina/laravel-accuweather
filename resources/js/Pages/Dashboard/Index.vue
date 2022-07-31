<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Forecast from "./Partials/Forecast.vue";
</script>

<template>
	<AppLayout title="Dashboard">
		<template #header>
			<h2 class="font-semibold text-xl text-gray-800 leading-tight">
				Dashboard
			</h2>
		</template>

		<div>
			<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
				<Forecast />
			</div>
		</div>
	</AppLayout>
</template>
